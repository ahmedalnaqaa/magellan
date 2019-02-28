<?php

namespace TaskBundle\Entity;

use TaskBundle\Constant\TaskStatuses;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\User;

/**
 * Task
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Expose()
     * @Serializer\Groups({"Default"})
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="text", length=255)
     * @Serializer\Expose()
     * @Serializer\Groups({"Default"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="assignee", type="text", length=100)
     * @Serializer\Expose()
     * @Serializer\Groups({"Default"})
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $assignee;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="smallint", nullable=true)
     * @Assert\NotBlank()
     */
    private $status;

    /**
     * @var \DateTime
     * @ORM\Column(name="issue", type="datetime")
     * @Serializer\Expose()
     * @Serializer\Groups({"Default"})
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * @Serializer\Groups({"Details"})
     * @Serializer\Expose
     */
    protected $user;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (!$this->getIssue()) {
            $this->setIssue(new \DateTime());
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Task
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set assignee
     *
     * @param string $assignee
     *
     * @return Task
     */
    public function setAssignee($assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return string
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Task
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get status label
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("status")
     * @Serializer\Groups({"Default"})
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return TaskStatuses::getLabel($this->getStatus());
    }

    /**
     * Set issue
     *
     * @param \DateTime $issue
     *
     * @return Task
     */
    public function setIssue($issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \DateTime
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Task
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
