<?php


namespace Teebb\CoreBundle\Repository;


use Doctrine\Persistence\ObjectRepository;

interface RepositoryInterface extends ObjectRepository
{
    public const ORDER_ASCENDING = 'ASC';

    public const ORDER_DESCENDING = 'DESC';

    public function createPaginator(array $criteria = [], array $sorting = []): iterable;

    public function save(object $object): void;

    public function remove(object $object): void;
}