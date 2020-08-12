<?php


namespace Teebb\CoreBundle\Repository;


class UserRepository extends EntityRepository
{
    public function findUserByEmail(string $email)
    {
        return $this->findOneBy(['email' => $email]);
    }
}