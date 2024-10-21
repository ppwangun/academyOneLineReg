<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherPaymentBill
 *
 * @ORM\Table(name="teacher_payment_bill", indexes={@ORM\Index(name="fk_teacher_payment_bill_contract1_idx", columns={"contract_id"}), @ORM\Index(name="fk_teacher_payment_teacher1_idx", columns={"teacher_id"})})
 * @ORM\Entity
 */
class TeacherPaymentBill
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
     * @ORM\Column(name="ref_number", type="string", length=45, nullable=true)
     */
    private $refNumber;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var float|null
     *
     * @ORM\Column(name="payment_amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentAmount;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="payment_status", type="boolean", nullable=true)
     */
    private $paymentStatus;

    /**
     * @var float|null
     *
     * @ORM\Column(name="total_time", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalTime;

    /**
     * @var float|null
     *
     * @ORM\Column(name="overtime", type="float", precision=10, scale=0, nullable=true)
     */
    private $overtime;

    /**
     * @var float|null
     *
     * @ORM\Column(name="vacation_deduction", type="float", precision=10, scale=0, nullable=true)
     */
    private $vacationDeduction;

    /**
     * @var float|null
     *
     * @ORM\Column(name="total_time_previously_billed", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalTimePreviouslyBilled;

    /**
     * @var float|null
     *
     * @ORM\Column(name="total_time_currently_billed", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalTimeCurrentlyBilled;

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
     * @var \Contract
     *
     * @ORM\ManyToOne(targetEntity="Contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     * })
     */
    private $contract;



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
     * @param string|null $refNumber
     *
     * @return TeacherPaymentBill
     */
    public function setRefNumber($refNumber = null)
    {
        $this->refNumber = $refNumber;

        return $this;
    }

    /**
     * Get refNumber.
     *
     * @return string|null
     */
    public function getRefNumber()
    {
        return $this->refNumber;
    }

    /**
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return TeacherPaymentBill
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
     * Set paymentAmount.
     *
     * @param float|null $paymentAmount
     *
     * @return TeacherPaymentBill
     */
    public function setPaymentAmount($paymentAmount = null)
    {
        $this->paymentAmount = $paymentAmount;

        return $this;
    }

    /**
     * Get paymentAmount.
     *
     * @return float|null
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * Set paymentStatus.
     *
     * @param bool|null $paymentStatus
     *
     * @return TeacherPaymentBill
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
     * Set totalTime.
     *
     * @param float|null $totalTime
     *
     * @return TeacherPaymentBill
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
     * Set overtime.
     *
     * @param float|null $overtime
     *
     * @return TeacherPaymentBill
     */
    public function setOvertime($overtime = null)
    {
        $this->overtime = $overtime;

        return $this;
    }

    /**
     * Get overtime.
     *
     * @return float|null
     */
    public function getOvertime()
    {
        return $this->overtime;
    }

    /**
     * Set vacationDeduction.
     *
     * @param float|null $vacationDeduction
     *
     * @return TeacherPaymentBill
     */
    public function setVacationDeduction($vacationDeduction = null)
    {
        $this->vacationDeduction = $vacationDeduction;

        return $this;
    }

    /**
     * Get vacationDeduction.
     *
     * @return float|null
     */
    public function getVacationDeduction()
    {
        return $this->vacationDeduction;
    }

    /**
     * Set totalTimePreviouslyBilled.
     *
     * @param float|null $totalTimePreviouslyBilled
     *
     * @return TeacherPaymentBill
     */
    public function setTotalTimePreviouslyBilled($totalTimePreviouslyBilled = null)
    {
        $this->totalTimePreviouslyBilled = $totalTimePreviouslyBilled;

        return $this;
    }

    /**
     * Get totalTimePreviouslyBilled.
     *
     * @return float|null
     */
    public function getTotalTimePreviouslyBilled()
    {
        return $this->totalTimePreviouslyBilled;
    }

    /**
     * Set totalTimeCurrentlyBilled.
     *
     * @param float|null $totalTimeCurrentlyBilled
     *
     * @return TeacherPaymentBill
     */
    public function setTotalTimeCurrentlyBilled($totalTimeCurrentlyBilled = null)
    {
        $this->totalTimeCurrentlyBilled = $totalTimeCurrentlyBilled;

        return $this;
    }

    /**
     * Get totalTimeCurrentlyBilled.
     *
     * @return float|null
     */
    public function getTotalTimeCurrentlyBilled()
    {
        return $this->totalTimeCurrentlyBilled;
    }

    /**
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return TeacherPaymentBill
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

    /**
     * Set contract.
     *
     * @param \Contract|null $contract
     *
     * @return TeacherPaymentBill
     */
    public function setContract(\Contract $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract.
     *
     * @return \Contract|null
     */
    public function getContract()
    {
        return $this->contract;
    }
}
