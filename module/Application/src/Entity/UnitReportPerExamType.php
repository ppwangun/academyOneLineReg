<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\UnitReportPerSession;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\ExamType;

/**
 * UnitReportPerExamType
 *
 * @ORM\Table(name="unit_report_per_exam_type", indexes={@ORM\Index(name="fk_unit_report_per_exam_type_unit_report_per_session1_idx", columns={"unit_report_per_session_id"}), @ORM\Index(name="fk_unit_report_per_exam_type_teaching_unit1_idx", columns={"teaching_unit_id"}), @ORM\Index(name="fk_unit_report_per_exam_type_subject1_idx", columns={"subject_id"}), @ORM\Index(name="fk_note_per_exam_type_exam_type1_idx", columns={"exam_type_id"})})
 * @ORM\Entity
 */
class UnitReportPerExamType
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
     * @ORM\Column(name="note", type="float", precision=10, scale=0, nullable=true)
     */
    private $note;



    /**
     * @var ExamType
     *
     * @ORM\ManyToOne(targetEntity="ExamType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_type_id", referencedColumnName="id")
     * })
     */
    private $examType;

    /**
     * @var UnitReportPerSession
     *
     * @ORM\ManyToOne(targetEntity="UnitReportPerSession")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_report_per_session_id", referencedColumnName="id")
     * })
     */
    private $unitReportPerSession;
    
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
     * @var TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;    


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
     * Set note.
     *
     * @param float|null $note
     *
     * @return UnitReportPerExamType
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return float|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set teachingUnit.
     *
     * @param TeachingUnit|null $teachingUnit
     *
     * @return UnitReportPerExamType
     */
    public function setTeachingUnit(TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit.
     *
     * @return TeachingUnit|null
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }

    /**
     * Set examType.
     *
     * @param ExamType|null $examType
     *
     * @return UnitReportPerExamType
     */
    public function setExamType(ExamType $examType = null)
    {
        $this->examType = $examType;

        return $this;
    }

    /**
     * Get examType.
     *
     * @return ExamType|null
     */
    public function getExamType()
    {
        return $this->examType;
    }

    /**
     * Set unitReportPerSession.
     *
     * @param UnitReportPerSession|null $unitReportPerSession
     *
     * @return UnitReportPerExamType
     */
    public function setUnitReportPerSession(UnitReportPerSession $unitReportPerSession = null)
    {
        $this->unitReportPerSession = $unitReportPerSession;

        return $this;
    }

    /**
     * Get unitReportPerSession.
     *
     * @return UnitReportPerSession|null
     */
    public function getUnitReportPerSession()
    {
        return $this->unitReportPerSession;
    }

    /**
     * Set subject.
     *
     * @param Subject|null $subject
     *
     * @return UnitReportPerExamType
     */
    public function setSubject(Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return Subject|null
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
