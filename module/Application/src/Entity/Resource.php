<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Resource
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity
 */
class Resource
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
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CourseScheduled", inversedBy="resource")
     * @ORM\JoinTable(name="associated_resoure",
     *   joinColumns={
     *     @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="course_scheduled_date_scheduled_date", referencedColumnName="date_scheduled"),
     *     @ORM\JoinColumn(name="course_scheduled_class_of_study_id", referencedColumnName="class_of_study_id"),
     *     @ORM\JoinColumn(name="course_scheduled_teacher_id", referencedColumnName="teacher_id"),
     *     @ORM\JoinColumn(name="course_scheduled_teaching_unit_id", referencedColumnName="teaching_unit_id")
     *   }
     * )
     */
    private $courseScheduledDateScheduledDate = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->courseScheduledDateScheduledDate = new \Doctrine\Common\Collections\ArrayCollection();
    }


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
     * Set name.
     *
     * @param string|null $name
     *
     * @return Resource
     */
    public function setName($name = null)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return Resource
     */
    public function setCode($code = null)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return Resource
     */
    public function setType($type = null)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Add courseScheduledDateScheduledDate.
     *
     * @param \CourseScheduled $courseScheduledDateScheduledDate
     *
     * @return Resource
     */
    public function addCourseScheduledDateScheduledDate(\CourseScheduled $courseScheduledDateScheduledDate)
    {
        $this->courseScheduledDateScheduledDate[] = $courseScheduledDateScheduledDate;
    
        return $this;
    }

    /**
     * Remove courseScheduledDateScheduledDate.
     *
     * @param \CourseScheduled $courseScheduledDateScheduledDate
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCourseScheduledDateScheduledDate(\CourseScheduled $courseScheduledDateScheduledDate)
    {
        return $this->courseScheduledDateScheduledDate->removeElement($courseScheduledDateScheduledDate);
    }

    /**
     * Get courseScheduledDateScheduledDate.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourseScheduledDateScheduledDate()
    {
        return $this->courseScheduledDateScheduledDate;
    }
}
