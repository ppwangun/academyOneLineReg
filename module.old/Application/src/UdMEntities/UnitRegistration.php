<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * UnitRegistration
 *
 * @ORM\Table(name="unit_registration", indexes={@ORM\Index(name="fk_unit_registration_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_unit_registration_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_unit_registration_subject1_idx", columns={"subject_id"}), @ORM\Index(name="fk_unit_registration_teaching_unit1_idx", columns={"teaching_unit_id"})})
 * @ORM\Entity
 */
class UnitRegistration
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
     * @var int
     *
     * @ORM\Column(name="student_id", type="integer", nullable=false)
     */
    private $studentId;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_CC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_CCTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCctp;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_EXAMTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExamtp;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_EXAM", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExam;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_before_RATTRAPAGE", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteBeforeRattrapage;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_STAGEC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteStagec;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_EXAMC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExamc;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_STAGEE", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteStagee;

    /**
     * @var float|null
     *
     * @ORM\Column(name="note_FINAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteFinal;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;

    /**
     * @var float|null
     *
     * @ORM\Column(name="points", type="float", precision=10, scale=0, nullable=true)
     */
    private $points;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_from_deliberation", type="boolean", nullable=true)
     */
    private $isFromDeliberation = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_from_ratrappage", type="boolean", nullable=true)
     */
    private $isFromRatrappage = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="mention", type="integer", nullable=true)
     */
    private $mention;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_repeated", type="boolean", nullable=true)
     */
    private $isRepeated = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="year_first_inscription", type="integer", nullable=true)
     */
    private $yearFirstInscription;

    /**
     * @var int|null
     *
     * @ORM\Column(name="equivalent_subject", type="integer", nullable=true)
     */
    private $equivalentSubject;

    /**
     * @var int|null
     *
     * @ORM\Column(name="admission_decision", type="integer", nullable=true)
     */
    private $admissionDecision = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="calculationStatus", type="integer", nullable=true)
     */
    private $calculationstatus = '0';

    /**
     * @var \TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;

    /**
     * @var \Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

    /**
     * @var \Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     * })
     */
    private $subject;



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
     * Set studentId.
     *
     * @param int $studentId
     *
     * @return UnitRegistration
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId.
     *
     * @return int
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Set noteCc.
     *
     * @param float|null $noteCc
     *
     * @return UnitRegistration
     */
    public function setNoteCc($noteCc = null)
    {
        $this->noteCc = $noteCc;

        return $this;
    }

    /**
     * Get noteCc.
     *
     * @return float|null
     */
    public function getNoteCc()
    {
        return $this->noteCc;
    }

    /**
     * Set noteCctp.
     *
     * @param float|null $noteCctp
     *
     * @return UnitRegistration
     */
    public function setNoteCctp($noteCctp = null)
    {
        $this->noteCctp = $noteCctp;

        return $this;
    }

    /**
     * Get noteCctp.
     *
     * @return float|null
     */
    public function getNoteCctp()
    {
        return $this->noteCctp;
    }

    /**
     * Set noteExamtp.
     *
     * @param float|null $noteExamtp
     *
     * @return UnitRegistration
     */
    public function setNoteExamtp($noteExamtp = null)
    {
        $this->noteExamtp = $noteExamtp;

        return $this;
    }

    /**
     * Get noteExamtp.
     *
     * @return float|null
     */
    public function getNoteExamtp()
    {
        return $this->noteExamtp;
    }

    /**
     * Set noteExam.
     *
     * @param float|null $noteExam
     *
     * @return UnitRegistration
     */
    public function setNoteExam($noteExam = null)
    {
        $this->noteExam = $noteExam;

        return $this;
    }

    /**
     * Get noteExam.
     *
     * @return float|null
     */
    public function getNoteExam()
    {
        return $this->noteExam;
    }

    /**
     * Set noteBeforeRattrapage.
     *
     * @param float|null $noteBeforeRattrapage
     *
     * @return UnitRegistration
     */
    public function setNoteBeforeRattrapage($noteBeforeRattrapage = null)
    {
        $this->noteBeforeRattrapage = $noteBeforeRattrapage;

        return $this;
    }

    /**
     * Get noteBeforeRattrapage.
     *
     * @return float|null
     */
    public function getNoteBeforeRattrapage()
    {
        return $this->noteBeforeRattrapage;
    }

    /**
     * Set noteStagec.
     *
     * @param float|null $noteStagec
     *
     * @return UnitRegistration
     */
    public function setNoteStagec($noteStagec = null)
    {
        $this->noteStagec = $noteStagec;

        return $this;
    }

    /**
     * Get noteStagec.
     *
     * @return float|null
     */
    public function getNoteStagec()
    {
        return $this->noteStagec;
    }

    /**
     * Set noteExamc.
     *
     * @param float|null $noteExamc
     *
     * @return UnitRegistration
     */
    public function setNoteExamc($noteExamc = null)
    {
        $this->noteExamc = $noteExamc;

        return $this;
    }

    /**
     * Get noteExamc.
     *
     * @return float|null
     */
    public function getNoteExamc()
    {
        return $this->noteExamc;
    }

    /**
     * Set noteStagee.
     *
     * @param float|null $noteStagee
     *
     * @return UnitRegistration
     */
    public function setNoteStagee($noteStagee = null)
    {
        $this->noteStagee = $noteStagee;

        return $this;
    }

    /**
     * Get noteStagee.
     *
     * @return float|null
     */
    public function getNoteStagee()
    {
        return $this->noteStagee;
    }

    /**
     * Set noteFinal.
     *
     * @param float|null $noteFinal
     *
     * @return UnitRegistration
     */
    public function setNoteFinal($noteFinal = null)
    {
        $this->noteFinal = $noteFinal;

        return $this;
    }

    /**
     * Get noteFinal.
     *
     * @return float|null
     */
    public function getNoteFinal()
    {
        return $this->noteFinal;
    }

    /**
     * Set grade.
     *
     * @param string|null $grade
     *
     * @return UnitRegistration
     */
    public function setGrade($grade = null)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade.
     *
     * @return string|null
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set points.
     *
     * @param float|null $points
     *
     * @return UnitRegistration
     */
    public function setPoints($points = null)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return float|null
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set isFromDeliberation.
     *
     * @param bool|null $isFromDeliberation
     *
     * @return UnitRegistration
     */
    public function setIsFromDeliberation($isFromDeliberation = null)
    {
        $this->isFromDeliberation = $isFromDeliberation;

        return $this;
    }

    /**
     * Get isFromDeliberation.
     *
     * @return bool|null
     */
    public function getIsFromDeliberation()
    {
        return $this->isFromDeliberation;
    }

    /**
     * Set isFromRatrappage.
     *
     * @param bool|null $isFromRatrappage
     *
     * @return UnitRegistration
     */
    public function setIsFromRatrappage($isFromRatrappage = null)
    {
        $this->isFromRatrappage = $isFromRatrappage;

        return $this;
    }

    /**
     * Get isFromRatrappage.
     *
     * @return bool|null
     */
    public function getIsFromRatrappage()
    {
        return $this->isFromRatrappage;
    }

    /**
     * Set mention.
     *
     * @param int|null $mention
     *
     * @return UnitRegistration
     */
    public function setMention($mention = null)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention.
     *
     * @return int|null
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * Set isRepeated.
     *
     * @param bool|null $isRepeated
     *
     * @return UnitRegistration
     */
    public function setIsRepeated($isRepeated = null)
    {
        $this->isRepeated = $isRepeated;

        return $this;
    }

    /**
     * Get isRepeated.
     *
     * @return bool|null
     */
    public function getIsRepeated()
    {
        return $this->isRepeated;
    }

    /**
     * Set yearFirstInscription.
     *
     * @param int|null $yearFirstInscription
     *
     * @return UnitRegistration
     */
    public function setYearFirstInscription($yearFirstInscription = null)
    {
        $this->yearFirstInscription = $yearFirstInscription;

        return $this;
    }

    /**
     * Get yearFirstInscription.
     *
     * @return int|null
     */
    public function getYearFirstInscription()
    {
        return $this->yearFirstInscription;
    }

    /**
     * Set equivalentSubject.
     *
     * @param int|null $equivalentSubject
     *
     * @return UnitRegistration
     */
    public function setEquivalentSubject($equivalentSubject = null)
    {
        $this->equivalentSubject = $equivalentSubject;

        return $this;
    }

    /**
     * Get equivalentSubject.
     *
     * @return int|null
     */
    public function getEquivalentSubject()
    {
        return $this->equivalentSubject;
    }

    /**
     * Set admissionDecision.
     *
     * @param int|null $admissionDecision
     *
     * @return UnitRegistration
     */
    public function setAdmissionDecision($admissionDecision = null)
    {
        $this->admissionDecision = $admissionDecision;

        return $this;
    }

    /**
     * Get admissionDecision.
     *
     * @return int|null
     */
    public function getAdmissionDecision()
    {
        return $this->admissionDecision;
    }

    /**
     * Set calculationstatus.
     *
     * @param int|null $calculationstatus
     *
     * @return UnitRegistration
     */
    public function setCalculationstatus($calculationstatus = null)
    {
        $this->calculationstatus = $calculationstatus;

        return $this;
    }

    /**
     * Get calculationstatus.
     *
     * @return int|null
     */
    public function getCalculationstatus()
    {
        return $this->calculationstatus;
    }

    /**
     * Set teachingUnit.
     *
     * @param \TeachingUnit|null $teachingUnit
     *
     * @return UnitRegistration
     */
    public function setTeachingUnit(\TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit.
     *
     * @return \TeachingUnit|null
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }

    /**
     * Set semester.
     *
     * @param \Semester|null $semester
     *
     * @return UnitRegistration
     */
    public function setSemester(\Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester.
     *
     * @return \Semester|null
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set subject.
     *
     * @param \Subject|null $subject
     *
     * @return UnitRegistration
     */
    public function setSubject(\Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return \Subject|null
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
