<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AssociatedResoure
 *
 * @ORM\Table(name="associated_resoure", indexes={@ORM\Index(name="fk_associated_resoure_course_scheduled1_idx", columns={"course_scheduled_date_scheduled_date", "course_scheduled_class_of_study_id", "course_scheduled_teacher_id", "course_scheduled_class_of_study_has_semester_id", "course_scheduled_time_slot_id"}), @ORM\Index(name="fk_associated_resoure_resource1_idx", columns={"resource_id"}), @ORM\Index(name="IDX_A372683CF5A9B154BFF87B758EECD83", columns={"course_scheduled_date_scheduled_date", "course_scheduled_class_of_study_id", "course_scheduled_teacher_id"})})
 * @ORM\Entity
 */
class AssociatedResoure
{
    /**
     * @var int
     *
     * @ORM\Column(name="course_scheduled_class_of_study_has_semester_id", type="integer", nullable=false)
     */
    private $courseScheduledClassOfStudyHasSemesterId;

    /**
     * @var int
     *
     * @ORM\Column(name="course_scheduled_time_slot_id", type="integer", nullable=false)
     */
    private $courseScheduledTimeSlotId;

    /**
     * @var \CourseScheduled
     *
     * @ORM\ManyToOne(targetEntity="CourseScheduled")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_scheduled_date_scheduled_date", referencedColumnName="date_scheduled_date"),
     *   @ORM\JoinColumn(name="course_scheduled_class_of_study_id", referencedColumnName="class_of_study_id"),
     *   @ORM\JoinColumn(name="course_scheduled_teacher_id", referencedColumnName="teacher_id")
     * })
     */
    private $courseScheduledDateScheduledDate;

    /**
     * @var \Resource
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Resource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     */
    private $resource;



    /**
     * Set courseScheduledClassOfStudyHasSemesterId.
     *
     * @param int $courseScheduledClassOfStudyHasSemesterId
     *
     * @return AssociatedResoure
     */
    public function setCourseScheduledClassOfStudyHasSemesterId($courseScheduledClassOfStudyHasSemesterId)
    {
        $this->courseScheduledClassOfStudyHasSemesterId = $courseScheduledClassOfStudyHasSemesterId;
    
        return $this;
    }

    /**
     * Get courseScheduledClassOfStudyHasSemesterId.
     *
     * @return int
     */
    public function getCourseScheduledClassOfStudyHasSemesterId()
    {
        return $this->courseScheduledClassOfStudyHasSemesterId;
    }

    /**
     * Set courseScheduledTimeSlotId.
     *
     * @param int $courseScheduledTimeSlotId
     *
     * @return AssociatedResoure
     */
    public function setCourseScheduledTimeSlotId($courseScheduledTimeSlotId)
    {
        $this->courseScheduledTimeSlotId = $courseScheduledTimeSlotId;
    
        return $this;
    }

    /**
     * Get courseScheduledTimeSlotId.
     *
     * @return int
     */
    public function getCourseScheduledTimeSlotId()
    {
        return $this->courseScheduledTimeSlotId;
    }

    /**
     * Set courseScheduledDateScheduledDate.
     *
     * @param \CourseScheduled|null $courseScheduledDateScheduledDate
     *
     * @return AssociatedResoure
     */
    public function setCourseScheduledDateScheduledDate(\CourseScheduled $courseScheduledDateScheduledDate = null)
    {
        $this->courseScheduledDateScheduledDate = $courseScheduledDateScheduledDate;
    
        return $this;
    }

    /**
     * Get courseScheduledDateScheduledDate.
     *
     * @return \CourseScheduled|null
     */
    public function getCourseScheduledDateScheduledDate()
    {
        return $this->courseScheduledDateScheduledDate;
    }

    /**
     * Set resource.
     *
     * @param \Resource $resource
     *
     * @return AssociatedResoure
     */
    public function setResource(\Resource $resource)
    {
        $this->resource = $resource;
    
        return $this;
    }

    /**
     * Get resource.
     *
     * @return \Resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}
