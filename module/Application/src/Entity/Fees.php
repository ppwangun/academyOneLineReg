<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Fees
 *
 * @ORM\Table(name="fees", indexes={@ORM\Index(name="fk_fees_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_fees_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class Fees
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
     * @var float
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $amount;

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
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;



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
     * Set amount
     *
     * @param float $amount
     *
     * @return Fees
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set academicYear
     *
     * @param \AcademicYear $academicYear
     *
     * @return Fees
     */
    public function setAcademicYear(\AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear
     *
     * @return \AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set classOfStudy
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return Fees
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return \ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }
}
