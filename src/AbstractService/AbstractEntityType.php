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


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Metadata\EntityTypeMetadataInterface;
use Teebb\CoreBundle\Repository\RepositoryInterface;
use Teebb\CoreBundle\Route\EntityTypePathBuilder;
use Teebb\CoreBundle\Route\EntityTypeRouteCollection;
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
    protected $metadata;

    /**
     * 内容实体类型的routes
     *
     * @var EntityTypeRouteCollection
     */
    protected $routes;

    /**
     * @var EntityTypePathBuilder
     */
    protected $pathBuilder;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var PathInfoGeneratorInterface
     */
    protected $pathInfoGenerator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityTypePathBuilder $pathBuilder, ContainerInterface $container,
                                PathInfoGeneratorInterface $pathInfoGenerator, EntityManagerInterface $entityManager)
    {
        $this->pathBuilder = $pathBuilder;
        $this->container = $container;
        $this->pathInfoGenerator = $pathInfoGenerator;
        $this->entityManager = $entityManager;
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
    public function getEntityTypeRepository(): RepositoryInterface
    {
        return $this->entityManager->getRepository($this->getTypeEntityClass());
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
        $routeName = $this->metadata->getBundle() . '_' . $name;

        return $this->pathInfoGenerator->hasRoute($serviceId, $routeName);
    }

    /**￿
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
        //$routeCollection->addRoute('example', 'pattern');
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

    /**
     * @inheritDoc
     */
    public function getTypeEntityClass(): string
    {
        return $this->metadata->getTypeEntity();
    }

    /**
     * @inheritDoc
     */
    public function getEntityClassName(): string
    {
        return $this->metadata->getEntity();
    }

    /**
     * @inheritDoc
     */
    public function getBundle(): string
    {
        return $this->metadata->getBundle();
    }

    /**
     * @inheritDoc
     */
    public function getRouteName(string $name): string
    {
        return $this->metadata->getBundle() . '_' . $name;
    }

    /**
     * @inheritDoc
     */
    public function getEntityFormType(): string
    {
        return $this->metadata->getEntityFormType();
    }

    /**
     * @inheritDoc
     */
    public function getAllFields(string $typeAlias): array
    {
        $fieldConfigRepository = $this->entityManager->getRepository(FieldConfiguration::class);
        return $fieldConfigRepository->findBy(['bundle' => $this->getBundle(), 'typeAlias' => $typeAlias],
            ['delta' => 'ASC', 'id' => 'ASC']);
    }

    /**
     * @inheritDoc
     */
    public function getFieldService(string $fieldType): FieldInterface
    {
        return $this->container->get('teebb.core.field.' . $fieldType);
    }

    /**
     * @inheritDoc
     */
    public function getAllFieldsData(BaseContent $contentEntity, string $typeAlias): array
    {
        /**@var FieldConfiguration[] $fields * */
        $fields = $this->getAllFields($typeAlias);

        $fieldDatas = [];
        foreach ($fields as $field) {
            $fieldType = $field->getFieldType();
            $fieldLabel = $field->getFieldLabel();
            $fieldService = $this->getFieldService($field->getFieldType());

            $fieldData = $fieldService->getFieldEntityData($contentEntity, $field, $this->getEntityClassName());
            $fieldDatas[$field->getFieldAlias()] = [
                'field_type' => $fieldType,
                'field_label' => $fieldLabel,
                'field' => $field,
                'data' => $fieldData
            ];
        }

        return $fieldDatas;
    }

    /**
     * @inheritDoc
     */
    public function getSingleFieldData(BaseContent $contentEntity, string $filedAlias): array
    {
        $fieldConfigRepository = $this->entityManager->getRepository(FieldConfiguration::class);
        /**@var FieldConfiguration $field * */
        $field = $fieldConfigRepository->findOneBy(['fieldAlias' => $filedAlias]);

        $fieldService = $this->getFieldService($field->getFieldType());

        return $fieldService->getFieldEntityData($contentEntity, $field, $this->getEntityClassName());
    }

    /**
     * 获取内容指定 字段类型 的字段数据
     * @param BaseContent $baseContent
     * @param string $typeAlias 内容类型别名
     * @param string $fieldType 字段类型
     * @return array
     */
    public function getSpecifyTypeFieldData(BaseContent $baseContent, string $typeAlias, string $fieldType): array
    {
        $fieldConfigRepository = $this->entityManager->getRepository(FieldConfiguration::class);
        /**@var FieldConfiguration[] $fields * */
        $fields = $fieldConfigRepository->findBy(['bundle' => $this->getBundle(), 'typeAlias' => $typeAlias, 'fieldType' => $fieldType],
            ['delta' => 'ASC', 'id' => 'ASC']);

        $fieldService = $this->getFieldService($fieldType);

        $field_data = [];
        foreach ($fields as $field) {
            $field_data[$field->getFieldAlias()] = $fieldService->getFieldEntityData($baseContent, $field, $this->getEntityClassName());
        }

        return $field_data;
    }
}