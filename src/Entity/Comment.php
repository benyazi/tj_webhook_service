<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="integer")
     */
    private $tjId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $creatorId;

    /**
     * @ORM\Column(type="integer")
     */
    private $creatorTjId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $contentTjId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $replyToId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $replyToTjId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commentDate;

    /**
     * @ORM\Column(type="text")
     */
    private $commentData;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Comment
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Comment
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatorId()
    {
        return $this->creatorId;
    }

    /**
     * @param mixed $creatorId
     * @return Comment
     */
    public function setCreatorId($creatorId)
    {
        $this->creatorId = $creatorId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatorTjId()
    {
        return $this->creatorTjId;
    }

    /**
     * @param mixed $creatorTjId
     * @return Comment
     */
    public function setCreatorTjId($creatorTjId)
    {
        $this->creatorTjId = $creatorTjId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentTjId()
    {
        return $this->contentTjId;
    }

    /**
     * @param mixed $contentTjId
     * @return Comment
     */
    public function setContentTjId($contentTjId)
    {
        $this->contentTjId = $contentTjId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyToId()
    {
        return $this->replyToId;
    }

    /**
     * @param mixed $replyToId
     * @return Comment
     */
    public function setReplyToId($replyToId)
    {
        $this->replyToId = $replyToId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReplyToTjId()
    {
        return $this->replyToTjId;
    }

    /**
     * @param mixed $replyToTjId
     * @return Comment
     */
    public function setReplyToTjId($replyToTjId)
    {
        $this->replyToTjId = $replyToTjId;
        return $this;
    }

    /**
     * @return array
     */
    public function getCommentData()
    {
        return json_decode($this->commentData, true);
    }

    /**
     * @param array $commentData
     * @return Comment
     */
    public function setCommentData($commentData)
    {
        $this->commentData = json_encode($commentData);
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCommentDate()
    {
        return $this->commentDate;
    }

    /**
     * @param \DateTime $commentDate
     * @return Comment
     */
    public function setCommentDate($commentDate)
    {
        $this->commentDate = $commentDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTjId()
    {
        return $this->tjId;
    }

    /**
     * @param mixed $tjId
     * @return Comment
     */
    public function setTjId($tjId)
    {
        $this->tjId = $tjId;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'text' => $this->getText(),
            'content_tj_id' => $this->getContentTjId(),
            'creator_tj_id' => $this->getCreatorTjId(),
            'tj_id' => $this->getTjId()
        ];
    }
}