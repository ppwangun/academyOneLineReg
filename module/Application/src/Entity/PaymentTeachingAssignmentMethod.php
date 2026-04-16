<?php

namespace Application\Entity;

use Application\Entity\TeacherPaymentRate;


use Doctrine\ORM\Mapping as ORM;

/**
 * PaymentTeachingAssignmentMethod
 *
 * @ORM\Table(name="payment_teaching_assignment_method", indexes={@ORM\Index(name="fk_payment_teaching_assignment_teacher_payment_rate1_idx", columns={"teacher_payment_rate_id"})})
 * @ORM\Entity
 */
class PaymentTeachingAssignmentMethod
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
     * @ORM\Column(name="payment_method", type="string", length=45, nullable=true, options={"default"="ACADEMIC_RANK"})
     */
    private $paymentMethod = 'ACADEMIC_RANK';

    /**
     * @var string|null
     *
     * @ORM\Column(name="amount", type="string", length=45, nullable=true)
     */
    private $amount;
    
    /**
     * @var float|null
     *
     * @ORM\Column(name="amount_practical", type="float", precision=10, scale=0, nullable=true)
     */
    private $amountPractical;

    /**
     * @var float|null
     *
     * @ORM\Column(name="amount_theoritical", type="float", precision=10, scale=0, nullable=true)
     */
    private $amountTheoritical;   

    /**
     * @var TeacherPaymentRate
     *
     * @ORM\ManyToOne(targetEntity="TeacherPaymentRate")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_payment_rate_id", referencedColumnName="id")
     * })
     */
    private $teacherPaymentRate;



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
     * Set paymentMethod.
     *
     * @param string|null $paymentMethod
     *
     * @return PaymentTeachingAssignmentMethod
     */
    public function setPaymentMethod($paymentMethod = null)
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod.
     *
     * @return string|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Set amount.
     *
     * @param string|null $amount
     *
     * @return PaymentTeachingAssignmentMethod
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->amount;
    }
    
    /**
     * Set amountPractical.
     *
     * @param float|null $amountPractical
     *
     * @return PaymentTeachingAssignmentMethod
     */
    public function setAmountPractical($amountPractical = null)
    {
        $this->amountPractical = $amountPractical;

        return $this;
    }

    /**
     * Get amountPractical.
     *
     * @return float|null
     */
    public function getAmountPractical()
    {
        return $this->amountPractical;
    }

    /**
     * Set amountTheoritical.
     *
     * @param float|null $amountTheoritical
     *
     * @return PaymentTeachingAssignmentMethod
     */
    public function setAmountTheoritical($amountTheoritical = null)
    {
        $this->amountTheoritical = $amountTheoritical;

        return $this;
    }

    /**
     * Get amountTheoritical.
     *
     * @return float|null
     */
    public function getAmountTheoritical()
    {
        return $this->amountTheoritical;
    }    

    /**
     * Set teacherPaymentRate.
     *
     * @param TeacherPaymentRate|null $teacherPaymentRate
     *
     * @return PaymentTeachingAssignmentMethod
     */
    public function setTeacherPaymentRate(TeacherPaymentRate $teacherPaymentRate = null)
    {
        $this->teacherPaymentRate = $teacherPaymentRate;

        return $this;
    }

    /**
     * Get teacherPaymentRate.
     *
     * @return TeacherPaymentRate|null
     */
    public function getTeacherPaymentRate()
    {
        return $this->teacherPaymentRate;
    }
}
