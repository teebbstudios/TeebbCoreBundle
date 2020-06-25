<?php


namespace Teebb\CoreBundle\Repository\Fields;


use Gedmo\Sortable\Entity\Repository\SortableRepository;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

class FieldConfigurationRepository extends SortableRepository
{
    /**
     * @param FieldConfiguration $fieldConfiguration
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(FieldConfiguration $fieldConfiguration)
    {
        $this->_em->persist($fieldConfiguration);
        $this->_em->flush();
    }

    /**
     * @param FieldConfiguration $fieldConfiguration
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(FieldConfiguration $fieldConfiguration)
    {
        $this->_em->remove($fieldConfiguration);
        $this->_em->flush();
    }

    /**
     * 查询某类型所有字段
     *
     * @param string $typeAlias
     * @return array
     */
    public function findAllTypesFields(string $typeAlias)
    {
        return $this->findBy(['typeAlias' => $typeAlias]);
    }

    /**
     * 获取SortableRepository config 调整排序方式
     * @return array|null
     */
    public function getSortableConfig()
    {
        return $this->config;
    }

    /**
     * 获取降序结果
     *
     * @param array $groupValues
     * @return \Doctrine\ORM\Query
     */
    public function getBySortableGroupsQueryDesc(array $groupValues = array())
    {
        return $this->getBySortableGroupsQueryBuilder($groupValues)
            ->orderBy('n.' . $this->config['position'], 'DESC')
            ->getQuery();
    }
}