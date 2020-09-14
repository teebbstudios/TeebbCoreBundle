<?php


namespace Teebb\CoreBundle\Twig\Extensions;


use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Utils\FileHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UploadFileShowExtension extends AbstractExtension
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var FileHelper
     */
    private $fileUploader;

    /**
     * @var string
     */
    private $baseRootHost;

    public function __construct(FilesystemInterface $filesystem, string $baseRootHost)
    {
        $this->filesystem = $filesystem;
        $this->fileUploader = new FileHelper($filesystem);
        $this->baseRootHost = $baseRootHost;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('show_file_url', [$this, 'showFileUrl'])
        ];
    }

    public function showFileUrl(Request $request, string $filePath)
    {
        return $this->fileUploader->generateAbsoluteUrlPath($request, $this->baseRootHost, $filePath);
    }
}