<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * UnitReportPerExamType
 *
 * @ORM\Table(name="unit_report_per_exam_type", indexes={@ORM\Index(name="fk_unit_report_per_exam_type_unit_report_per_session1_idx", columns={"unit_report_per_session_id"}), @ORM\Index(name="fk_unit_sumary_exam_type_exam_type1_idx", columns={"exam_type_id"})})
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
     * @var \UnitReportPerSession
     *
     * @ORM\ManyToOne(targetEntity="UnitReportPerSession")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="unit_report_per_session_id", referencedColumnName="id")
     * })
     */
    private $unitReportPerSession;

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
     * Set unitReportPerSession.
     *
     * @param \UnitReportPerSession|null $unitReportPerSession
     *
     * @return UnitReportPerExamType
     */
    public function setUnitReportPerSession(\UnitReportPerSession $unitReportPerSession = null)
    {
        $this->unitReportPerSession = $unitReportPerSession;

        return $this;
    }

    /**
     * Get unitReportPerSession.
     *
     * @return \UnitReportPerSession|null
     */
    public function getUnitReportPerSession()
    {
        return $this->unitReportPerSession;
    }

    /**
     * Set examType.
     *
     * @param \ExamType|null $examType
     *
     * @return UnitReportPerExamType
     */
    public function setExamType(\ExamType $examType = null)
    {
        $this->examType = $examType;

        return $this;
    }

    /**
     * Get examType.
     *
     * @return \ExamType|null
     */
    public function getExamType()
    {
        return $this->examType;
    }
}
