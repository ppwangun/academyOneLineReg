<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Teacher
 *
 * @ORM\Table(name="teacher", indexes={@ORM\Index(name="fk_teacher_academic_ranck1_idx", columns={"academic_ranck_id"})})
 * @ORM\Entity
 */
class Teacher
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
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=true)
     */
    private $surname;

    /**
     * @var string|null
     *
     * @ORM\Column(name="academic rank", type="string", length=45, nullable=true)
     */
    private $academicRank;

    /**
     * @var \AcademicRanck
     *
     * @ORM\ManyToOne(targetEntity="AcademicRanck")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_ranck_id", referencedColumnName="id")
     * })
     */
    private $academicRanck;



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
     * Set name.
     *
     * @param string|null $name
     *
     * @return Teacher
     */
    public function setName($name = null)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname.
     *
     * @param string|null $surname
     *
     * @return Teacher
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname.
     *
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set academicRank.
     *
     * @param string|null $academicRank
     *
     * @return Teacher
     */
    public function setAcademicRank($academicRank = null)
    {
        $this->academicRank = $academicRank;
    
        return $this;
    }

    /**
     * Get academicRank.
     *
     * @return string|null
     */
    public function getAcademicRank()
    {
        return $this->academicRank;
    }

    /**
     * Set academicRanck.
     *
     * @param \AcademicRanck|null $academicRanck
     *
     * @return Teacher
     */
    public function setAcademicRanck(\AcademicRanck $academicRanck = null)
    {
        $this->academicRanck = $academicRanck;
    
        return $this;
    }

    /**
     * Get academicRanck.
     *
     * @return \AcademicRanck|null
     */
    public function getAcademicRanck()
    {
        return $this->academicRanck;
    }
}
