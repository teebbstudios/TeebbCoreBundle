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
use Teebb\CoreBundle\Doctrine\DBAL\FieldDBALUtils;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Metadata\FieldMetadataInterface;
use Doctrine\ORM\Mapping\MappingException;

/**
 * Class AbstractField
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var FieldMetadataInterface
     */
    protected $metadata;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
     */
    public function getFieldEntityData(BaseContent $contentEntity, FieldConfiguration $fieldConfiguration, string $targetEntityClassName): array
    {
        $evm = $this->entityManager->getEventManager();
        $dynamicChangeMetadataListener = new DynamicChangeFieldMetadataListener($fieldConfiguration, $targetEntityClassName);
        $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

        $fieldDBALUtils = new FieldDBALUtils($this->entityManager, $fieldConfiguration);

        $qb = $this->entityManager->getConnection()->createQueryBuilder();

        $conditions = [$qb->expr()->eq('entity_id', '?'), $qb->expr()->eq('types', '?')];
        $parameters = [$contentEntity->getId(), $fieldConfiguration->getTypeAlias()];
        $orderBy = ['delta' => 'ASC'];

        $fieldRows = $fieldDBALUtils->fetchFieldItem($qb, $conditions, $parameters, $orderBy);

        $fieldData = [];
        foreach ($fieldRows as $fieldRow) {
            $fieldEntity = $this->transformFieldRowToFieldEntity($fieldRow, $contentEntity);
            array_push($fieldData, $fieldEntity);
        }

        $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

        return $fieldData;
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