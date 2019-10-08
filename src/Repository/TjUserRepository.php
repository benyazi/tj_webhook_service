<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TjUserRepository extends EntityRepository
{
    public function findOneByTjId($tjId)
    {
        return $this->findOneBy([
            'tjId' => $tjId
        ]);
    }
}