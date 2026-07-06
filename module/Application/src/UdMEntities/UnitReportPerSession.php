<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * UnitReportPerSession
 *
 * @ORM\Table(name="unit_report_per_session", indexes={@ORM\Index(name="fk_UE_PV_unit_registration1_idx", columns={"unit_registration_id"}), @ORM\Index(name="fk_UE_PV_exam_session1_idx", columns={"exam_session_id"})})
 * @ORM\Entity
 */
class UnitReportPerSession
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
     * @ORM\Column(name="note", type="string", length=45, nullable=true)
     */
    private $note;

    /**
     * @var string|null
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;

    /**
     * @var string|null
     *
     * @ORM\Column(name="points", type="string", length=45, nullable=true)
     */
    private $points;

    /**
     * @var string|null
     *
     * @ORM\Column(name="result_status", type="string", length=45, nullable=true, options={"default"="FAILED"})
     */
    private $resultStatus = 'FAILED';

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
     * @param string|null $note
     *
     * @return UnitReportPerSession
     */
    public function setNote($note = null)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set grade.
     *
     * @param string|null $grade
     *
     * @return UnitReportPerSession
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
     * @param string|null $points
     *
     * @return UnitReportPerSession
     */
    public function setPoints($points = null)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return string|null
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set resultStatus.
     *
     * @param string|null $resultStatus
     *
     * @return UnitReportPerSession
     */
    public function setResultStatus($resultStatus = null)
    {
        $this->resultStatus = $resultStatus;

        return $this;
    }

    /**
     * Get resultStatus.
     *
     * @return string|null
     */
    public function getResultStatus()
    {
        return $this->resultStatus;
    }

    /**
     * Set unitRegistration.
     *
     * @param \UnitRegistration|null $unitRegistration
     *
     * @return UnitReportPerSession
     */
    public function setUnitRegistration(\UnitRegistration $unitRegistration = null)
    {
        $this->unitRegistration = $unitRegistration;

        return $this;
    }

    /**
     * Get unitRegistration.
     *
     * @return \UnitRegistration|null
     */
    public function getUnitRegistration()
    {
        return $this->unitRegistration;
    }

    /**
     * Set examSession.
     *
     * @param \ExamSession|null $examSession
     *
     * @return UnitReportPerSession
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
}
