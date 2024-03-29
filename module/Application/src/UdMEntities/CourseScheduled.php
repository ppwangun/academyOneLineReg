<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CourseScheduled
 *
 * @ORM\Table(name="course_scheduled", indexes={@ORM\Index(name="fk_ressource_has_course_scheduled_teacher1_idx", columns={"teacher_id"}), @ORM\Index(name="fk_course_scheduled_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"}), @ORM\Index(name="fk_ressource_has_course_scheduled_course_scheduled1_idx", columns={"date_scheduled_date"}), @ORM\Index(name="fk_course_scheduled_time_slot1_idx", columns={"time_slot_id"}), @ORM\Index(name="fk_ressource_has_course_scheduled_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class CourseScheduled
{
    /**
     * @var \ClassOfStudyHasSemester
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;

    /**
     * @var \TimeSlot
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="TimeSlot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="time_slot_id", referencedColumnName="id")
     * })
     */
    private $timeSlot;

    /**
     * @var \ClassOfStudy
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var \DateScheduled
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="DateScheduled")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="date_scheduled_date", referencedColumnName="date")
     * })
     */
    private $dateScheduledDate;

    /**
     * @var \Teacher
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;



    /**
     * Set classOfStudyHasSemester.
     *
     * @param \ClassOfStudyHasSemester $classOfStudyHasSemester
     *
     * @return CourseScheduled
     */
    public function setClassOfStudyHasSemester(\ClassOfStudyHasSemester $classOfStudyHasSemester)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;
    
        return $this;
    }

    /**
     * Get classOfStudyHasSemester.
     *
     * @return \ClassOfStudyHasSemester
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }

    /**
     * Set timeSlot.
     *
     * @param \TimeSlot $timeSlot
     *
     * @return CourseScheduled
     */
    public function setTimeSlot(\TimeSlot $timeSlot)
    {
        $this->timeSlot = $timeSlot;
    
        return $this;
    }

    /**
     * Get timeSlot.
     *
     * @return \TimeSlot
     */
    public function getTimeSlot()
    {
        return $this->timeSlot;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return CourseScheduled
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy)
    {
        $this->classOfStudy = $classOfStudy;
    
        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set dateScheduledDate.
     *
     * @param \DateScheduled $dateScheduledDate
     *
     * @return CourseScheduled
     */
    public function setDateScheduledDate(\DateScheduled $dateScheduledDate)
    {
        $this->dateScheduledDate = $dateScheduledDate;
    
        return $this;
    }

    /**
     * Get dateScheduledDate.
     *
     * @return \DateScheduled
     */
    public function getDateScheduledDate()
    {
        return $this->dateScheduledDate;
    }

    /**
     * Set teacher.
     *
     * @param \Teacher $teacher
     *
     * @return CourseScheduled
     */
    public function setTeacher(\Teacher $teacher)
    {
        $this->teacher = $teacher;
    
        return $this;
    }

    /**
     * Get teacher.
     *
     * @return \Teacher
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
