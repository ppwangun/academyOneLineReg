<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\ExamType;

/**
 * Exam
 *
 * @ORM\Table(name="exam", indexes={@ORM\Index(name="fk_exam_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"}), @ORM\Index(name="fk_exam_exam_type1_idx", columns={"exam_type_code"})})
 * @ORM\Entity
 */
class Exam
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", length=45, nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_mark_registered", type="integer", nullable=true)
     */
    private $isMarkRegistered;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_mark_validated", type="integer", nullable=true)
     */
    private $isMarkValidated;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_mark_confirmed", type="integer", nullable=true)
     */
    private $isMarkConfirmed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_validated", type="datetime", nullable=true)
     */
    private $dateValidated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_registered", type="datetime", nullable=true)
     */
    private $dateRegistered;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_confirmed", type="datetime", nullable=true)
     */
    private $dateConfirmed;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_attendance_saved", type="integer", nullable=true)
     */
    private $isAttendanceSaved;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_anonymat_saved", type="integer", nullable=true)
     */
    private $isAnonymatSaved;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_catch_up_exam_performed", type="integer", nullable=true)
     */
    private $isCatchUpExamPerformed = '1';    
    
    /**
     * @var ClassOfStudyHasSemester
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;

    /**
     * @var ExamType
     *
     * @ORM\ManyToOne(targetEntity="ExamType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_type_code", referencedColumnName="code")
     * })
     */
    private $examTypeCode;



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
     * Set code
     *
     * @param string $code
     *
     * @return Exam
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Exam
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Exam
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set isMarkRegistered
     *
     * @param integer $isMarkRegistered
     *
     * @return Exam
     */
    public function setIsMarkRegistered($isMarkRegistered)
    {
        $this->isMarkRegistered = $isMarkRegistered;

        return $this;
    }

    /**
     * Get isMarkRegistered
     *
     * @return integer
     */
    public function getIsMarkRegistered()
    {
        return $this->isMarkRegistered;
    }

    /**
     * Set isMarkValidated
     *
     * @param integer $isMarkValidated
     *
     * @return Exam
     */
    public function setIsMarkValidated($isMarkValidated)
    {
        $this->isMarkValidated = $isMarkValidated;

        return $this;
    }

    /**
     * Get isMarkValidated
     *
     * @return integer
     */
    public function getIsMarkValidated()
    {
        return $this->isMarkValidated;
    }

    /**
     * Set isMarkConfirmed
     *
     * @param integer $isMarkConfirmed
     *
     * @return Exam
     */
    public function setIsMarkConfirmed($isMarkConfirmed)
    {
        $this->isMarkConfirmed = $isMarkConfirmed;

        return $this;
    }

    /**
     * Get isMarkConfirmed
     *
     * @return integer
     */
    public function getIsMarkConfirmed()
    {
        return $this->isMarkConfirmed;
    }

    /**
     * Set dateValidated
     *
     * @param \DateTime $dateValidated
     *
     * @return Exam
     */
    public function setDateValidated($dateValidated)
    {
        $this->dateValidated = $dateValidated;

        return $this;
    }

    /**
     * Get dateValidated
     *
     * @return \DateTime
     */
    public function getDateValidated()
    {
        return $this->dateValidated;
    }

    /**
     * Set dateRegistered
     *
     * @param \DateTime $dateRegistered
     *
     * @return Exam
     */
    public function setDateRegistered($dateRegistered)
    {
        $this->dateRegistered = $dateRegistered;

        return $this;
    }

    /**
     * Get dateRegistered
     *
     * @return \DateTime
     */
    public function getDateRegistered()
    {
        return $this->dateRegistered;
    }

    /**
     * Set dateConfirmed
     *
     * @param \DateTime $dateConfirmed
     *
     * @return Exam
     */
    public function setDateConfirmed($dateConfirmed)
    {
        $this->dateConfirmed = $dateConfirmed;

        return $this;
    }

    /**
     * Get dateConfirmed
     *
     * @return \DateTime
     */
    public function getDateConfirmed()
    {
        return $this->dateConfirmed;
    }

    /**
     * Set isAttendanceSaved
     *
     * @param integer $isAttendanceSaved
     *
     * @return Exam
     */
    public function setIsAttendanceSaved($isAttendanceSaved)
    {
        $this->isAttendanceSaved = $isAttendanceSaved;

        return $this;
    }

    /**
     * Get isAttendanceSaved
     *
     * @return integer
     */
    public function getIsAttendanceSaved()
    {
        return $this->isAttendanceSaved;
    }

    /**
     * Set isAnonymatSaved
     *
     * @param integer $isAnonymatSaved
     *
     * @return Exam
     */
    public function setIsAnonymatSaved($isAnonymatSaved)
    {
        $this->isAnonymatSaved = $isAnonymatSaved;

        return $this;
    }

    /**
     * Get isAnonymatSaved
     *
     * @return integer
     */
    public function getIsAnonymatSaved()
    {
        return $this->isAnonymatSaved;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Exam
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Set classOfStudyHasSemester
     *
     * @param ClassOfStudyHasSemester $classOfStudyHasSemester
     *
     * @return Exam
     */
    public function setClassOfStudyHasSemester(ClassOfStudyHasSemester $classOfStudyHasSemester = null)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;

        return $this;
    }

    /**
     * Get classOfStudyHasSemester
     *
     * @return ClassOfStudyHasSemester
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }

    /**
     * Set examTypeCode
     *
     * @param ExamType $examTypeCode
     *
     * @return Exam
     */
    public function setExamTypeCode(ExamType $examTypeCode = null)
    {
        $this->examTypeCode = $examTypeCode;

        return $this;
    }

    /**
     * Get examTypeCode
     *
     * @return ExamType
     */
    public function getExamTypeCode()
    {
        return $this->examTypeCode;
    }

    /**
     * Set isCatchUpExamPerformed
     *
     * @param integer $isCatchUpExamPerformed
     *
     * @return Exam
     */
    public function setIsCatchUpExamPerformed($isCatchUpExamPerformed)
    {
        $this->isCatchUpExamPerformed = $isCatchUpExamPerformed;

        return $this;
    }

    /**
     * Get isCatchUpExamPerformed
     *
     * @return integer
     */
    public function getIsCatchUpExamPerformed()
    {
        return $this->isCatchUpExamPerformed;
    }    
}
