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
            $tjUser->setName(isset($creatorData['name'])?:'котик');
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
        $client = new Api(Api::TJOURNAL, $_ENV['TJ_TOKEN']);
        if($tjUser->getTjId() > 0) {
            try {
                echo 'Get user data from API'.PHP_EOL;
                $userData = $client->getUser($tjUser->getTjId());
                var_dump($userData);
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

    public function getFullUserInfo($tjUserId)
    {
        $tjUser = $this->checkOrCreateUserByData([
            'id' => $tjUserId
        ]);
        if($this->isUserNeedUpdateInfo($tjUser)) {
            $this->updateUserInfo($tjUser->getId());
        }
        return $tjUser->toArray();
    }
}