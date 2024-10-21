<?php
namespace Application\Entity;

use Application\Entity\TeacherPaymentBill;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Contract;
use Application\Entity\Teacher;


use Doctrine\ORM\Mapping as ORM;

/**
 * ContractFollowUp
 *
 * @ORM\Table(name="contract_follow_up", indexes={@ORM\Index(name="fk_contract_follow_up_teacher_payment_bill1_idx", columns={"teacher_payment_bill_id"}), @ORM\Index(name="fk_contract_follow_up_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"}), @ORM\Index(name="fk_contract_follow_up_contract1_idx", columns={"contract_id"})})
 * @ORM\Entity
 */
class ContractFollowUp
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start_time", type="time", nullable=true)
     */
    private $startTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end_time", type="time", nullable=true)
     */
    private $endTime;

    /**
     * @var float|null
     *
     * @ORM\Column(name="total_time", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalTime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=500, nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lecture_type", type="string", length=45, nullable=true)
     */
    private $lectureType;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="payment_status", type="boolean", nullable=true)
     */
    private $paymentStatus = '0';

    /**
     * @var ClassOfStudyHasSemester
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;

    /**
     * @var Contract
     *
     * @ORM\ManyToOne(targetEntity="Contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     * })
     */
    private $contract;
        


    /**
     * @var TeacherPaymentBill
     *
     * @ORM\ManyToOne(targetEntity="TeacherPaymentBill")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_payment_bill_id", referencedColumnName="id")
     * })
     */
    private $teacherPaymentBill;



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
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return ContractFollowUp
     */
    public function setDate($date = null)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set startTime.
     *
     * @param \DateTime|null $startTime
     *
     * @return ContractFollowUp
     */
    public function setStartTime($startTime = null)
    {
        $this->startTime = $startTime;
    
        return $this;
    }

    /**
     * Get startTime.
     *
     * @return \DateTime|null
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime.
     *
     * @param \DateTime|null $endTime
     *
     * @return ContractFollowUp
     */
    public function setEndTime($endTime = null)
    {
        $this->endTime = $endTime;
    
        return $this;
    }

    /**
     * Get endTime.
     *
     * @return \DateTime|null
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set totalTime.
     *
     * @param float|null $totalTime
     *
     * @return ContractFollowUp
     */
    public function setTotalTime($totalTime = null)
    {
        $this->totalTime = $totalTime;
    
        return $this;
    }

    /**
     * Get totalTime.
     *
     * @return float|null
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return ContractFollowUp
     */
    public function setDescription($description = null)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lectureType.
     *
     * @param string|null $lectureType
     *
     * @return ContractFollowUp
     */
    public function setLectureType($lectureType = null)
    {
        $this->lectureType = $lectureType;
    
        return $this;
    }

    /**
     * Get lectureType.
     *
     * @return string|null
     */
    public function getLectureType()
    {
        return $this->lectureType;
    }

    /**
     * Set paymentStatus.
     *
     * @param bool|null $paymentStatus
     *
     * @return ContractFollowUp
     */
    public function setPaymentStatus($paymentStatus = null)
    {
        $this->paymentStatus = $paymentStatus;
    
        return $this;
    }

    /**
     * Get paymentStatus.
     *
     * @return bool|null
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set classOfStudyHasSemester.
     *
     * @param ClassOfStudyHasSemester|null $classOfStudyHasSemester
     *
     * @return ContractFollowUp
     */
    public function setClassOfStudyHasSemester(ClassOfStudyHasSemester $classOfStudyHasSemester = null)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;
    
        return $this;
    }

    /**
     * Get classOfStudyHasSemester.
     *
     * @return ClassOfStudyHasSemester|null
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }

    /**
     * Set contract.
     *
     * @param Contract|null $contract
     *
     * @return ContractFollowUp
     */
    public function setContract(Contract $contract = null)
    {
        $this->contract = $contract;
    
        return $this;
    }

    /**
     * Get contract.
     *
     * @return Contract|null
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set teacherPaymentBill.
     *
     * @param TeacherPaymentBill|null $teacherPaymentBill
     *
     * @return ContractFollowUp
     */
    public function setTeacherPaymentBill(TeacherPaymentBill $teacherPaymentBill = null)
    {
        $this->teacherPaymentBill = $teacherPaymentBill;
    
        return $this;
    }

    /**
     * Get teacherPaymentBill.
     *
     * @return TeacherPaymentBill|null
     */
    public function getTeacherPaymentBill()
    {
        return $this->teacherPaymentBill;
    }
    
   
}
