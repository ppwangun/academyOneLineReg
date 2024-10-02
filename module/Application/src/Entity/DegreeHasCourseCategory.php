<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\CourseCategory;
use Application\Entity\Degree;
use Application\Entity\FieldOfStudy;

/**
 * DegreeHasCourseCategory
 *
 * @ORM\Table(name="degree_has_course_category", indexes={@ORM\Index(name="fk_degree_has_Course_category_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_degree_has_Course_category_field_of_study1_idx", columns={"field_of_study_id"}), @ORM\Index(name="fk_degree_has_Course_category_Course_category1_idx", columns={"Course_category_id"})})
 * @ORM\Entity
 */
class DegreeHasCourseCategory
{
    /**
     * @var \CourseCategory
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="CourseCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Course_category_id", referencedColumnName="id")
     * })
     */
    private $courseCategory;

    /**
     * @var \Degree
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;

    /**
     * @var \FieldOfStudy
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="FieldOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_of_study_id", referencedColumnName="id")
     * })
     */
    private $fieldOfStudy;



    /**
     * Set courseCategory.
     *
     * @param CourseCategory $courseCategory
     *
     * @return DegreeHasCourseCategory
     */
    public function setCourseCategory(CourseCategory $courseCategory)
    {
        $this->courseCategory = $courseCategory;
    
        return $this;
    }

    /**
     * Get courseCategory.
     *
     * @return CourseCategory
     */
    public function getCourseCategory()
    {
        return $this->courseCategory;
    }

    /**
     * Set degree.
     *
     * @param Degree $degree
     *
     * @return DegreeHasCourseCategory
     */
    public function setDegree(Degree $degree)
    {
        $this->degree = $degree;
    
        return $this;
    }

    /**
     * Get degree.
     *
     * @return Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set fieldOfStudy.
     *
     * @param FieldOfStudy $fieldOfStudy
     *
     * @return DegreeHasCourseCategory
     */
    public function setFieldOfStudy(FieldOfStudy $fieldOfStudy)
    {
        $this->fieldOfStudy = $fieldOfStudy;
    
        return $this;
    }

    /**
     * Get fieldOfStudy.
     *
     * @return FieldOfStudy
     */
    public function getFieldOfStudy()
    {
        return $this->fieldOfStudy;
    }
}
