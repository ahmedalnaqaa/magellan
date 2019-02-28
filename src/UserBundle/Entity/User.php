<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity()
 * @Serializer\ExclusionPolicy("all")
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 * @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"Profile", "Default", "Details"})
     * @Serializer\Expose
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\Regex(pattern="/^(?=.{4,60}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/")
     * @Assert\Length(
     *      min = 4,
     *      max = 60,
     * )
     * @Assert\NotBlank()
     * @Serializer\Groups({"Profile", "Default", "Details"})
     * @Serializer\Expose
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\Email()
     * @Assert\NotBlank()
     * @Serializer\Groups({"Default", "Details"})
     * @Serializer\Expose
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=100, nullable=true)
     * @Serializer\Groups({"Profile", "Default", "Details"})
     * @Serializer\Expose
     */
    protected $fullName;

    /**
     * @ORM\OneToMany(targetEntity="\TaskBundle\Entity\Task", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $tasks;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    // ---------------------------------------------------------------------

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
    }

    // ---------------------------------------------------------------------

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
