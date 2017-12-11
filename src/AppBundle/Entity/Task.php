<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\TaskRepository")
 */
class Task implements RestViewInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255, nullable=false)
     */
    protected $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_completed", type="boolean", nullable=false, options={"default" = 0})
     */
    protected $isCompleted = false;

    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param int $id
     *
     * @return Task
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set User
     *
     * @param User $user
     *
     * @return Task
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get Content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set Content
     *
     * @param string $content
     *
     * @return Task
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get isCompleted
     *
     * @return boolean
     */
    public function isCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * Get isCompleted
     *
     * @return boolean
     */
    public function getIsCompleted()
    {
        return $this->isCompleted;
    }

    /**
     * Set IsCompleted
     *
     * @param boolean $isCompleted
     *
     * @return Task
     */
    public function setIsCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    /**
     * Get CreatedAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set CreatedAt
     *
     * @param \DateTime $createdAt
     *
     * @return Task
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Json Serialize
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'user_id' => $this->getUser()->getId(),
            'content' => $this->getContent(),
            'is_completed' => $this->getIsCompleted(),
            'created_at_timestamp' => $this->getCreatedAt()->getTimestamp(),
        ];
    }
}
