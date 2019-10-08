<?php
namespace App\Service;

use App\Entity\TjUser;
use App\Repository\TjUserRepository;
use Benyazi\CmttPhp\Api;
use Doctrine\ORM\EntityManagerInterface;

class TjUserService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $creatorData
     * @return TjUser|object|null
     */
    public function checkOrCreateUserByData($creatorData)
    {
        $id = (int) $creatorData['id'];
        /** @var TjUserRepository $repository */
        $repository = $this->em->getRepository(TjUser::class);
        $tjUser = $repository->findOneByTjId($id);
        if(empty($tjUser)) {
            $tjUser = new TjUser();
            $tjUser->setTjId($id);
            $tjUser->setName($creatorData['name']);
            $this->em->persist($tjUser);
        }
        $this->em->flush();
        return $tjUser;
    }

    /**
     * @param TjUser $tjUser
     * @return bool
     */
    public function isUserNeedUpdateInfo($tjUser)
    {
        if($tjUser->getTjId() < 1) {
            return false;
        }
        $dt = new \DateTime();
        $dt->sub(new \DateInterval('PT1H'));
        if(is_null($tjUser->getUpdateAt()) || $tjUser->getUpdateAt() < $dt) {
            return true;
        }
        return false;
    }

    public function updateUserInfo($userId)
    {
        /** @var TjUserRepository $repository */
        $repository = $this->em->getRepository(TjUser::class);
        /** @var TjUser $tjUser */
        $tjUser = $repository->find($userId);
        $client = new Api();
        if($tjUser->getTjId() > 0) {
            try {
                $userData = $client->getUser($tjUser->getTjId());
                $tjUser->setName($userData['name']);
                $tjUser->setKarma((int)$userData['karma']);
                $tjUser->setComments((int)$userData['counters']['comments']);
                $tjUser->setEntities((int)$userData['counters']['entries']);
                $tjUser->setSubs((int)$userData['subscribers_count']);
                $createdAt = $userData['created'];
                $createdAt = new \DateTime('@' . $createdAt);
                $tjUser->setCreatedAt($createdAt);
                $tjUser->setUpdateAt(new \DateTime());
                $this->em->persist($tjUser);
                $this->em->flush();
            } catch (\Exception $e) {
                throw $e;
            }
            sleep(1);
        }
    }
}