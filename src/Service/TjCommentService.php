<?php
namespace App\Service;

use App\Entity\Comment;
use App\Entity\TjUser;
use App\Repository\CommentRepository;
use App\Repository\TjUserRepository;
use Benyazi\CmttPhp\Api;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;

class TjCommentService
{
    private $em;
    private $tjNewCommentProducer;

    public function __construct(EntityManagerInterface $em, ProducerInterface $tjNewCommentProducer)
    {
        $this->em = $em;
        $this->tjNewCommentProducer = $tjNewCommentProducer;
    }

    /**
     * @param array $commentData
     * @return Comment|object|null
     */
    public function checkOrCreateByData($commentData)
    {
        $id = (int) $commentData['id'];
        /** @var CommentRepository $repository */
        $repository = $this->em->getRepository(Comment::class);
        $comment = $repository->findOneByTjId($id);
        if(empty($comment)) {
            $comment = new Comment();
            $comment->setTjId($commentData['id']);
            $comment->setUrl($commentData['url']);
            $comment->setText($commentData['text']);
            $comment->setContentTjId($commentData['content']['id']);
            $comment->setCreatorTjId($commentData['creator']['id']);
            if(isset($commentData['reply_to'])) {
                $comment->setReplyToTjId($commentData['reply_to']['id']);
            }
            $comment->setCommentDate(new \DateTime());
            $comment->setCommentData($commentData);
            $this->em->persist($comment);
        }
        $this->em->flush();
        $this->tjNewCommentProducer->publish(serialize($comment->toArray()));
        return $comment;
    }
}