<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

use Application\Entity\AcademicYear;
use Application\Entity\Teacher;

/**
 * TeacherPaymentBillSumary
 *
 * @ORM\Table(name="teacher_payment_bill_sumary", indexes={@ORM\Index(name="fk_teacher_payment_bill_sumary_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_teacher_payment_bill_sumary_teacher1_idx", columns={"teacher_id"})})
 * @ORM\Entity
 */
class TeacherPaymentBillSumary
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
     * @ORM\Column(name="total_time", type="float", precision=10, scale=0, nullable=true)
     */
    private $totalTime;
    
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
     * @var AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

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
     * @return TeacherPaymentBillSumary
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
     * @return TeacherPaymentBillSumary
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
     * Set totalTime.
     *
     * @param float|null $totalTime
     *
     * @return TeacherPaymentBillSumary
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
     * Set paymentAmount.
     *
     * @param float|null $paymentAmount
     *
     * @return TeacherPaymentBillSumary
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
     * @return TeacherPaymentBillSumary
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
     * Set academicYear.
     *
     * @param AcademicYear|null $academicYear
     *
     * @return TeacherPaymentBillSumary
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
     * Set teacher.
     *
     * @param Teacher|null $teacher
     *
     * @return TeacherPaymentBillSumary
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
}
