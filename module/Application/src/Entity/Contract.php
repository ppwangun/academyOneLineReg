<?php



namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Semester;
use Application\Entity\AcademicYear;
use Application\Entity\Teacher;

/**
 * Contract
 *
 * @ORM\Table(name="contract", indexes={@ORM\Index(name="fk_contract_subject1_idx", columns={"subject_id"}), @ORM\Index(name="fk_contract_teacher1_idx", columns={"teacher_id"}), @ORM\Index(name="fk_contract_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_contract_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_contract_teaching_unit1_idx", columns={"teaching_unit_id"})})
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
     * @var float|null
     *
     * @ORM\Column(name="volume_hrs", type="float", precision=10, scale=0, nullable=true)
     */
    private $volumeHrs;

    /**
     * @var float|null
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var float|null
     *
     * @ORM\Column(name="cm_hrs", type="float", precision=10, scale=0, nullable=true)
     */
    private $cmHrs;

    /**
     * @var float|null
     *
     * @ORM\Column(name="td_hrs", type="float", precision=10, scale=0, nullable=true)
     */
    private $tdHrs;

    /**
     * @var float|null
     *
     * @ORM\Column(name="tp_hrs", type="float", precision=10, scale=0, nullable=true)
     */
    private $tpHrs;

    /**
     * @var AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

    /**
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     * })
     */
    private $subject;

    /**
     * @var Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;

    /**
     * @var TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;



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
     * @param float|null $volumeHrs
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
     * @return float|null
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
     * Set cmHrs.
     *
     * @param float|null $cmHrs
     *
     * @return Contract
     */
    public function setCmHrs($cmHrs = null)
    {
        $this->cmHrs = $cmHrs;
    
        return $this;
    }

    /**
     * Get cmHrs.
     *
     * @return float|null
     */
    public function getCmHrs()
    {
        return $this->cmHrs;
    }

    /**
     * Set tdHrs.
     *
     * @param float|null $tdHrs
     *
     * @return Contract
     */
    public function setTdHrs($tdHrs = null)
    {
        $this->tdHrs = $tdHrs;
    
        return $this;
    }

    /**
     * Get tdHrs.
     *
     * @return float|null
     */
    public function getTdHrs()
    {
        return $this->tdHrs;
    }

    /**
     * Set tpHrs.
     *
     * @param float|null $tpHrs
     *
     * @return Contract
     */
    public function setTpHrs($tpHrs = null)
    {
        $this->tpHrs = $tpHrs;
    
        return $this;
    }

    /**
     * Get tpHrs.
     *
     * @return float|null
     */
    public function getTpHrs()
    {
        return $this->tpHrs;
    }

    /**
     * Set academicYear.
     *
     * @param AcademicYear|null $academicYear
     *
     * @return Contract
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;
    
        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set semester.
     *
     * @param Semester|null $semester
     *
     * @return Contract
     */
    public function setSemester(Semester $semester = null)
    {
        $this->semester = $semester;
    
        return $this;
    }

    /**
     * Get semester.
     *
     * @return Semester|null
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set subject.
     *
     * @param Subject|null $subject
     *
     * @return Contract
     */
    public function setSubject(Subject $subject = null)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject.
     *
     * @return Subject|null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set teacher.
     *
     * @param Teacher|null $teacher
     *
     * @return Contract
     */
    public function setTeacher(Teacher $teacher = null)
    {
        $this->teacher = $teacher;
    
        return $this;
    }

    /**
     * Get teacher.
     *
     * @return Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set teachingUnit.
     *
     * @param TeachingUnit|null $teachingUnit
     *
     * @return Contract
     */
    public function setTeachingUnit(TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;
    
        return $this;
    }

    /**
     * Get teachingUnit.
     *
     * @return TeachingUnit|null
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }
}
