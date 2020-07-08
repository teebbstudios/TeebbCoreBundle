<?php


namespace Teebb\CoreBundle\Utils;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;

class FileHelper
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * FileUploader constructor.
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $path
     * @return false|resource
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function readStream(string $path)
    {
        $resource = $this->filesystem->readStream($path);

        if ($resource === false) {
            throw new \Exception(sprintf('Error opening stream for "%s"', $path));
        }

        return $resource;
    }

    /**
     * @param string $path
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function deleteFile(string $path)
    {
        $result = $this->filesystem->delete($path);

        if ($result === false) {
            throw new \Exception(sprintf('Error deleting "%s"', $path));
        }
    }

    /**
     * @param File $file
     * @param string $directory
     * @param bool $isPublic
     * @return string
     * @throws \Exception
     */
    public function uploadFile(File $file, string $directory, bool $isPublic = false): string
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->getClientOriginalExtension();

        $stream = fopen($file->getPathname(), 'r');
        $result = $this->filesystem->writeStream(
            $directory . '/' . $newFilename,
            $stream,
            [
                'visibility' => $isPublic ? AdapterInterface::VISIBILITY_PUBLIC : AdapterInterface::VISIBILITY_PRIVATE
            ]
        );

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    /**
     * @param string $path
     * @return false|string
     * @throws FileNotFoundException
     */
    public function getMimeType(string $path)
    {
        return $this->filesystem->getMimetype($path);
    }

    /**
     * 生成文件绝对路径
     * @param Request $request
     * @param string $rootHost
     * @param string $filePath
     * @return string
     */
    public function generateAbsoluteUrlPath(Request $request, string $rootHost, string $filePath)
    {
        $path = $rootHost . '/' . $filePath;

        // if it's already absolute, just return
        if (strpos($path, '://') !== false) {
            return $path;
        }
        return $request->getSchemeAndHttpHost() . $path;
    }

}