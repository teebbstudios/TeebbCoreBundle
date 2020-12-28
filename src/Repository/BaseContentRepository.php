<?php


namespace Teebb\CoreBundle\Repository;


class BaseContentRepository extends EntityRepository
{
    /**
     * 批量获取内容
     * @param string $contentClassName
     * @param array|null $contentIds
     * @return mixed
     */
    public function getBatchContentItems(string $contentClassName, ?array $contentIds)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c')->from($contentClassName, 'c')
            ->andWhere($queryBuilder->expr()->in('c.id', ':contentIds'))
            ->setParameter('contentIds', $contentIds);

        return $queryBuilder->getQuery()->getResult();
    }

}