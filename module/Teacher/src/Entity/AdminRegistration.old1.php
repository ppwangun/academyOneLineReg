<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\Student;
use Application\Entity\ClassOfStudy;

/**
 * AdminRegistration
 *
 * @ORM\Table(name="admin_registration", uniqueConstraints={@ORM\UniqueConstraint(name="contrat_ID_UNIQUE", columns={"contrat_id"})}, indexes={@ORM\Index(name="fk_admin_registration_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_admin_registration_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_admin_registration_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class AdminRegistration
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
     * @var \DateTime
     *
     * @ORM\Column(name="registering_date", type="datetime", nullable=true)
     */
    private $registeringDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pv_registration_date", type="datetime", nullable=false)
     */
    private $pvRegistrationDate = 'CURRENT_TIMESTAMP';

    /**
     * @var integer
     *
     * @ORM\Column(name="class_of_study_id", type="integer", nullable=true)
     */
    private $classOfStudyId;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_status", type="string", length=10, nullable=true)
     */
    private $registrationStatus = 'DRAFT';

    /**
     * @var float
     *
     * @ORM\Column(name="fees_dotation", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesDotation;

    /**
     * @var float
     *
     * @ORM\Column(name="fees_paid", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesPaid = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="fees_balance_from_previous_year", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesBalanceFromPreviousYear = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="contrat_id", type="string", length=45, nullable=false)
     */
    private $contratId;

    /**
     * @var string
     *
     * @ORM\Column(name="moratorium_autorization", type="string", length=45, nullable=true)
     */
    private $moratoriumAutorization;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';
    
    /**
     * @var string
     *
     * @ORM\Column(name="decision", type="string", length=45, nullable=false)
     */
    private $decision; 
    
   

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
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



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
     * Set registeringDate
     *
     * @param \DateTime $registeringDate
     *
     * @return AdminRegistration
     */
    public function setRegisteringDate($registeringDate)
    {
        $this->registeringDate = $registeringDate;

        return $this;
    }

    /**
     * Get registeringDate
     *
     * @return \DateTime
     */
    public function getRegisteringDate()
    {
        return $this->registeringDate;
    }

    /**
     * Set pvRegistrationDate
     *
     * @param \DateTime $pvRegistrationDate
     *
     * @return AdminRegistration
     */
    public function setPvRegistrationDate($pvRegistrationDate)
    {
        $this->pvRegistrationDate = $pvRegistrationDate;

        return $this;
    }

    /**
     * Get pvRegistrationDate
     *
     * @return \DateTime
     */
    public function getPvRegistrationDate()
    {
        return $this->pvRegistrationDate;
    }

    /**
     * Set classOfStudyId
     *
     * @param integer $classOfStudyId
     *
     * @return AdminRegistration
     */
    public function setClassOfStudyId($classOfStudyId)
    {
        $this->classOfStudyId = $classOfStudyId;

        return $this;
    }

    /**
     * Get classOfStudyId
     *
     * @return integer
     */
    public function getClassOfStudyId()
    {
        return $this->classOfStudyId;
    }

    /**
     * Set registrationStatus
     *
     * @param string $registrationStatus
     *
     * @return AdminRegistration
     */
    public function setRegistrationStatus($registrationStatus)
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }

    /**
     * Get registrationStatus
     *
     * @return string
     */
    public function getRegistrationStatus()
    {
        return $this->registrationStatus;
    }

    /**
     * Set feesDotation
     *
     * @param float $feesDotation
     *
     * @return AdminRegistration
     */
    public function setFeesDotation($feesDotation)
    {
        $this->feesDotation = $feesDotation;

        return $this;
    }

    /**
     * Get feesDotation
     *
     * @return float
     */
    public function getFeesDotation()
    {
        return $this->feesDotation;
    }

    /**
     * Set feesPaid
     *
     * @param float $feesPaid
     *
     * @return AdminRegistration
     */
    public function setFeesPaid($feesPaid)
    {
        $this->feesPaid = $feesPaid;

        return $this;
    }

    /**
     * Get feesPaid
     *
     * @return float
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }

    /**
     * Set feesBalanceFromPreviousYear
     *
     * @param float $feesBalanceFromPreviousYear
     *
     * @return AdminRegistration
     */
    public function setFeesBalanceFromPreviousYear($feesBalanceFromPreviousYear)
    {
        $this->feesBalanceFromPreviousYear = $feesBalanceFromPreviousYear;

        return $this;
    }

    /**
     * Get feesBalanceFromPreviousYear
     *
     * @return float
     */
    public function getFeesBalanceFromPreviousYear()
    {
        return $this->feesBalanceFromPreviousYear;
    }

    /**
     * Set contratId
     *
     * @param string $contratId
     *
     * @return AdminRegistration
     */
    public function setContratId($contratId)
    {
        $this->contratId = $contratId;

        return $this;
    }

    /**
     * Get contratId
     *
     * @return string
     */
    public function getContratId()
    {
        return $this->contratId;
    }

    /**
     * Set moratoriumAutorization
     *
     * @param string $moratoriumAutorization
     *
     * @return AdminRegistration
     */
    public function setMoratoriumAutorization($moratoriumAutorization)
    {
        $this->moratoriumAutorization = $moratoriumAutorization;

        return $this;
    }

    /**
     * Get moratoriumAutorization
     *
     * @return string
     */
    public function getMoratoriumAutorization()
    {
        return $this->moratoriumAutorization;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AdminRegistration
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set decision
     *
     * @param string $decision
     *
     * @return AdminRegistration
     */
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get decision
     *
     * @return string
     */
    public function getDecision()
    {
        return $this->decision;
    }
    
    /**
     * Set isStudentRepeating
     *
     * @param integer $isStudentRepeating
     *
     * @return AdminRegistration
     */
    public function setIsStudentRepeating($isStudentRepeating)
    {
        $this->isStudentRepeating = $isStudentRepeating;

        return $this;
    }

    /**
     * Get isStudentRepeating
     *
     * @return integer
     */
    public function getIsStudentRepeating()
    {
        return $this->isStudentRepeating;
    }    
    
    /**
     * Set academicYear
     *
     * @param AcademicYear $academicYear
     *
     * @return AdminRegistration
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear
     *
     * @return AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set student
     *
     * @param Student $student
     *
     * @return AdminRegistration
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
