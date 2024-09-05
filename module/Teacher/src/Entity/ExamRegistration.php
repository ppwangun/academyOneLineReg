<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Exam;
use Application\Entity\Student;

/**
 * ExamRegistration
 *
 * @ORM\Table(name="exam_registration", indexes={@ORM\Index(name="fk_exam_registration_exam1_idx", columns={"exam_id"}), @ORM\Index(name="fk_exam_registration_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class ExamRegistration
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
     * @var string
     *
     * @ORM\Column(name="attendance", type="string", length=45, nullable=true)
     */
    private $attendance;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_anonymat", type="integer", nullable=true)
     */
    private $numAnonymat;

    /**
     * @var float
     *
     * @ORM\Column(name="registered_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $registeredMark = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="validated_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $validatedMark = '0';

    /**
     * @var float
     *
     * @ORM\Column(name="confirmed_mark", type="float", precision=10, scale=0, nullable=true)
     */
    private $confirmedMark = '0';
    
     /**
     * @var integer
     *
     * @ORM\Column(name="isMarkFromCatchUpExam", type="integer", nullable=true)
     */
    private $isMarkFromCatchUpExam = '0';

    /**
     * @var Exam
     *
     * @ORM\ManyToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     * })
     */
    private $exam;

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
     * Set attendance
     *
     * @param string $attendance
     *
     * @return ExamRegistration
     */
    public function setAttendance($attendance)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance
     *
     * @return string
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set numAnonymat
     *
     * @param integer $numAnonymat
     *
     * @return ExamRegistration
     */
    public function setNumAnonymat($numAnonymat)
    {
        $this->numAnonymat = $numAnonymat;

        return $this;
    }

    /**
     * Get numAnonymat
     *
     * @return integer
     */
    public function getNumAnonymat()
    {
        return $this->numAnonymat;
    }

    /**
     * Set registeredMark
     *
     * @param float $registeredMark
     *
     * @return ExamRegistration
     */
    public function setRegisteredMark($registeredMark)
    {
        $this->registeredMark = $registeredMark;

        return $this;
    }

    /**
     * Get registeredMark
     *
     * @return float
     */
    public function getRegisteredMark()
    {
        return $this->registeredMark;
    }

    /**
     * Set validatedMark
     *
     * @param float $validatedMark
     *
     * @return ExamRegistration
     */
    public function setValidatedMark($validatedMark)
    {
        $this->validatedMark = $validatedMark;

        return $this;
    }

    /**
     * Get validatedMark
     *
     * @return float
     */
    public function getValidatedMark()
    {
        return $this->validatedMark;
    }

    /**
     * Set confirmedMark
     *
     * @param float $confirmedMark
     *
     * @return ExamRegistration
     */
    public function setConfirmedMark($confirmedMark)
    {
        $this->confirmedMark = $confirmedMark;

        return $this;
    }

    /**
     * Get confirmedMark
     *
     * @return float
     */
    public function getConfirmedMark()
    {
        return $this->confirmedMark;
    }

    /**
     * Set exam
     *
     * @param Exam $exam
     *
     * @return ExamRegistration
     */
    public function setExam(Exam $exam = null)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam
     *
     * @return Exam
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set student
     *
     * @param Student $student
     *
     * @return ExamRegistration
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

    /**
     * Get isMarkFromCatchUpExam
     *
     * @return integer
     */
    public function getIsMarkFromCatchUpExam()
    {
        return $this->isMarkFromCatchUpExam;
    }

    /**
     * Set isMarkFromCatchUpExam
     *
     * @param integer $isMarkFromCatchUpExam
     *
     * @return Exam
     */
    public function setIsMarkFromCatchUpExam($isMarkFromCatchUpExam)
    {
        $this->isMarkFromCatchUpExam = $isMarkFromCatchUpExam;
        return $this;
    }  
}
