<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * DegreeHasCourseCategory
 *
 * @ORM\Table(name="degree_has_course_category", indexes={@ORM\Index(name="fk_degree_has_Course_category_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_degree_has_Course_category_field_of_study1_idx", columns={"field_of_study_id"}), @ORM\Index(name="fk_degree_has_Course_category_Course_category1_idx", columns={"Course_category_id"})})
 * @ORM\Entity
 */
class DegreeHasCourseCategory
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
     * @var \CourseCategory
     *
     * @ORM\ManyToOne(targetEntity="CourseCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Course_category_id", referencedColumnName="id")
     * })
     */
    private $courseCategory;

    /**
     * @var \Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;

    /**
     * @var \FieldOfStudy
     *
     * @ORM\ManyToOne(targetEntity="FieldOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_of_study_id", referencedColumnName="id")
     * })
     */
    private $fieldOfStudy;



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
     * Set courseCategory.
     *
     * @param \CourseCategory|null $courseCategory
     *
     * @return DegreeHasCourseCategory
     */
    public function setCourseCategory(\CourseCategory $courseCategory = null)
    {
        $this->courseCategory = $courseCategory;

        return $this;
    }

    /**
     * Get courseCategory.
     *
     * @return \CourseCategory|null
     */
    public function getCourseCategory()
    {
        return $this->courseCategory;
    }

    /**
     * Set degree.
     *
     * @param \Degree|null $degree
     *
     * @return DegreeHasCourseCategory
     */
    public function setDegree(\Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree.
     *
     * @return \Degree|null
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set fieldOfStudy.
     *
     * @param \FieldOfStudy|null $fieldOfStudy
     *
     * @return DegreeHasCourseCategory
     */
    public function setFieldOfStudy(\FieldOfStudy $fieldOfStudy = null)
    {
        $this->fieldOfStudy = $fieldOfStudy;

        return $this;
    }

    /**
     * Get fieldOfStudy.
     *
     * @return \FieldOfStudy|null
     */
    public function getFieldOfStudy()
    {
        return $this->fieldOfStudy;
    }
}
