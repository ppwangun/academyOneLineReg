<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * SemesterAssociatedToClass
 *
 * @ORM\Table(name="semester_associated_to_class", indexes={@ORM\Index(name="fk_semester_associated_to_class_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_semester_associated_to_class_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_semester_associated_to_class_semester1_idx", columns={"semester_id"})})
 * @ORM\Entity
 */
class SemesterAssociatedToClass
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
     * @var bool|null
     *
     * @ORM\Column(name="mark_calculation_status", type="boolean", nullable=true)
     */
    private $markCalculationStatus;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="sendSmsStatus", type="boolean", nullable=true)
     */
    private $sendsmsstatus = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="transcriptReferenceGenerationStatus", type="integer", nullable=true)
     */
    private $transcriptreferencegenerationstatus = '0';

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
     * @var \AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

    /**
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;



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
     * Set markCalculationStatus.
     *
     * @param bool|null $markCalculationStatus
     *
     * @return SemesterAssociatedToClass
     */
    public function setMarkCalculationStatus($markCalculationStatus = null)
    {
        $this->markCalculationStatus = $markCalculationStatus;

        return $this;
    }

    /**
     * Get markCalculationStatus.
     *
     * @return bool|null
     */
    public function getMarkCalculationStatus()
    {
        return $this->markCalculationStatus;
    }

    /**
     * Set sendsmsstatus.
     *
     * @param bool|null $sendsmsstatus
     *
     * @return SemesterAssociatedToClass
     */
    public function setSendsmsstatus($sendsmsstatus = null)
    {
        $this->sendsmsstatus = $sendsmsstatus;

        return $this;
    }

    /**
     * Get sendsmsstatus.
     *
     * @return bool|null
     */
    public function getSendsmsstatus()
    {
        return $this->sendsmsstatus;
    }

    /**
     * Set transcriptreferencegenerationstatus.
     *
     * @param int|null $transcriptreferencegenerationstatus
     *
     * @return SemesterAssociatedToClass
     */
    public function setTranscriptreferencegenerationstatus($transcriptreferencegenerationstatus = null)
    {
        $this->transcriptreferencegenerationstatus = $transcriptreferencegenerationstatus;

        return $this;
    }

    /**
     * Get transcriptreferencegenerationstatus.
     *
     * @return int|null
     */
    public function getTranscriptreferencegenerationstatus()
    {
        return $this->transcriptreferencegenerationstatus;
    }

    /**
     * Set semester.
     *
     * @param \Semester|null $semester
     *
     * @return SemesterAssociatedToClass
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
     * Set academicYear.
     *
     * @param \AcademicYear|null $academicYear
     *
     * @return SemesterAssociatedToClass
     */
    public function setAcademicYear(\AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return \AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return SemesterAssociatedToClass
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy|null
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }
}
