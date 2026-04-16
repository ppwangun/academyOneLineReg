<?php
namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

use Application\Entity\UnitRegistration;
use Application\Entity\ExamSession;
use Application\Entity\ExamType;
/**
 * UnitReportPerExamType
 *
 * @ORM\Table(name="unit_report_per_exam_type", indexes={@ORM\Index(name="fk_note_per_exam_type_unit_registration1_idx", columns={"unit_registration_id"}), @ORM\Index(name="fk_note_per_exam_type_exam_type1_idx", columns={"exam_type_id"}), @ORM\Index(name="fk_note_per_exam_type_exam_session1_idx", columns={"exam_session_id"})})
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
     * @var \UnitRegistration
     *
     * @ORM\ManyToOne(targetEntity="UnitRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_registration_id", referencedColumnName="id")
     * })
     */
    private $unitRegistration;

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
     * @var \ExamType
     *
     * @ORM\ManyToOne(targetEntity="ExamType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_type_id", referencedColumnName="id")
     * })
     */
    private $examType;



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
     * Set unitRegistration.
     *
     * @param UnitRegistration|null $unitRegistration
     *
     * @return UnitReportPerExamType
     */
    public function setUnitRegistration(UnitRegistration $unitRegistration = null)
    {
        $this->unitRegistration = $unitRegistration;

        return $this;
    }

    /**
     * Get unitRegistration.
     *
     * @return UnitRegistration|null
     */
    public function getUnitRegistration()
    {
        return $this->unitRegistration;
    }

    /**
     * Set examSession.
     *
     * @param ExamSession|null $examSession
     *
     * @return UnitReportPerExamType
     */
    public function setExamSession(ExamSession $examSession = null)
    {
        $this->examSession = $examSession;

        return $this;
    }

    /**
     * Get examSession.
     *
     * @return ExamSession|null
     */
    public function getExamSession()
    {
        return $this->examSession;
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
}
