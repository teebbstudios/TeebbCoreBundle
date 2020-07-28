<?php


namespace Teebb\CoreBundle\Twig;


use Teebb\CoreBundle\Application\Kernel;
use Teebb\CoreBundle\Templating\TemplateRegistry;

class GlobalVariables
{
    /**
     * @var string
     */
    private $version;

    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;
    /**
     * @var string
     */
    private $rootHostUrl;

    public function __construct(TemplateRegistry $registry, string $rootHostUrl)
    {
        $this->version = Kernel::VERSION;
        $this->templateRegistry = $registry;
        $this->rootHostUrl = $rootHostUrl;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return TemplateRegistry
     */
    public function getTemplateRegistry(): TemplateRegistry
    {
        return $this->templateRegistry;
    }

    /**
     * @param TemplateRegistry $templateRegistry
     */
    public function setTemplateRegistry(TemplateRegistry $templateRegistry): void
    {
        $this->templateRegistry = $templateRegistry;
    }

    /**
     * @return string
     */
    public function getRootHostUrl(): string
    {
        return $this->rootHostUrl;
    }

    /**
     * @param string $rootHostUrl
     */
    public function setRootHostUrl(string $rootHostUrl): void
    {
        $this->rootHostUrl = $rootHostUrl;
    }

}