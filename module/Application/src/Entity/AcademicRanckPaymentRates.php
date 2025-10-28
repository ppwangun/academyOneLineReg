<?php
namespace Application\Entity;

use Application\Entity\TeacherPaymentRate;
use Application\Entity\AcademicRanck;


use Doctrine\ORM\Mapping as ORM;

/**
 * AcademicRanckPaymentRates
 *
 * @ORM\Table(name="academic_ranck__payment_rates", indexes={@ORM\Index(name="fk_teacher_payment_rate_has_academic_ranck_teacher_payment__idx", columns={"teacher_payment_rate_id"}), @ORM\Index(name="fk_teacher_payment_rate_has_academic_ranck_academic_ranck1_idx", columns={"academic_ranck_id"})})
 * @ORM\Entity
 */
class AcademicRanckPaymentRates
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
     * @var TeacherPaymentRate
     *
     * @ORM\ManyToOne(targetEntity="TeacherPaymentRate")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_payment_rate_id", referencedColumnName="id")
     * })
     */
    private $teacherPaymentRate;

    /**
     * @var AcademicRanck
     *
     * @ORM\ManyToOne(targetEntity="AcademicRanck")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_ranck_id", referencedColumnName="id")
     * })
     */
    private $academicRanck;



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
     * @return AcademicRanckPaymentRates
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
     * Set teacherPaymentRate.
     *
     * @param TeacherPaymentRate|null $teacherPaymentRate
     *
     * @return AcademicRanckPaymentRates
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

    /**
     * Set academicRanck.
     *
     * @param AcademicRanck|null $academicRanck
     *
     * @return AcademicRanckPaymentRates
     */
    public function setAcademicRanck(AcademicRanck $academicRanck = null)
    {
        $this->academicRanck = $academicRanck;

        return $this;
    }

    /**
     * Get academicRanck.
     *
     * @return AcademicRanck|null
     */
    public function getAcademicRanck()
    {
        return $this->academicRanck;
    }
}
