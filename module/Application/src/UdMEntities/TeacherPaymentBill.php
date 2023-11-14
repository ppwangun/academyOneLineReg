<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherPaymentBill
 *
 * @ORM\Table(name="teacher_payment_bill", indexes={@ORM\Index(name="fk_teacher_payment_teacher1_idx", columns={"teacher_id"})})
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
     * @var string
     *
     * @ORM\Column(name="ref_number", type="string", length=45, nullable=false)
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
     * @return TeacherPaymentBill
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
}
