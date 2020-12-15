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
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Doctrine\DBAL\FieldDBALUtils;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Event\DataCacheEvent;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Metadata\FieldMetadataInterface;
use Doctrine\ORM\Mapping\MappingException;
use Teebb\CoreBundle\Traits\GenerateNameTrait;

/**
 * Class AbstractField
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractField implements FieldInterface
{
    use GenerateNameTrait;

    /**
     * @var FieldMetadataInterface
     */
    protected $metadata;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var AdapterInterface
     */
    private $cacheAdapter;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                AdapterInterface $cacheAdapter)
    {
        $this->entityManager = $entityManager;
        $this->cacheAdapter = $cacheAdapter;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function getFieldId(): string
    {
        if (null == $this->metadata) {
            throw new \Exception(sprintf('The field service "%s" $metadata did not set.', self::class));
        }
        return $this->metadata->getId();
    }

    /**
     * @inheritDoc
     */
    public function setFieldMetadata(FieldMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function getFieldMetadata(): FieldMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * 获取字段Entity类名
     * @return string
     */
    public function getFieldEntity(): string
    {
        return $this->metadata->getEntity();
    }

    /**
     * 获取字段设置表单Entity全类名
     * @return string
     */
    public function getFieldConfigFormEntity(): string
    {
        return $this->metadata->getFieldFormConfigEntity();
    }

    /**
     * 获取字段设置表单Type全类名
     * @return string
     */
    public function getFieldConfigFormType(): string
    {
        return $this->metadata->getFieldFormConfigType();
    }

    /**
     * @inheritDoc
     */
    public function getFieldFormType(): string
    {
        return $this->metadata->getFieldFormType();
    }

    /**
     * @inheritDoc
     * @throws MappingException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getFieldEntityData(BaseContent $contentEntity, FieldConfiguration $fieldConfiguration,
                                       string $targetEntityClassName, bool $flushCache = false): array
    {
        //添加缓存,每个字段一个缓存项,缓存KEY规则： bundle_contentId_fieldAlias_fieldType
        $cacheKey = $this->generateCacheKey($contentEntity, $fieldConfiguration);

        //刷新缓存，用于编辑内容时显示真实数据
        if ($flushCache) {
            $deleteCacheEvent = new DataCacheEvent();
            $deleteCacheEvent->setNeedDeleteCacheKeyArray([$cacheKey]);
            $this->eventDispatcher->dispatch($deleteCacheEvent, $deleteCacheEvent::DELETE_FIELD_CACHE);
        }

        //如果没有此缓存则发送消息生成此缓存
        if (!$this->cacheAdapter->hasItem($cacheKey)) {
            $cacheEvent = new DataCacheEvent();
            $cacheEvent->setBaseContent($contentEntity);
            $cacheEvent->setFieldService($this);
            $cacheEvent->setFieldConfiguration($fieldConfiguration);
            $cacheEvent->setTargetEntityClassName($targetEntityClassName);

            $this->eventDispatcher->dispatch($cacheEvent, $cacheEvent::GET_FIELD_CACHE);
        }

        return $this->cacheAdapter->getItem($cacheKey)->get();

//        $evm = $this->entityManager->getEventManager();
//        $dynamicChangeMetadataListener = new DynamicChangeFieldMetadataListener($fieldConfiguration, $targetEntityClassName);
//        $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);
//
//        $fieldDBALUtils = new FieldDBALUtils($this->entityManager, $fieldConfiguration);
//
//        $qb = $this->entityManager->getConnection()->createQueryBuilder();
//
//        $conditions = [$qb->expr()->eq('entity_id', '?'), $qb->expr()->eq('types', '?')];
//        $parameters = [$contentEntity->getId(), $fieldConfiguration->getTypeAlias()];
//        $orderBy = ['delta' => 'ASC'];
//
//        $fieldRows = $fieldDBALUtils->fetchFieldItem($qb, $conditions, $parameters, $orderBy);
//
//        $fieldData = [];
//        foreach ($fieldRows as $fieldRow) {
//            $fieldEntity = $this->transformFieldRowToFieldEntity($fieldRow, $contentEntity);
//            array_push($fieldData, $fieldEntity);
//        }
//
//        $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);
//
//        return $fieldData;
    }

    /**
     * @inheritDoc
     * @throws MappingException
     * @throws \Exception
     */
    public function transformFieldRowToFieldEntity(array $fieldRow, BaseContent $targetContentEntity)
    {
        $fieldEntityClassName = $this->getFieldEntity();
        $fieldEntity = new $fieldEntityClassName();
        $classMetadata = $this->entityManager->getClassMetadata($fieldEntityClassName);

        foreach ($fieldRow as $columnName => $value) {
            $fieldName = $classMetadata->getFieldForColumn($columnName);

            if ($classMetadata->hasField($fieldName)) {
                $fieldMapping = $classMetadata->getFieldMapping($fieldName);

                if ($fieldMapping['type'] === 'datetime') {
                    $value = new \DateTime($value);
                }
                if ($fieldMapping['type'] === 'array') {
                    $value = unserialize($value);
                }

                $classMetadata->setFieldValue($fieldEntity, $fieldName, $value);
            }

            if ($classMetadata->hasAssociation($fieldName)) {
                //如果字段名是entity 则直接指定为引用content
                if ($fieldName == 'entity') {
                    $classMetadata->setFieldValue($fieldEntity, $fieldName, $targetContentEntity);
                } else {
                    $fieldAssociationMapping = $classMetadata->getAssociationMapping($fieldName);
                    $targetEntityRepository = $this->entityManager->getRepository($fieldAssociationMapping['targetEntity']);

                    $targetEntityKey = $fieldAssociationMapping['sourceToTargetKeyColumns'][$columnName];
                    $targetEntity = $targetEntityRepository->findOneBy([$targetEntityKey => $value]);

                    $classMetadata->setFieldValue($fieldEntity, $fieldName, $targetEntity);
                }
            }
        }

        return $fieldEntity;
    }
}