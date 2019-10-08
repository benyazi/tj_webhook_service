<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findOneByTjId($tjId)
    {
        return $this->findOneBy([
            'tjId' => $tjId
        ]);
    }
}