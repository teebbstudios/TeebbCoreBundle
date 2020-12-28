<?php


namespace Teebb\CoreBundle\Repository;


use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class EntityRepository
 */
class EntityRepository extends BaseEntityRepository implements RepositoryInterface
{
    /**
     * @param object $object
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(object $object): void
    {
        $this->_em->persist($object);
        $this->_em->flush();
    }

    /**
     * @param object $object
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(object $object): void
    {
        $this->_em->remove($object);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function createPaginator(array $criteria = [], array $sorting = []): iterable
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $this->applyCriteria($queryBuilder, $criteria);
        $this->applySorting($queryBuilder, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    protected function getPaginator(QueryBuilder $queryBuilder): Pagerfanta
    {
        // Use output walkers option in DoctrineORMAdapter should be false as it affects performance greatly (see #3775)
        return new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
    }

    /**
     * @param $objects
     * @return Pagerfanta
     */
    protected function getArrayPaginator($objects): Pagerfanta
    {
        return new Pagerfanta(new ArrayAdapter($objects));
    }

    protected function applyCriteria(QueryBuilder $queryBuilder, array $criteria = []): void
    {
        foreach ($criteria as $property => $value) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            $name = $this->getPropertyName($property);

            if (null === $value) {
                $queryBuilder->andWhere($queryBuilder->expr()->isNull($name));
            } elseif (is_array($value)) {
                $queryBuilder->andWhere($queryBuilder->expr()->in($name, $value));
            } elseif ('' !== $value) {
                $parameter = str_replace('.', '_', $property);
                if (false !== strpos($value, '%')){
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->like($name, ':' . $parameter))
                        ->setParameter($parameter, $value);
                }else{
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->eq($name, ':' . $parameter))
                        ->setParameter($parameter, $value);
                }
            }
        }
    }

    protected function applySorting(QueryBuilder $queryBuilder, array $sorting = []): void
    {
        foreach ($sorting as $property => $order) {
            if (!in_array($property, array_merge($this->_class->getAssociationNames(), $this->_class->getFieldNames()), true)) {
                continue;
            }

            if (!empty($order)) {
                $queryBuilder->addOrderBy($this->getPropertyName($property), $order);
            }
        }
    }

    protected function getPropertyName(string $name): string
    {
        if (false === strpos($name, '.')) {
            return 'o' . '.' . $name;
        }

        return $name;
    }

}