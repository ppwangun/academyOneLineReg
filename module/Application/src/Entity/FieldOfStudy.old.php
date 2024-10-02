<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FieldOfStudy
 *
 * @ORM\Table(name="field_of_study", indexes={@ORM\Index(name="fk_training_faculty1_idx", columns={"faculty_id"}), @ORM\Index(name="fk_field_of_study_department1_idx", columns={"department_id"})})
 * @ORM\Entity
 */
class FieldOfStudy
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \Department
     *
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * })
     */
    private $department;

    /**
     * @var \Faculty
     *
     * @ORM\ManyToOne(targetEntity="Faculty")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="faculty_id", referencedColumnName="id")
     * })
     */
    private $faculty;



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
     * Set code.
     *
     * @param string|null $code
     *
     * @return FieldOfStudy
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
     * Set name.
     *
     * @param string|null $name
     *
     * @return FieldOfStudy
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
     * Set status.
     *
     * @param int|null $status
     *
     * @return FieldOfStudy
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
     * Set department.
     *
     * @param \Department|null $department
     *
     * @return FieldOfStudy
     */
    public function setDepartment(\Department $department = null)
    {
        $this->department = $department;
    
        return $this;
    }

    /**
     * Get department.
     *
     * @return \Department|null
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set faculty.
     *
     * @param \Faculty|null $faculty
     *
     * @return FieldOfStudy
     */
    public function setFaculty(\Faculty $faculty = null)
    {
        $this->faculty = $faculty;
    
        return $this;
    }

    /**
     * Get faculty.
     *
     * @return \Faculty|null
     */
    public function getFaculty()
    {
        return $this->faculty;
    }
}
