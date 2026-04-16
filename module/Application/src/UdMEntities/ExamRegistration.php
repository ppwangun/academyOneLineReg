<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ExamRegistration
 *
 * @ORM\Table(name="exam_registration", indexes={@ORM\Index(name="fk_exam_registration_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_exam_registration_exam1_idx", columns={"exam_id"})})
 * @ORM\Entity
 */
class ExamRegistration
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="attendance", type="string", length=45, nullable=true)
     */
    private $attendance;

    /**
     * @var int|null
     *
     * @ORM\Column(name="num_anonymat", type="integer", nullable=true)
     */
    private $numAnonymat;

    /**
     * @var float
     *
     * @ORM\Column(name="registered_mark", type="float", precision=10, scale=0, nullable=false, options={"default"="0000000000000000000000"})
     */
    private $registeredMark = 0000000000000000000000;

    /**
     * @var float
     *
     * @ORM\Column(name="validated_mark", type="float", precision=10, scale=0, nullable=false, options={"default"="0000000000000000000000"})
     */
    private $validatedMark = 0000000000000000000000;

    /**
     * @var float
     *
     * @ORM\Column(name="confirmed_mark", type="float", precision=10, scale=0, nullable=false, options={"default"="0000000000000000000000"})
     */
    private $confirmedMark = 0000000000000000000000;

    /**
     * @var int|null
     *
     * @ORM\Column(name="isMarkFromCatchUpExam", type="integer", nullable=true)
     */
    private $ismarkfromcatchupexam = '0';

    /**
     * @var \Exam
     *
     * @ORM\ManyToOne(targetEntity="Exam")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_id", referencedColumnName="id")
     * })
     */
    private $exam;

    /**
     * @var \Student
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



    /**
     * Set id.
     *
     * @param int $id
     *
     * @return ExamRegistration
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set attendance.
     *
     * @param string|null $attendance
     *
     * @return ExamRegistration
     */
    public function setAttendance($attendance = null)
    {
        $this->attendance = $attendance;

        return $this;
    }

    /**
     * Get attendance.
     *
     * @return string|null
     */
    public function getAttendance()
    {
        return $this->attendance;
    }

    /**
     * Set numAnonymat.
     *
     * @param int|null $numAnonymat
     *
     * @return ExamRegistration
     */
    public function setNumAnonymat($numAnonymat = null)
    {
        $this->numAnonymat = $numAnonymat;

        return $this;
    }

    /**
     * Get numAnonymat.
     *
     * @return int|null
     */
    public function getNumAnonymat()
    {
        return $this->numAnonymat;
    }

    /**
     * Set registeredMark.
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
     * Get registeredMark.
     *
     * @return float
     */
    public function getRegisteredMark()
    {
        return $this->registeredMark;
    }

    /**
     * Set validatedMark.
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
     * Get validatedMark.
     *
     * @return float
     */
    public function getValidatedMark()
    {
        return $this->validatedMark;
    }

    /**
     * Set confirmedMark.
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
     * Get confirmedMark.
     *
     * @return float
     */
    public function getConfirmedMark()
    {
        return $this->confirmedMark;
    }

    /**
     * Set ismarkfromcatchupexam.
     *
     * @param int|null $ismarkfromcatchupexam
     *
     * @return ExamRegistration
     */
    public function setIsmarkfromcatchupexam($ismarkfromcatchupexam = null)
    {
        $this->ismarkfromcatchupexam = $ismarkfromcatchupexam;

        return $this;
    }

    /**
     * Get ismarkfromcatchupexam.
     *
     * @return int|null
     */
    public function getIsmarkfromcatchupexam()
    {
        return $this->ismarkfromcatchupexam;
    }

    /**
     * Set exam.
     *
     * @param \Exam|null $exam
     *
     * @return ExamRegistration
     */
    public function setExam(\Exam $exam = null)
    {
        $this->exam = $exam;

        return $this;
    }

    /**
     * Get exam.
     *
     * @return \Exam|null
     */
    public function getExam()
    {
        return $this->exam;
    }

    /**
     * Set student.
     *
     * @param \Student $student
     *
     * @return ExamRegistration
     */
    public function setStudent(\Student $student)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student.
     *
     * @return \Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
