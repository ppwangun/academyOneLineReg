<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StudentAttendance
 *
 * @ORM\Table(name="student_attendance", indexes={@ORM\Index(name="fk_student_attendance_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentAttendance
{
    /**
     * @var int
     *
     * @ORM\Column(name="course_scheduled_id", type="integer", nullable=false)
     */
    private $courseScheduledId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="1"})
     */
    private $status = true;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

    /**
     * @var \CourseScheduled
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="CourseScheduled")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;



    /**
     * Set courseScheduledId.
     *
     * @param int $courseScheduledId
     *
     * @return StudentAttendance
     */
    public function setCourseScheduledId($courseScheduledId)
    {
        $this->courseScheduledId = $courseScheduledId;

        return $this;
    }

    /**
     * Get courseScheduledId.
     *
     * @return int
     */
    public function getCourseScheduledId()
    {
        return $this->courseScheduledId;
    }

    /**
     * Set status.
     *
     * @param bool|null $status
     *
     * @return StudentAttendance
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set student.
     *
     * @param \Student|null $student
     *
     * @return StudentAttendance
     */
    public function setStudent(\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student.
     *
     * @return \Student|null
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set id.
     *
     * @param \CourseScheduled $id
     *
     * @return StudentAttendance
     */
    public function setId(\CourseScheduled $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return \CourseScheduled
     */
    public function getId()
    {
        return $this->id;
    }
}
