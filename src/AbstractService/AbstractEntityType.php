<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\AbstractService;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Route\EntityTypePathBuilder;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;
use Teebb\CoreBundle\Route\PathInfoGenerator;
use Teebb\CoreBundle\Route\PathInfoGeneratorInterface;

/**
 * 内容实体类型类
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractEntityType implements EntityTypeInterface
{
    /**
     * @var EntityTypeMetadataInterface
     */
    private $metadata;

    /**
     * 内容实体类型的routes
     *
     * @var EntityTypeRouteCollection
     */
    private $routes;

    /**
     * @var EntityTypePathBuilder
     */
    private $pathBuilder;

    /**
     * @var RepositoryInterface
     */
    private $entityTypeRepository;

    /**
     * 所有字段的类型和Service Id数组，
     *
     * @var array
     */
    private $fieldList = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var PathInfoGeneratorInterface
     */
    private $pathInfoGenerator;

    public function __construct(EntityTypePathBuilder $pathBuilder, ContainerInterface $container,
                                PathInfoGeneratorInterface $pathInfoGenerator)
    {
        $this->pathBuilder = $pathBuilder;
        $this->container = $container;
        $this->pathInfoGenerator = $pathInfoGenerator;
    }

    /**
     * @inheritDoc
     */
    public function setEntityTypeMetadata(EntityTypeMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function getEntityTypeMetadata(): EntityTypeMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @inheritDoc
     */
    public function setRepository(RepositoryInterface $repository): void
    {
        $this->entityTypeRepository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): RepositoryInterface
    {
        return $this->entityTypeRepository;
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): EntityTypeRouteCollection
    {

        $this->buildRoutes();

        return $this->routes;
    }

    /**
     * @inheritDoc
     */
    public function buildRoutes(): void
    {
        if (null !== $this->routes) {
            return;
        }

        $this->routes = new EntityTypeRouteCollection($this->metadata);

        $this->pathBuilder->build($this->routes);

        $this->configureRoutes($this->routes);
    }

    /**
     * @inheritDoc
     */
    public function hasRoute(string $serviceId, string $name): bool
    {
        $routeName = $this->metadata->getType() . '_' . $name;

        return $this->pathInfoGenerator->hasRoute($serviceId, $routeName);
    }

    /**
     * @return EntityTypePathBuilder
     */
    public function getPathBuilder(): EntityTypePathBuilder
    {
        return $this->pathBuilder;
    }

    /**
     * 覆盖此方法以自定义路由添加到EntityTypeRouteCollection.
     *
     * 自定义Controller继承自AbstractEntityTypeController并实现Action，并在注释中配置对应controller。
     *
     * @param EntityTypeRouteCollection $routeCollection
     */
    protected function configureRoutes(EntityTypeRouteCollection $routeCollection): void
    {
        //$this->routing->addRoute('example', 'pattern');
    }

    /**
     * @inheritDoc
     */
    public function getFields()
    {
        // TODO: Implement getFields() method.
    }

    /**
     * @inheritDoc
     */
    public function addField(FieldInterface $field): void
    {
        // TODO: Implement addField() method.
    }

    /**
     * @return array
     */
    public function getFieldList(): array
    {
        return $this->fieldList;
    }

    /**
     * @param array $fieldList
     */
    public function setFieldList(array $fieldList): void
    {
        $this->fieldList = $fieldList;
    }

    /**
     * @inheritDoc
     */
    public function generateFieldListData(): array
    {
        if (empty($this->fieldList)) {
            throw new \RuntimeException(sprintf('The service "%s" property "fieldList" cannot be empty.', get_class($this)));
        }

        $allFieldInfo = [];

        foreach ($this->fieldList as $type => $fieldServices) {
            foreach ($fieldServices as $fieldService) {

                /** @var FieldInterface $field * */
                $field = $this->container->get($fieldService);
                $fieldMetadata = $field->getFieldMetadata();

                $allFieldInfo[$type][] = [
                    'id' => $fieldMetadata->getId(),
                    'label' => $fieldMetadata->getLabel(),
                    'description' => $fieldMetadata->getDescription(),
                    'category' => $fieldMetadata->getCategory()
                ];

            }
        }

        return $allFieldInfo;
    }

    /**
     * @return array
     * @todo 需要根据权限判断当前用户可用Actions
     */
    public function getActionButtons(): array
    {
        $actions = ['index', 'create', 'update', 'delete', 'index_field', 'add_field', 'update_field', 'delete_field'];

        //Todo: 判断当前用户可用的权限

        return $actions;
    }

    /**
     * @return PathInfoGeneratorInterface
     */
    public function getPathInfoGenerator(): PathInfoGeneratorInterface
    {
        return $this->pathInfoGenerator;
    }
}