<?php


namespace Teebb\CoreBundle\Twig;


use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\ItemInterface;
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

    /**
     * @var AdapterInterface
     */
    private $cacheAdapter;

    public function __construct(TemplateRegistry $registry, EntityManagerInterface $entityManager,
                                AdapterInterface $cacheAdapter,
                                string $rootHostUrl)
    {
        $this->version = Kernel::VERSION;
        $this->templateRegistry = $registry;
        $this->uploadRootUrl = $rootHostUrl;
        $this->entityManager = $entityManager;
        $this->cacheAdapter = $cacheAdapter;
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
     * @throws InvalidArgumentException
     */
    public function getOptionValue(string $optionName)
    {
        if (!$this->hasCache($optionName)) {
            $optionRepo = $this->entityManager->getRepository(Option::class);
            $option = $optionRepo->findOneBy(['optionName' => $optionName]);

            return $this->getCache($optionName, $option->getOptionValue());
        }
        return $this->getCache($optionName);
    }

    /**
     * 用于页面部分数据缓存
     * @param string $cacheKey
     * @param mixed $data
     * @return mixed
     */
    public function getCache(string $cacheKey, $data = null)
    {
        return $this->cacheAdapter->get($cacheKey,
            function (ItemInterface $item) use ($data) {
                return $data;
            });
    }

    /**
     * @param string $cacheKey
     * @return bool
     * @throws InvalidArgumentException
     */
    public function hasCache(string $cacheKey): bool
    {
        return $this->cacheAdapter->hasItem($cacheKey);
    }
}