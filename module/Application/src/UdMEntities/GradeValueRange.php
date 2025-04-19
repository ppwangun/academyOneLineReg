<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * GradeValueRange
 *
 * @ORM\Table(name="grade_value_range", indexes={@ORM\Index(name="fk_grade_value_range_grade1_idx", columns={"grade_id"})})
 * @ORM\Entity
 */
class GradeValueRange
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
     * @var float|null
     *
     * @ORM\Column(name="minsur20", type="float", precision=10, scale=0, nullable=true)
     */
    private $minsur20;

    /**
     * @var float|null
     *
     * @ORM\Column(name="maxsur20", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxsur20;

    /**
     * @var float|null
     *
     * @ORM\Column(name="minsur100", type="float", precision=10, scale=0, nullable=true)
     */
    private $minsur100;

    /**
     * @var float|null
     *
     * @ORM\Column(name="maxsur100", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxsur100;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grade_value", type="string", length=45, nullable=true)
     */
    private $gradeValue;

    /**
     * @var float|null
     *
     * @ORM\Column(name="grade_points", type="float", precision=10, scale=0, nullable=true)
     */
    private $gradePoints;

    /**
     * @var \Grade
     *
     * @ORM\ManyToOne(targetEntity="Grade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
     * })
     */
    private $grade;



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
     * Set minsur20.
     *
     * @param float|null $minsur20
     *
     * @return GradeValueRange
     */
    public function setMinsur20($minsur20 = null)
    {
        $this->minsur20 = $minsur20;

        return $this;
    }

    /**
     * Get minsur20.
     *
     * @return float|null
     */
    public function getMinsur20()
    {
        return $this->minsur20;
    }

    /**
     * Set maxsur20.
     *
     * @param float|null $maxsur20
     *
     * @return GradeValueRange
     */
    public function setMaxsur20($maxsur20 = null)
    {
        $this->maxsur20 = $maxsur20;

        return $this;
    }

    /**
     * Get maxsur20.
     *
     * @return float|null
     */
    public function getMaxsur20()
    {
        return $this->maxsur20;
    }

    /**
     * Set minsur100.
     *
     * @param float|null $minsur100
     *
     * @return GradeValueRange
     */
    public function setMinsur100($minsur100 = null)
    {
        $this->minsur100 = $minsur100;

        return $this;
    }

    /**
     * Get minsur100.
     *
     * @return float|null
     */
    public function getMinsur100()
    {
        return $this->minsur100;
    }

    /**
     * Set maxsur100.
     *
     * @param float|null $maxsur100
     *
     * @return GradeValueRange
     */
    public function setMaxsur100($maxsur100 = null)
    {
        $this->maxsur100 = $maxsur100;

        return $this;
    }

    /**
     * Get maxsur100.
     *
     * @return float|null
     */
    public function getMaxsur100()
    {
        return $this->maxsur100;
    }

    /**
     * Set gradeValue.
     *
     * @param string|null $gradeValue
     *
     * @return GradeValueRange
     */
    public function setGradeValue($gradeValue = null)
    {
        $this->gradeValue = $gradeValue;

        return $this;
    }

    /**
     * Get gradeValue.
     *
     * @return string|null
     */
    public function getGradeValue()
    {
        return $this->gradeValue;
    }

    /**
     * Set gradePoints.
     *
     * @param float|null $gradePoints
     *
     * @return GradeValueRange
     */
    public function setGradePoints($gradePoints = null)
    {
        $this->gradePoints = $gradePoints;

        return $this;
    }

    /**
     * Get gradePoints.
     *
     * @return float|null
     */
    public function getGradePoints()
    {
        return $this->gradePoints;
    }

    /**
     * Set grade.
     *
     * @param \Grade|null $grade
     *
     * @return GradeValueRange
     */
    public function setGrade(\Grade $grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade.
     *
     * @return \Grade|null
     */
    public function getGrade()
    {
        return $this->grade;
    }
}
