<?php


namespace Teebb\CoreBundle\Doctrine\DBAL;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Teebb\CoreBundle\Entity\Fields\BaseFieldItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Doctrine\ORM\Mapping\MappingException;

/**
 * 字段DBAL操作用于保存读取字段值
 */
class FieldDBALUtils
{
    /**
     * @var string
     */
    private $fieldTableName;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var Connection
     */
    private $conn;

    public function __construct(EntityManagerInterface $entityManager, FieldConfiguration $fieldConfiguration)
    {
        $this->conn = $entityManager->getConnection();
        $this->fieldTableName = $fieldConfiguration->getBundle() . '__field_' . $fieldConfiguration->getFieldAlias();
        $this->entityManager = $entityManager;
    }

    /**
     * insert字段Entity值到对应的表
     * @param BaseFieldItem $fieldItem 字段Entity Object
     * @throws MappingException
     */
    public function persistFieldItem(BaseFieldItem $fieldItem)
    {
        $classMetadata = $this->entityManager->getClassMetadata(get_class($fieldItem));

        $queryBuilder = $this->conn->createQueryBuilder();

        $columnArray = $this->getSqlColumnNamesArray($classMetadata);

        $columnParameters = $this->getFieldEntityColumnsValue($fieldItem, $classMetadata, $columnArray);

        if ($fieldItem->getId() === null) {
            $queryBuilder->insert($this->fieldTableName)->values($columnArray)->setParameters($columnParameters);
        } else {
            $queryBuilder->update($this->fieldTableName);

            foreach ($columnArray as $columnName => $positionHolder) {
                $queryBuilder->set($columnName, $positionHolder);
            }
            $queryBuilder->andWhere('id = ?');

            array_push($columnParameters, $fieldItem->getId());

            $queryBuilder->setParameters($columnParameters);

        }

        $queryBuilder->execute();
    }

    /**
     * 查询当前字段的数据
     *
     * @param QueryBuilder $queryBuilder
     * @param array $conditions 查询条件示例 'id' => $queryBuilder->expr()->eq(1)
     * @param array $parameters
     * @param array $orderBy 排序示例
     * @return array
     */
    public function fetchFieldItem(QueryBuilder $queryBuilder, array $conditions, array $parameters, array $orderBy)
    {
        $queryBuilder->select('*')->from($this->fieldTableName);

        foreach ($conditions as $condition) {
            $queryBuilder->andWhere($condition);
        }

        $queryBuilder->setParameters($parameters);
        foreach ($orderBy as $sort => $order) {
            $queryBuilder->addOrderBy($sort, $order);
        }

        return $queryBuilder->execute()->fetchAll();
    }

    /**
     * 获取字段数据表列名称
     * @param ClassMetadata $classMetadata
     * @return array
     */
    private function getSqlColumnNamesArray(ClassMetadata $classMetadata): array
    {
        $sqlValues = [];
        foreach ($classMetadata->getColumnNames() as $columnName) {

            $fieldName = $classMetadata->getFieldName($columnName);
            if (in_array($fieldName, $classMetadata->getIdentifier())) {
                continue;
            }

            $sqlValues[$columnName] = '?';
        }

        foreach ($classMetadata->getAssociationMappings() as $associationMapping) {
            foreach ($associationMapping['joinColumns'] as $joinColumn) {
                $sqlValues[$joinColumn['name']] = '?';
            }
        }
        return $sqlValues;
    }

    /**
     * 获取字段表的值
     * @param BaseFieldItem $fieldItem
     * @param ClassMetadata $classMetadata
     * @param array $sqlValues
     * @return array
     * @throws MappingException
     */
    private function getFieldEntityColumnsValue(BaseFieldItem $fieldItem, ClassMetadata $classMetadata, array $sqlValues)
    {
        $parameters = [];
        foreach ($sqlValues as $sqlValueColumn => $valueHolder) {
            $fieldName = $classMetadata->getFieldForColumn($sqlValueColumn);

            //如果是普通字段Mapping
            if ($classMetadata->hasField($fieldName)) {

                //如果是ID则根据ID策略自动生成一个ID
                if (in_array($fieldName, $classMetadata->getIdentifier())) {
                    continue;
                }

                $value = $classMetadata->getFieldValue($fieldItem, $fieldName);
                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                array_push($parameters, $value);
            }

            //如果是关系字段Mapping
            if ($classMetadata->hasAssociation($fieldName)) {

                $associationMapping = $classMetadata->getAssociationMapping($fieldName);

                $targetEntityKey = $associationMapping['sourceToTargetKeyColumns'][$sqlValueColumn];
                $targetEntityKeyMethodName = 'get' . ucfirst($targetEntityKey);

                $targetEntity = $classMetadata->getFieldValue($fieldItem, $fieldName);
                if (!method_exists($targetEntity, $targetEntityKeyMethodName)) {
                    throw new \RuntimeException(sprintf('The "%s" class must define "%s" method', get_class($targetEntity), $targetEntityKeyMethodName));
                }

                $value = $targetEntity->{$targetEntityKeyMethodName}();

                array_push($parameters, $value);
            }
        }

        return $parameters;
    }
}