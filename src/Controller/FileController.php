<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use League\Flysystem\FilesystemInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Cache\CacheManagerAwareInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\File\Exception\NoFileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\FileManaged;
use Teebb\CoreBundle\Exception\FileUploadException;
use Teebb\CoreBundle\ExpressionLanguage\Date;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\FileManagedRepository;
use Teebb\CoreBundle\Utils\FileHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class FileController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    private $fieldConfigurationRepo;

    /**
     * @var FileManagedRepository
     */
    private $fileManagedRepository;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var string
     */
    private $defaultUploadDir;
    /**
     * @var string
     */
    private $rootHost;
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * FileController constructor.
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param CacheManager $cacheManager
     * @param FilesystemInterface $filesystem
     * @param string $defaultUploadDir 默认文件上传目录
     * @param string $rootHost 默认文件上传主机
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, CacheManager $cacheManager,
                                FilesystemInterface $filesystem, string $defaultUploadDir, string $rootHost)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigurationRepo = $entityManager->getRepository(FieldConfiguration::class);
        $this->fileManagedRepository = $entityManager->getRepository(FileManaged::class);
        $this->filesystem = $filesystem;
        $this->validator = $validator;
        $this->defaultUploadDir = $defaultUploadDir;
        $this->rootHost = $rootHost;
        $this->cacheManager = $cacheManager;
    }

    /**
     * 文件字段上传action
     *
     * 如果 $request->get('field_alias') 有值则说明是文件或图像字段上传的文件，图像字段上传的文件需要额外处理，
     * 否则是html文本编辑器上传的文件。
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function fileUploadAction(Request $request)
    {
        $field_alias = $request->get('field_alias');

        /**@var UploadedFile $file * */
        $file = $request->files->get('file');
        if (null == $file) {
            throw new NoFileException('The post "file" not exist.');
        }

        $fieldType = null;
        //如果是文件或图像字段上传的文件
        if (null != $field_alias) {
            /**@var FieldConfiguration $fieldConfiguration * */
            $fieldConfiguration = $this->fieldConfigurationRepo->findOneBy(['fieldAlias' => $field_alias]);
            if (null == $fieldConfiguration) {
                throw new \InvalidArgumentException(sprintf('The field configuration object not found. Maybe the "%s" parameter wrong.', 'field_alias'));
            }

            //获取字段类型，如果是图像字段则需要额外处理
            $fieldType = $fieldConfiguration->getFieldType();

            $fieldSettings = $fieldConfiguration->getSettings();
            //对上传的文件进行校验
            $fileConstraints = [
                new File([
                    'maxSize' => $fieldSettings->getMaxSize() ?: ini_get('upload_max_filesize'),
                    'mimeTypes' => $this->getExtMimeTypes($fieldSettings->getAllowExt()),
                ])
            ];

            //如果是图像字段进行图像校验
            if ($fieldType == 'referenceImage') {
                $minResolution = $fieldSettings->getMinResolution();
                $maxResolution = $fieldSettings->getMaxResolution();
                $imageConstraint = new Image([
                    'minHeight' => $minResolution['height'] ?: null,
                    'minWidth' => $minResolution['width'] ?: null,
                    'maxHeight' => $maxResolution['height'] ?: null,
                    'maxWidth' => $maxResolution['width'] ?: null
                ]);
                array_push($fileConstraints, $imageConstraint);
            }

            /**@var ConstraintViolationList $results * */
            $results = $this->validator->validate($file, $fileConstraints);
            if (0 !== $results->count()) {
                $messages = [];
                /**@var ConstraintViolation $result * */
                foreach ($results as $result) {
                    array_push($messages, $result->getMessage());
                }
                throw new FileUploadException(implode(',', $messages));
            }
            //处理文件上传路径
            $distDirectory = $this->getFileUploadDir($fieldSettings->getUploadDir());
        } else {
            //如果是html编辑器上传的文件
            $distDirectory = $this->defaultUploadDir;
        }

        $fileUploader = new FileHelper($this->filesystem);
        $newFileName = $fileUploader->uploadFile($file, $distDirectory);

        $fileManaged = new FileManaged();
        $fileManaged->setFileName($newFileName);
        $fileManaged->setFileMime($file->getMimeType());
        $fileManaged->setFilePath($distDirectory . '/' . $newFileName);
        $fileManaged->setFileSize($file->getSize());
        $fileManaged->setOriginFileName($file->getClientOriginalName());

        $this->entityManager->persist($fileManaged);
        $this->entityManager->flush();

        //生成文件绝对路径
        $absoluteUrl = $fileUploader->generateAbsoluteUrlPath($request, $this->rootHost, $distDirectory . '/' . $newFileName);

        $result = ['file' => $fileManaged, 'url' => $absoluteUrl];

        //如果mimetype是image添加缩略图url
        if (false !== strpos($file->getMimeType(),'image/')){
            $thumbnailUrl = $this->cacheManager->generateUrl($distDirectory . '/' . $newFileName, 'squared_thumbnail_small');
            $result['thumbnailUrl'] = $thumbnailUrl;
        }

        return $this->json($result);
    }

    /**
     * 文件删除action
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \League\Flysystem\FileNotFoundException
     *
     */
    public function fileDeleteAction(Request $request)
    {
        $id = $request->get('id');
        if (null == $id) {
            throw new \InvalidArgumentException('The "id" parameter must post.');
        }
        /**@var FileManaged $fileManaged * */
        $fileManaged = $this->fileManagedRepository->findOneBy(['id' => $id]);
        if (null == $fileManaged) {
            throw new \InvalidArgumentException('The "id" parameter wrong. Don\'t hack the html code!!!');
        }

        //删除fileManaged Entity
        $this->entityManager->remove($fileManaged);
        $this->entityManager->flush();

        //删除原文件
        $fileUploader = new FileHelper($this->filesystem);
        $fileUploader->deleteFile($fileManaged->getFilePath());

        //删除liip_imagine cache文件
        $this->cacheManager->remove($fileManaged->getFilePath());

        return $this->json(null, 204);
    }

    /**
     * 读取文件内容，用于生成文件链接
     * @param Request $request
     */
    public function readFileAction(Request $request)
    {

    }

    /**
     * 处理文件上传目录
     *
     * @param string $uploadDir
     * @return string
     */
    private function getFileUploadDir(string $uploadDir): string
    {
        //获取目标目录
        $distDir = $uploadDir;
        if (preg_match("/^\[(\S+)]$/", $distDir, $matches)) {
            $expressionLanguage = new ExpressionLanguage();
            $distDir = $expressionLanguage->evaluate($matches[1], ['date' => new Date()]);
        }
        return $distDir;
    }

    /**
     * 根据文件后缀获取对应的mimeTypes
     * @param array $exts
     * @return array
     */
    private function getExtMimeTypes(array $exts)
    {
        $mimeTypes = new MimeTypes();

        $extMimeTypeArray = [];
        foreach ($exts as $ext) {
            $result = $mimeTypes->getMimeTypes($ext);
            if (!empty($result)) {
                array_walk_recursive($result, function ($value) use (&$extMimeTypeArray) {
                    array_push($extMimeTypeArray, $value);
                });
            }
        }
        return $extMimeTypeArray;
    }
}