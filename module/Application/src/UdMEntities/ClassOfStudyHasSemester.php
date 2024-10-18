<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ClassOfStudyHasSemester
 *
 * @ORM\Table(name="class_of_study_has_semester", indexes={@ORM\Index(name="fk_class_of_study_has_semester_teaching_unit1_idx", columns={"teaching_unit_id"}), @ORM\Index(name="fk_class_of_study_has_semester_subject1_idx", columns={"subject_id"}), @ORM\Index(name="fk_class_of_study_has_semester_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_class_of_study_has_semester_teacher1_idx", columns={"teacher_id"}), @ORM\Index(name="fk_class_of_study_has_semester_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class ClassOfStudyHasSemester
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
     * @ORM\Column(name="credits", type="float", precision=10, scale=0, nullable=true)
     */
    private $credits;

    /**
     * @var int|null
     *
     * @ORM\Column(name="hours_volume", type="integer", nullable=true)
     */
    private $hoursVolume;

    /**
     * @var float|null
     *
     * @ORM\Column(name="subject_weight", type="float", precision=10, scale=0, nullable=true, options={"default"="1"})
     */
    private $subjectWeight = 1;

    /**
     * @var int|null
     *
     * @ORM\Column(name="subject_hours", type="integer", nullable=true)
     */
    private $subjectHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cm_hours", type="integer", nullable=true)
     */
    private $cmHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tp_hours", type="integer", nullable=true)
     */
    private $tpHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="td_hours", type="integer", nullable=true)
     */
    private $tdHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="subject_cm_hours", type="integer", nullable=true)
     */
    private $subjectCmHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="subject_td_hours", type="integer", nullable=true)
     */
    private $subjectTdHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="subject_tp_hours", type="integer", nullable=true)
     */
    private $subjectTpHours;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="mark_calculation_status", type="boolean", nullable=true)
     */
    private $markCalculationStatus = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_previous_year_subject", type="boolean", nullable=true)
     */
    private $isPreviousYearSubject = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_repeated_subject", type="integer", nullable=true)
     */
    private $isRepeatedSubject = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="module_consolidation_status", type="boolean", nullable=true)
     */
    private $moduleConsolidationStatus = '0';

    /**
     * @var float|null
     *
     * @ORM\Column(name="subject_credits", type="float", precision=10, scale=0, nullable=true)
     */
    private $subjectCredits;

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
     * @var \TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;

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
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;



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
     * Set credits.
     *
     * @param float|null $credits
     *
     * @return ClassOfStudyHasSemester
     */
    public function setCredits($credits = null)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits.
     *
     * @return float|null
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Set hoursVolume.
     *
     * @param int|null $hoursVolume
     *
     * @return ClassOfStudyHasSemester
     */
    public function setHoursVolume($hoursVolume = null)
    {
        $this->hoursVolume = $hoursVolume;

        return $this;
    }

    /**
     * Get hoursVolume.
     *
     * @return int|null
     */
    public function getHoursVolume()
    {
        return $this->hoursVolume;
    }

    /**
     * Set subjectWeight.
     *
     * @param float|null $subjectWeight
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectWeight($subjectWeight = null)
    {
        $this->subjectWeight = $subjectWeight;

        return $this;
    }

    /**
     * Get subjectWeight.
     *
     * @return float|null
     */
    public function getSubjectWeight()
    {
        return $this->subjectWeight;
    }

    /**
     * Set subjectHours.
     *
     * @param int|null $subjectHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectHours($subjectHours = null)
    {
        $this->subjectHours = $subjectHours;

        return $this;
    }

    /**
     * Get subjectHours.
     *
     * @return int|null
     */
    public function getSubjectHours()
    {
        return $this->subjectHours;
    }

    /**
     * Set cmHours.
     *
     * @param int|null $cmHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setCmHours($cmHours = null)
    {
        $this->cmHours = $cmHours;

        return $this;
    }

    /**
     * Get cmHours.
     *
     * @return int|null
     */
    public function getCmHours()
    {
        return $this->cmHours;
    }

    /**
     * Set tpHours.
     *
     * @param int|null $tpHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTpHours($tpHours = null)
    {
        $this->tpHours = $tpHours;

        return $this;
    }

    /**
     * Get tpHours.
     *
     * @return int|null
     */
    public function getTpHours()
    {
        return $this->tpHours;
    }

    /**
     * Set tdHours.
     *
     * @param int|null $tdHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTdHours($tdHours = null)
    {
        $this->tdHours = $tdHours;

        return $this;
    }

    /**
     * Get tdHours.
     *
     * @return int|null
     */
    public function getTdHours()
    {
        return $this->tdHours;
    }

    /**
     * Set subjectCmHours.
     *
     * @param int|null $subjectCmHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectCmHours($subjectCmHours = null)
    {
        $this->subjectCmHours = $subjectCmHours;

        return $this;
    }

    /**
     * Get subjectCmHours.
     *
     * @return int|null
     */
    public function getSubjectCmHours()
    {
        return $this->subjectCmHours;
    }

    /**
     * Set subjectTdHours.
     *
     * @param int|null $subjectTdHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectTdHours($subjectTdHours = null)
    {
        $this->subjectTdHours = $subjectTdHours;

        return $this;
    }

    /**
     * Get subjectTdHours.
     *
     * @return int|null
     */
    public function getSubjectTdHours()
    {
        return $this->subjectTdHours;
    }

    /**
     * Set subjectTpHours.
     *
     * @param int|null $subjectTpHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectTpHours($subjectTpHours = null)
    {
        $this->subjectTpHours = $subjectTpHours;

        return $this;
    }

    /**
     * Get subjectTpHours.
     *
     * @return int|null
     */
    public function getSubjectTpHours()
    {
        return $this->subjectTpHours;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return ClassOfStudyHasSemester
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
     * Set markCalculationStatus.
     *
     * @param bool|null $markCalculationStatus
     *
     * @return ClassOfStudyHasSemester
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
     * Set isPreviousYearSubject.
     *
     * @param bool|null $isPreviousYearSubject
     *
     * @return ClassOfStudyHasSemester
     */
    public function setIsPreviousYearSubject($isPreviousYearSubject = null)
    {
        $this->isPreviousYearSubject = $isPreviousYearSubject;

        return $this;
    }

    /**
     * Get isPreviousYearSubject.
     *
     * @return bool|null
     */
    public function getIsPreviousYearSubject()
    {
        return $this->isPreviousYearSubject;
    }

    /**
     * Set isRepeatedSubject.
     *
     * @param int|null $isRepeatedSubject
     *
     * @return ClassOfStudyHasSemester
     */
    public function setIsRepeatedSubject($isRepeatedSubject = null)
    {
        $this->isRepeatedSubject = $isRepeatedSubject;

        return $this;
    }

    /**
     * Get isRepeatedSubject.
     *
     * @return int|null
     */
    public function getIsRepeatedSubject()
    {
        return $this->isRepeatedSubject;
    }

    /**
     * Set moduleConsolidationStatus.
     *
     * @param bool|null $moduleConsolidationStatus
     *
     * @return ClassOfStudyHasSemester
     */
    public function setModuleConsolidationStatus($moduleConsolidationStatus = null)
    {
        $this->moduleConsolidationStatus = $moduleConsolidationStatus;

        return $this;
    }

    /**
     * Get moduleConsolidationStatus.
     *
     * @return bool|null
     */
    public function getModuleConsolidationStatus()
    {
        return $this->moduleConsolidationStatus;
    }

    /**
     * Set subjectCredits.
     *
     * @param float|null $subjectCredits
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectCredits($subjectCredits = null)
    {
        $this->subjectCredits = $subjectCredits;

        return $this;
    }

    /**
     * Get subjectCredits.
     *
     * @return float|null
     */
    public function getSubjectCredits()
    {
        return $this->subjectCredits;
    }

    /**
     * Set semester.
     *
     * @param \Semester|null $semester
     *
     * @return ClassOfStudyHasSemester
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
     * Set teachingUnit.
     *
     * @param \TeachingUnit|null $teachingUnit
     *
     * @return ClassOfStudyHasSemester
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
     * Set subject.
     *
     * @param \Subject|null $subject
     *
     * @return ClassOfStudyHasSemester
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

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return ClassOfStudyHasSemester
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

    /**
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTeacher(\Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher.
     *
     * @return \Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
