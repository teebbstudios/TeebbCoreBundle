<?php


namespace Teebb\CoreBundle\Twig\Extensions;


use Psr\Container\ContainerInterface;
use Teebb\CoreBundle\AbstractService\AbstractEntityType;
use Teebb\CoreBundle\Entity\BaseContent;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * 用于内容的字段显示的Twig方法
 */
class BaseContentFieldsShowExtension extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('show_content_all_fields', [$this, 'showContentAllFields']),
            new TwigFunction('get_content_field', [$this, 'getContentField'])
        ];
    }

    /**
     * 获取当前内容所有的字段数据
     *
     * @param BaseContent $baseContent 要获取字段信息的内容object
     * @param string $bundle 当前内容所属的bundle，用于获取对应的 EntityTypeService
     * @param string $typeAlias 内容的类型机读别名
     * @return array
     */
    public function showContentAllFields(BaseContent $baseContent, string $bundle, string $typeAlias)
    {
        $entityTypeService = $this->getEntityTypeService($bundle);
        
        return $entityTypeService->getAllFieldsData($baseContent, $typeAlias);
    }

    /**
     * 获取当前内容某字段数据
     *
     * @param BaseContent $baseContent 要获取字段信息的内容object
     * @param string $bundle 当前内容所属的bundle，用于获取对应的 EntityTypeService
     * @param string $filedAlias 字段的机读别名
     * @return array
     */
    public function getContentField(BaseContent $baseContent, string $bundle, string $filedAlias)
    {
        $entityTypeService = $this->getEntityTypeService($bundle);
        return $entityTypeService->getSingleFieldData($baseContent, $filedAlias);
    }

    /**
     * 用于获取当前内容所属bundle的EntityType Service
     * @param string $bundle 拼合EntityType Service Id
     * @return AbstractEntityType
     */
    private function getEntityTypeService(string $bundle)
    {
        $entityTypeServiceId = 'teebb.core.entity_type.' . $bundle;

        return $this->container->get($entityTypeServiceId);
    }

}