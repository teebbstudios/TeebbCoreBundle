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

    public function __construct(TemplateRegistry $registry)
    {
        $this->version = Kernel::VERSION;
        $this->templateRegistry = $registry;
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

}