<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CourseScheduled
 *
 * @ORM\Table(name="course_scheduled", indexes={@ORM\Index(name="fk_course_scheduled_contract_follow_up1_idx", columns={"contract_follow_up_id"}), @ORM\Index(name="fk_course_scheduled_subject1_idx", columns={"subject_id"}), @ORM\Index(name="fk_ressource_has_course_scheduled_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_course_scheduled_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_ressource_has_course_scheduled_teacher1_idx", columns={"teacher_id"}), @ORM\Index(name="fk_course_scheduled_resource1_idx", columns={"resource_id"}), @ORM\Index(name="fk_course_scheduled_teaching_unit1_idx", columns={"teaching_unit_id"})})
 * @ORM\Entity
 */
class CourseScheduled
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_scheduled", type="date", nullable=false)
     */
    private $dateScheduled;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="starting_time", type="datetime", nullable=true)
     */
    private $startingTime;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ending_time", type="datetime", nullable=true)
     */
    private $endingTime;

    /**
     * @var string|null
     *
     * @ORM\Column(name="schedule_type", type="string", length=45, nullable=true)
     */
    private $scheduleType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_cours", type="string", length=4, nullable=true)
     */
    private $typeCours;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_validated", type="boolean", nullable=true)
     */
    private $isValidated = '0';

    /**
     * @var \ContractFollowUp
     *
     * @ORM\ManyToOne(targetEntity="ContractFollowUp")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_follow_up_id", referencedColumnName="id")
     * })
     */
    private $contractFollowUp;

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
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;

    /**
     * @var \Resource
     *
     * @ORM\ManyToOne(targetEntity="Resource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     */
    private $resource;

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
     * Set dateScheduled.
     *
     * @param \DateTime $dateScheduled
     *
     * @return CourseScheduled
     */
    public function setDateScheduled($dateScheduled)
    {
        $this->dateScheduled = $dateScheduled;

        return $this;
    }

    /**
     * Get dateScheduled.
     *
     * @return \DateTime
     */
    public function getDateScheduled()
    {
        return $this->dateScheduled;
    }

    /**
     * Set startingTime.
     *
     * @param \DateTime|null $startingTime
     *
     * @return CourseScheduled
     */
    public function setStartingTime($startingTime = null)
    {
        $this->startingTime = $startingTime;

        return $this;
    }

    /**
     * Get startingTime.
     *
     * @return \DateTime|null
     */
    public function getStartingTime()
    {
        return $this->startingTime;
    }

    /**
     * Set endingTime.
     *
     * @param \DateTime|null $endingTime
     *
     * @return CourseScheduled
     */
    public function setEndingTime($endingTime = null)
    {
        $this->endingTime = $endingTime;

        return $this;
    }

    /**
     * Get endingTime.
     *
     * @return \DateTime|null
     */
    public function getEndingTime()
    {
        return $this->endingTime;
    }

    /**
     * Set scheduleType.
     *
     * @param string|null $scheduleType
     *
     * @return CourseScheduled
     */
    public function setScheduleType($scheduleType = null)
    {
        $this->scheduleType = $scheduleType;

        return $this;
    }

    /**
     * Get scheduleType.
     *
     * @return string|null
     */
    public function getScheduleType()
    {
        return $this->scheduleType;
    }

    /**
     * Set typeCours.
     *
     * @param string|null $typeCours
     *
     * @return CourseScheduled
     */
    public function setTypeCours($typeCours = null)
    {
        $this->typeCours = $typeCours;

        return $this;
    }

    /**
     * Get typeCours.
     *
     * @return string|null
     */
    public function getTypeCours()
    {
        return $this->typeCours;
    }

    /**
     * Set isValidated.
     *
     * @param bool|null $isValidated
     *
     * @return CourseScheduled
     */
    public function setIsValidated($isValidated = null)
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    /**
     * Get isValidated.
     *
     * @return bool|null
     */
    public function getIsValidated()
    {
        return $this->isValidated;
    }

    /**
     * Set contractFollowUp.
     *
     * @param \ContractFollowUp|null $contractFollowUp
     *
     * @return CourseScheduled
     */
    public function setContractFollowUp(\ContractFollowUp $contractFollowUp = null)
    {
        $this->contractFollowUp = $contractFollowUp;

        return $this;
    }

    /**
     * Get contractFollowUp.
     *
     * @return \ContractFollowUp|null
     */
    public function getContractFollowUp()
    {
        return $this->contractFollowUp;
    }

    /**
     * Set subject.
     *
     * @param \Subject|null $subject
     *
     * @return CourseScheduled
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
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return CourseScheduled
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

    /**
     * Set resource.
     *
     * @param \Resource|null $resource
     *
     * @return CourseScheduled
     */
    public function setResource(\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource.
     *
     * @return \Resource|null
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set teachingUnit.
     *
     * @param \TeachingUnit|null $teachingUnit
     *
     * @return CourseScheduled
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
     * @return CourseScheduled
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
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return CourseScheduled
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
