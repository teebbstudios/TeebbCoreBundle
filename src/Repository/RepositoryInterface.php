<?php


namespace Teebb\CoreBundle\Repository;


interface RepositoryInterface
{
    public const ORDER_ASCENDING = 'ASC';

    public const ORDER_DESCENDING = 'DESC';

    public function createPaginator(array $criteria = [], array $sorting = []): iterable;

}