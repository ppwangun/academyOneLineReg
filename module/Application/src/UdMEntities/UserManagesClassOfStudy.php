<?php



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
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param \User|null $user
     *
     * @return UserManagesClassOfStudy
     */
    public function setUser(\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return UserManagesClassOfStudy
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy|null
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }
}
