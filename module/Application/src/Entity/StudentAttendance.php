<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Student;
use Application\Entity\CourseScheduled;

/**
 * StudentAttendance
 *
 * @ORM\Table(name="student_attendance", indexes={@ORM\Index(name="fk_student_attendance_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_course_scheduled_idx", columns={"course_scheduled_id"})})
 * @ORM\Entity
 */
class StudentAttendance
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
     * @ORM\Column(name="status", type="boolean", nullable=true, options={"default"="1"})
     */
    private $status = true;

    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;

    /**
     * @var CourseScheduled
     *
     * @ORM\ManyToOne(targetEntity="CourseScheduled")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_scheduled_id", referencedColumnName="id")
     * })
     */
    private $courseScheduled;



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
     * @param Student|null $student
     *
     * @return StudentAttendance
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student.
     *
     * @return Student|null
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set courseScheduled.
     *
     * @param CourseScheduled|null $courseScheduled
     *
     * @return StudentAttendance
     */
    public function setCourseScheduled(CourseScheduled $courseScheduled = null)
    {
        $this->courseScheduled = $courseScheduled;

        return $this;
    }

    /**
     * Get courseScheduled.
     *
     * @return CourseScheduled|null
     */
    public function getCourseScheduled()
    {
        return $this->courseScheduled;
    }
}
