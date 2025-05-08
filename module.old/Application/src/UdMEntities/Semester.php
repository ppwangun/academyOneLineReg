<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Semester
 *
 * @ORM\Table(name="semester", indexes={@ORM\Index(name="fk_semester_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class Semester
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="starting_date", type="datetime", nullable=true)
     */
    private $startingDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ending_date", type="datetime", nullable=true)
     */
    private $endingDate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;

    /**
     * @var \AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;



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
     * Set code.
     *
     * @param string $code
     *
     * @return Semester
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Semester
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
     * Set startingDate.
     *
     * @param \DateTime|null $startingDate
     *
     * @return Semester
     */
    public function setStartingDate($startingDate = null)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    /**
     * Get startingDate.
     *
     * @return \DateTime|null
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * Set endingDate.
     *
     * @param \DateTime|null $endingDate
     *
     * @return Semester
     */
    public function setEndingDate($endingDate = null)
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    /**
     * Get endingDate.
     *
     * @return \DateTime|null
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return Semester
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ranking.
     *
     * @param int|null $ranking
     *
     * @return Semester
     */
    public function setRanking($ranking = null)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking.
     *
     * @return int|null
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set academicYear.
     *
     * @param \AcademicYear|null $academicYear
     *
     * @return Semester
     */
    public function setAcademicYear(\AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return \AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }
}
