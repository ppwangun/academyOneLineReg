<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Department
 *
 * @ORM\Table(name="department", indexes={@ORM\Index(name="fk_department_faculty1_idx", columns={"faculty_id"}), @ORM\Index(name="fk_department_person_in_charge1_idx", columns={"person_in_charge_id"})})
 * @ORM\Entity
 */
class Department
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
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var \PersonInCharge
     *
     * @ORM\ManyToOne(targetEntity="PersonInCharge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_in_charge_id", referencedColumnName="id")
     * })
     */
    private $personInCharge;

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
     * @return Department
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
     * @return Department
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
     * @param bool|null $status
     *
     * @return Department
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
     * Set personInCharge.
     *
     * @param \PersonInCharge|null $personInCharge
     *
     * @return Department
     */
    public function setPersonInCharge(\PersonInCharge $personInCharge = null)
    {
        $this->personInCharge = $personInCharge;

        return $this;
    }

    /**
     * Get personInCharge.
     *
     * @return \PersonInCharge|null
     */
    public function getPersonInCharge()
    {
        return $this->personInCharge;
    }

    /**
     * Set faculty.
     *
     * @param \Faculty|null $faculty
     *
     * @return Department
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
