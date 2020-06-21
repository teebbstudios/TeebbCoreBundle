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
}