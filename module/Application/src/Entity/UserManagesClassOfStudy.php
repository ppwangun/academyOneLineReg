<?php

namespace Application\Entity;

use Application\Entity\User;
use Application\Entity\ClassOfStudy;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserManagesClassOfStudy
 *
 * @ORM\Table(name="user_manages_class_of_study", indexes={@ORM\Index(name="fk_user_has_class_of_study_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_user_has_class_of_study_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class UserManagesClassOfStudy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;



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
     * Set classOfStudy
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return UserManagesClassOfStudy
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return UserManagesClassOfStudy
     */
    public function setUser(User $user = null)
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
