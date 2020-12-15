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
    private $uploadRootUrl;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TemplateRegistry $registry, EntityManagerInterface $entityManager, string $rootHostUrl)
    {
        $this->version = Kernel::VERSION;
        $this->templateRegistry = $registry;
        $this->uploadRootUrl = $rootHostUrl;
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
    public function getUploadRootUrl(): string
    {
        return $this->uploadRootUrl;
    }

    /**
     * @param string $uploadRootUrl
     */
    public function setUploadRootUrl(string $uploadRootUrl): void
    {
        $this->uploadRootUrl = $uploadRootUrl;
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * 使用此方法获取TEEBB的设置值
     * @param string $optionName
     * @return mixed
     */
    public function getOptionValue(string $optionName)
    {
        $optionRepo = $this->entityManager->getRepository(Option::class);
        $option = $optionRepo->findOneBy(['optionName' => $optionName]);
        return $option->getOptionValue();
    }
}