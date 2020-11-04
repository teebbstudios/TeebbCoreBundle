<?php


namespace Teebb\CoreBundle\Twig;


use Doctrine\ORM\EntityManagerInterface;
use Teebb\CoreBundle\Application\Kernel;
use Teebb\CoreBundle\Entity\Option;
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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TemplateRegistry $registry, EntityManagerInterface $entityManager, string $rootHostUrl)
    {
        $this->version = Kernel::VERSION;
        $this->templateRegistry = $registry;
        $this->rootHostUrl = $rootHostUrl;
        $this->entityManager = $entityManager;
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

    public function getOptionValue(string $optionName)
    {
        $optionRepo = $this->entityManager->getRepository(Option::class);
        $option = $optionRepo->findOneBy(['optionName' => $optionName]);
        return $option->getOptionValue();
    }
}