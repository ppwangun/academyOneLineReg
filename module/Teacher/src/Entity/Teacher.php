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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=true)
     */
    private $surname;

    /**
     * @var string
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Teacher
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Teacher
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set academicRank
     *
     * @param string $academicRank
     *
     * @return Teacher
     */
    public function setAcademicRank($academicRank)
    {
        $this->academicRank = $academicRank;

        return $this;
    }

    /**
     * Get academicRank
     *
     * @return string
     */
    public function getAcademicRank()
    {
        return $this->academicRank;
    }

    /**
     * Set academicRanck
     *
     * @param \AcademicRanck $academicRanck
     *
     * @return Teacher
     */
    public function setAcademicRanck(\AcademicRanck $academicRanck = null)
    {
        $this->academicRanck = $academicRanck;

        return $this;
    }

    /**
     * Get academicRanck
     *
     * @return \AcademicRanck
     */
    public function getAcademicRanck()
    {
        return $this->academicRanck;
    }
}
