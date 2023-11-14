<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract", indexes={@ORM\Index(name="fk_contract_teacher1_idx", columns={"teacher_id"}), @ORM\Index(name="fk_contract_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class Contract
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
     * @ORM\Column(name="ref_number", type="string", length=45, nullable=false)
     */
    private $refNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="volume_hrs", type="string", length=45, nullable=true)
     */
    private $volumeHrs;

    /**
     * @var float|null
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
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;



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
     * Set refNumber.
     *
     * @param string $refNumber
     *
     * @return Contract
     */
    public function setRefNumber($refNumber)
    {
        $this->refNumber = $refNumber;
    
        return $this;
    }

    /**
     * Get refNumber.
     *
     * @return string
     */
    public function getRefNumber()
    {
        return $this->refNumber;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return Contract
     */
    public function setStatus($status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set volumeHrs.
     *
     * @param string|null $volumeHrs
     *
     * @return Contract
     */
    public function setVolumeHrs($volumeHrs = null)
    {
        $this->volumeHrs = $volumeHrs;
    
        return $this;
    }

    /**
     * Get volumeHrs.
     *
     * @return string|null
     */
    public function getVolumeHrs()
    {
        return $this->volumeHrs;
    }

    /**
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Contract
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set academicYear.
     *
     * @param \AcademicYear|null $academicYear
     *
     * @return Contract
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

    /**
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return Contract
     */
    public function setTeacher(\Teacher $teacher = null)
    {
        $this->teacher = $teacher;
    
        return $this;
    }

    /**
     * Get teacher.
     *
     * @return \Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
