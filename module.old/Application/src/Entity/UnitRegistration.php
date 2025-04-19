<?php
namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;

/**
 * UnitRegistration
 *
 * @ORM\Table(name="unit_registration", indexes={@ORM\Index(name="fk_unit_registration_teaching_unit1_idx", columns={"teaching_unit_id"}), @ORM\Index(name="fk_unit_registration_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_unit_registration_semester1_idx", columns={"semester_id"})})
 * @ORM\Entity
 */
class UnitRegistration
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
     * @var float
     *
     * @ORM\Column(name="note_CC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCc;

    /**
     * @var float
     *
     * @ORM\Column(name="note_CCTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCctp;

    /**
     * @var float
     *
     * @ORM\Column(name="note_EXAMTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExamtp;
    
    /**
     * @var float
     *
     * @ORM\Column(name="note_EXAMC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExamc;    

    /**
     * @var float
     *
     * @ORM\Column(name="note_EXAM", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExam;

    /**
     * @var float
     *
     * @ORM\Column(name="note_before_RATTRAPAGE", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteBeforeRattrapage;    
    
    /**
     * @var float
     *
     * @ORM\Column(name="note_STAGEC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteStagec;

    /**
     * @var float
     *
     * @ORM\Column(name="note_STAGEE", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteStagee;

    /**
     * @var float
     *
     * @ORM\Column(name="note_FINAL", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;

    /**
     * @var float
     *
     * @ORM\Column(name="points", type="float", precision=10, scale=0, nullable=true)
     */
    private $points;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mention", type="string", length=45, nullable=true)
     */
    private $mention;    

    /**
     * @var integer
     *
     * @ORM\Column(name="is_repeated", type="integer", nullable=true)
     */
    private $isRepeated = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="year_first_inscription", type="integer", nullable=true)
     */
    private $yearFirstInscription;

    /**
     * @var integer
     *
     * @ORM\Column(name="equivalent_subject", type="integer", nullable=true)
     */
    private $equivalentSubject;

    /**
     * @var integer
     *
     * @ORM\Column(name="admission_decision", type="integer", nullable=true)
     */
    private $admissionDecision = '0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_from_ratrappage", type="integer",  nullable=true)
     */
    private $isFromRatrappage = '0';    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_from_deliberation", type="integer",  nullable=true)
     */
    private $isFromDeliberation = '0';    

     /** @var integer
     *
     * @ORM\Column(name="calculationStatus", type="integer",  nullable=true)
     */
    private $calculationStatus = '0';
    
    /**
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

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
     * @var TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;

    /**
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     * })
     */
    private $subject;

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
     * Set noteCc
     *
     * @param float $noteCc
     *
     * @return UnitRegistration
     */
    public function setNoteCc($noteCc)
    {
        $this->noteCc = $noteCc;

        return $this;
    }

    /**
     * Get noteCc
     *
     * @return float
     */
    public function getNoteCc()
    {
        return $this->noteCc;
    }

    /**
     * Set noteCctp
     *
     * @param float $noteCctp
     *
     * @return UnitRegistration
     */
    public function setNoteCctp($noteCctp)
    {
        $this->noteCctp = $noteCctp;

        return $this;
    }

    /**
     * Get noteCctp
     *
     * @return float
     */
    public function getNoteCctp()
    {
        return $this->noteCctp;
    }

    /**
     * Set noteExamtp
     *
     * @param float $noteExamtp
     *
     * @return UnitRegistration
     */
    public function setNoteExamtp($noteExamtp)
    {
        $this->noteExamtp = $noteExamtp;

        return $this;
    }

    /**
     * Get noteExamtp
     *
     * @return float
     */
    public function getNoteExamtp()
    {
        return $this->noteExamtp;
    }

    /**
     * Set noteExam
     *
     * @param float $noteExam
     *
     * @return UnitRegistration
     */
    public function setNoteExam($noteExam)
    {
        $this->noteExam = $noteExam;

        return $this;
    }

    /**
     * Get noteExam
     *
     * @return float
     */
    public function getNoteExam()
    {
        return $this->noteExam;
    }

    /**
     * Set noteExam
     *
     * @param float $noteExamc
     *
     * @return UnitRegistration
     */
    public function setNoteExamc($noteExamc)
    {
        $this->noteExamc = $noteExamc;

        return $this;
    }

    /**
     * Get noteExamc
     *
     * @return float
     */
    public function getNoteExamc()
    {
        return $this->noteExamc;
    }
    
    
/**
     * Set noteBeforeRattrapage
     *
     * @param float $noteBeforeRattrapage
     *
     * @return UnitRegistration
     */
    public function setNoteBeforeRattrapage($noteBeforeRattrapage)
    {
        $this->noteBeforeRattrapage = $noteBeforeRattrapage;

        return $this;
    }

    /**
     * Get noteBeforeRattrapage
     *
     * @return float
     */
    public function getNoteBeforeRattrapage()
    {
        return $this->noteBeforeRattrapage;
    }    
    
    /**
     * Set noteStagec
     *
     * @param float $noteStagec
     *
     * @return UnitRegistration
     */
    public function setNoteStagec($noteStagec)
    {
        $this->noteStagec = $noteStagec;

        return $this;
    }

    /**
     * Get noteStagec
     *
     * @return float
     */
    public function getNoteStagec()
    {
        return $this->noteStagec;
    }

    /**
     * Set noteStagee
     *
     * @param float $noteStagee
     *
     * @return UnitRegistration
     */
    public function setNoteStagee($noteStagee)
    {
        $this->noteStagee = $noteStagee;

        return $this;
    }

    /**
     * Get noteStagee
     *
     * @return float
     */
    public function getNoteStagee()
    {
        return $this->noteStagee;
    }

    /**
     * Set noteFinal
     *
     * @param float $noteFinal
     *
     * @return UnitRegistration
     */
    public function setNoteFinal($noteFinal)
    {
        $this->noteFinal = $noteFinal;

        return $this;
    }

    /**
     * Get noteFinal
     *
     * @return float
     */
    public function getNoteFinal()
    {
        return $this->noteFinal;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return UnitRegistration
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set points
     *
     * @param float $points
     *
     * @return UnitRegistration
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points
     *
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set mention
     *
     * @param string $grade
     *
     * @return UnitRegistration
     */
    public function setMention($mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention
     *
     * @return string
     */
    public function getMention()
    {
        return $this->mention;
    }
    
    /**
     * Set isRepeated
     *
     * @param integer $isRepeated
     *
     * @return UnitRegistration
     */
    public function setIsRepeated($isRepeated)
    {
        $this->isRepeated = $isRepeated;

        return $this;
    }

    /**
     * Get isRepeated
     *
     * @return integer
     */
    public function getIsRepeated()
    {
        return $this->isRepeated;
    }

    /**
     * Set yearFirstInscription
     *
     * @param integer $yearFirstInscription
     *
     * @return UnitRegistration
     */
    public function setYearFirstInscription($yearFirstInscription)
    {
        $this->yearFirstInscription = $yearFirstInscription;

        return $this;
    }

    /**
     * Get yearFirstInscription
     *
     * @return integer
     */
    public function getYearFirstInscription()
    {
        return $this->yearFirstInscription;
    }

    /**
     * Set equivalentSubject
     *
     * @param integer $equivalentSubject
     *
     * @return UnitRegistration
     */
    public function setEquivalentSubject($equivalentSubject)
    {
        $this->equivalentSubject = $equivalentSubject;

        return $this;
    }

    /**
     * Get equivalentSubject
     *
     * @return integer
     */
    public function getEquivalentSubject()
    {
        return $this->equivalentSubject;
    }

    /**
     * Set admissionDecision
     *
     * @param integer $admissionDecision
     *
     * @return UnitRegistration
     */
    public function setAdmissionDecision($admissionDecision)
    {
        $this->admissionDecision = $admissionDecision;

        return $this;
    }

    /**
     * Get admissionDecision
     *
     * @return integer
     */
    public function getAdmissionDecision()
    {
        return $this->admissionDecision;
    }
    
    /**
     * Set isFromRatrappage
     * @param integer $isFromRatrappage
     *
     * @return UnitRegistration
     */
    public function setIsFromRatrappage($isFromRatrappage)
    {
        $this->isFromRatrappage = $isFromRatrappage;

        return $this;
    }

    /**
     * Get isFromRatrappage
     *
     * @return float
     */
    public function getIsFromRatrappage()
    {
        return $this->isFromRatrappage;
    }    
    /**
     * Set isFromDeliberation
     * @param integer $isFromDeliberation
     *
     * @return UnitRegistration
     */
    public function setIsFromDeliberation($isFromDeliberation)
    {
        $this->isFromDeliberation = $isFromDeliberation;

        return $this;
    }

    /**
     * Get calculationStatus
     *
     * @return integer
     */
    public function getCalculationStatus()
    {
        return $this->calculationStatus;
    }
    
    /**
     * Set calculationStatus
     * @param integer $calculationStatus
     *
     * @return UnitRegistration
     */
    public function setCalculationStatus($calculationStatus)
    {
        $this->calculationStatus = $calculationStatus;

        return $this;
    }

    /**
     * Get isFromDeliberation
     *
     * @return float
     */
    public function getIsFromDeliberation()
    {
        return $this->isFromDeliberation;
    }    
    /**
     * Set semester
     *
     * @param Semester $semester
     *
     * @return UnitRegistration
     */
    public function setSemester(Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return Semester
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set student
     *
     * @param Student $student
     *
     * @return UnitRegistration
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
     * Set teachingUnit
     *
     * @param TeachingUnit $teachingUnit
     *
     * @return UnitRegistration
     */
    public function setTeachingUnit(TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit
     *
     * @return TeachingUnit
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }
    /**
     * Set subject
     *
     * @param Subject $subject
     *
     * @return UnitRegistration
     */
    public function setSubject(Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }    
}
