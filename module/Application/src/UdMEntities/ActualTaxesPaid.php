<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ActualTaxesPaid
 *
 * @ORM\Table(name="actual_taxes_paid", indexes={@ORM\Index(name="fk_actual_taxes_paid_teacher_associated_taxes1_idx", columns={"teacher_associated_taxes_id"}), @ORM\Index(name="fk_actual_taxes_paid_teacher_payment_bill_sumary1_idx", columns={"teacher_payment_bill_sumary_id"})})
 * @ORM\Entity
 */
class ActualTaxesPaid
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
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var float|null
     *
     * @ORM\Column(name="rate", type="float", precision=10, scale=0, nullable=true)
     */
    private $rate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="refundable", type="boolean", nullable=true)
     */
    private $refundable;

    /**
     * @var \TeacherAssociatedTaxes
     *
     * @ORM\ManyToOne(targetEntity="TeacherAssociatedTaxes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_associated_taxes_id", referencedColumnName="id")
     * })
     */
    private $teacherAssociatedTaxes;

    /**
     * @var \TeacherPaymentBillSumary
     *
     * @ORM\ManyToOne(targetEntity="TeacherPaymentBillSumary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_payment_bill_sumary_id", referencedColumnName="id")
     * })
     */
    private $teacherPaymentBillSumary;



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
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return ActualTaxesPaid
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
     * Set code.
     *
     * @param string|null $code
     *
     * @return ActualTaxesPaid
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set rate.
     *
     * @param float|null $rate
     *
     * @return ActualTaxesPaid
     */
    public function setRate($rate = null)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return float|null
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set refundable.
     *
     * @param bool|null $refundable
     *
     * @return ActualTaxesPaid
     */
    public function setRefundable($refundable = null)
    {
        $this->refundable = $refundable;

        return $this;
    }

    /**
     * Get refundable.
     *
     * @return bool|null
     */
    public function getRefundable()
    {
        return $this->refundable;
    }

    /**
     * Set teacherPaymentBillSumary.
     *
     * @param \TeacherPaymentBillSumary|null $teacherPaymentBillSumary
     *
     * @return ActualTaxesPaid
     */
    public function setTeacherPaymentBillSumary(\TeacherPaymentBillSumary $teacherPaymentBillSumary = null)
    {
        $this->teacherPaymentBillSumary = $teacherPaymentBillSumary;

        return $this;
    }

    /**
     * Get teacherPaymentBillSumary.
     *
     * @return \TeacherPaymentBillSumary|null
     */
    public function getTeacherPaymentBillSumary()
    {
        return $this->teacherPaymentBillSumary;
    }

    /**
     * Set teacherAssociatedTaxes.
     *
     * @param \TeacherAssociatedTaxes|null $teacherAssociatedTaxes
     *
     * @return ActualTaxesPaid
     */
    public function setTeacherAssociatedTaxes(\TeacherAssociatedTaxes $teacherAssociatedTaxes = null)
    {
        $this->teacherAssociatedTaxes = $teacherAssociatedTaxes;

        return $this;
    }

    /**
     * Get teacherAssociatedTaxes.
     *
     * @return \TeacherAssociatedTaxes|null
     */
    public function getTeacherAssociatedTaxes()
    {
        return $this->teacherAssociatedTaxes;
    }
}
