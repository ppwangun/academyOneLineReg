<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AssociatedResoure
 *
 * @ORM\Table(name="associated_resoure", indexes={@ORM\Index(name="fk_associated_resoure_course_scheduled1_idx", columns={"course_scheduled_date_scheduled_date", "course_scheduled_class_of_study_id", "course_scheduled_teacher_id", "course_scheduled_class_of_study_has_semester_id", "course_scheduled_time_slot_id"}), @ORM\Index(name="fk_associated_resoure_resource1_idx", columns={"resource_id"})})
 * @ORM\Entity
 */
class AssociatedResoure
{
    /**
     * @var \CourseScheduled
     *
     * @ORM\ManyToOne(targetEntity="CourseScheduled")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_scheduled_date_scheduled_date", referencedColumnName="date_scheduled_date"),
     *   @ORM\JoinColumn(name="course_scheduled_class_of_study_id", referencedColumnName="class_of_study_id"),
     *   @ORM\JoinColumn(name="course_scheduled_teacher_id", referencedColumnName="teacher_id"),
     *   @ORM\JoinColumn(name="course_scheduled_class_of_study_has_semester_id", referencedColumnName="class_of_study_has_semester_id"),
     *   @ORM\JoinColumn(name="course_scheduled_time_slot_id", referencedColumnName="time_slot_id")
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
