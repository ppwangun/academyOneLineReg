<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Exam
 *
 * @ORM\Table(name="exam", indexes={@ORM\Index(name="fk_exam_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"}), @ORM\Index(name="fk_exam_exam_type1_idx", columns={"exam_type_code"}), @ORM\Index(name="fk_exam_exam_session1_idx", columns={"exam_session_id"})})
 * @ORM\Entity
 */
class Exam
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="exam_type_code", type="string", length=45, nullable=true)
     */
    private $examTypeCode;

    /**
     * @var int
     *
     * @ORM\Column(name="is_mark_registered", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $isMarkRegistered = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="is_mark_validated", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $isMarkValidated = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="is_mark_confirmed", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $isMarkConfirmed = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_validated", type="datetime", nullable=true)
     */
    private $dateValidated;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_registered", type="datetime", nullable=true)
     */
    private $dateRegistered;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_confirmed", type="datetime", nullable=true)
     */
    private $dateConfirmed;

    /**
     * @var int
     *
     * @ORM\Column(name="is_attendance_saved", type="integer", nullable=false)
     */
    private $isAttendanceSaved = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="is_anonymat_saved", type="integer", nullable=false)
     */
    private $isAnonymatSaved = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_catch_up_exam_performed", type="integer", nullable=true)
     */
    private $isCatchUpExamPerformed = '0';

    /**
     * @var \ExamSession
     *
     * @ORM\ManyToOne(targetEntity="ExamSession")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_session_id", referencedColumnName="id")
     * })
     */
    private $examSession;

    /**
     * @var \ClassOfStudyHasSemester
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;



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
     * Set code.
     *
     * @param string|null $code
     *
     * @return Exam
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
     * Set type.
     *
     * @param string|null $type
     *
     * @return Exam
     */
    public function setType($type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return Exam
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
     * Set examTypeCode.
     *
     * @param string|null $examTypeCode
     *
     * @return Exam
     */
    public function setExamTypeCode($examTypeCode = null)
    {
        $this->examTypeCode = $examTypeCode;

        return $this;
    }

    /**
     * Get examTypeCode.
     *
     * @return string|null
     */
    public function getExamTypeCode()
    {
        return $this->examTypeCode;
    }

    /**
     * Set isMarkRegistered.
     *
     * @param int $isMarkRegistered
     *
     * @return Exam
     */
    public function setIsMarkRegistered($isMarkRegistered)
    {
        $this->isMarkRegistered = $isMarkRegistered;

        return $this;
    }

    /**
     * Get isMarkRegistered.
     *
     * @return int
     */
    public function getIsMarkRegistered()
    {
        return $this->isMarkRegistered;
    }

    /**
     * Set isMarkValidated.
     *
     * @param int $isMarkValidated
     *
     * @return Exam
     */
    public function setIsMarkValidated($isMarkValidated)
    {
        $this->isMarkValidated = $isMarkValidated;

        return $this;
    }

    /**
     * Get isMarkValidated.
     *
     * @return int
     */
    public function getIsMarkValidated()
    {
        return $this->isMarkValidated;
    }

    /**
     * Set isMarkConfirmed.
     *
     * @param int $isMarkConfirmed
     *
     * @return Exam
     */
    public function setIsMarkConfirmed($isMarkConfirmed)
    {
        $this->isMarkConfirmed = $isMarkConfirmed;

        return $this;
    }

    /**
     * Get isMarkConfirmed.
     *
     * @return int
     */
    public function getIsMarkConfirmed()
    {
        return $this->isMarkConfirmed;
    }

    /**
     * Set dateValidated.
     *
     * @param \DateTime|null $dateValidated
     *
     * @return Exam
     */
    public function setDateValidated($dateValidated = null)
    {
        $this->dateValidated = $dateValidated;

        return $this;
    }

    /**
     * Get dateValidated.
     *
     * @return \DateTime|null
     */
    public function getDateValidated()
    {
        return $this->dateValidated;
    }

    /**
     * Set dateRegistered.
     *
     * @param \DateTime|null $dateRegistered
     *
     * @return Exam
     */
    public function setDateRegistered($dateRegistered = null)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered.
     *
     * @return \DateTime|null
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set dateConfirmed.
     *
     * @param \DateTime|null $dateConfirmed
     *
     * @return Exam
     */
    public function setDateConfirmed($dateConfirmed = null)
    {
        $this->dateConfirmed = $dateConfirmed;

        return $this;
    }

    /**
     * Get dateConfirmed.
     *
     * @return \DateTime|null
     */
    public function getDateConfirmed()
    {
        return $this->dateConfirmed;
    }

    /**
     * Set isAttendanceSaved.
     *
     * @param int $isAttendanceSaved
     *
     * @return Exam
     */
    public function setIsAttendanceSaved($isAttendanceSaved)
    {
        $this->isAttendanceSaved = $isAttendanceSaved;

        return $this;
    }

    /**
     * Get isAttendanceSaved.
     *
     * @return int
     */
    public function getIsAttendanceSaved()
    {
        return $this->isAttendanceSaved;
    }

    /**
     * Set isAnonymatSaved.
     *
     * @param int $isAnonymatSaved
     *
     * @return Exam
     */
    public function setIsAnonymatSaved($isAnonymatSaved)
    {
        $this->isAnonymatSaved = $isAnonymatSaved;

        return $this;
    }

    /**
     * Get isAnonymatSaved.
     *
     * @return int
     */
    public function getIsAnonymatSaved()
    {
        return $this->isAnonymatSaved;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return Exam
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isCatchUpExamPerformed.
     *
     * @param int|null $isCatchUpExamPerformed
     *
     * @return Exam
     */
    public function setIsCatchUpExamPerformed($isCatchUpExamPerformed = null)
    {
        $this->isCatchUpExamPerformed = $isCatchUpExamPerformed;

        return $this;
    }

    /**
     * Get isCatchUpExamPerformed.
     *
     * @return int|null
     */
    public function getIsCatchUpExamPerformed()
    {
        return $this->isCatchUpExamPerformed;
    }

    /**
     * Set examSession.
     *
     * @param \ExamSession|null $examSession
     *
     * @return Exam
     */
    public function setExamSession(\ExamSession $examSession = null)
    {
        $this->examSession = $examSession;

        return $this;
    }

    /**
     * Get examSession.
     *
     * @return \ExamSession|null
     */
    public function getExamSession()
    {
        return $this->examSession;
    }

    /**
     * Set classOfStudyHasSemester.
     *
     * @param \ClassOfStudyHasSemester|null $classOfStudyHasSemester
     *
     * @return Exam
     */
    public function setClassOfStudyHasSemester(\ClassOfStudyHasSemester $classOfStudyHasSemester = null)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;

        return $this;
    }

    /**
     * Get classOfStudyHasSemester.
     *
     * @return \ClassOfStudyHasSemester|null
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }
}
