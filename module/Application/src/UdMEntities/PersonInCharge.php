<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * PersonInCharge
 *
 * @ORM\Table(name="person_in_charge", indexes={@ORM\Index(name="fk_person_in_charge_department1_idx", columns={"department_id"}), @ORM\Index(name="fk_person_in_charge_school1_idx", columns={"school_id"}), @ORM\Index(name="fk_person_in_charge_faculty1_idx", columns={"faculty_id"}), @ORM\Index(name="fk_person_in_charge_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_person_in_charge_school_employee1_idx", columns={"school_employee_id"})})
 * @ORM\Entity
 */
class PersonInCharge
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="from", type="datetime", nullable=true)
     */
    private $from;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="to", type="datetime", nullable=true)
     */
    private $to;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = '0';

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
     * @var \Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;

    /**
     * @var \School
     *
     * @ORM\ManyToOne(targetEntity="School")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     * })
     */
    private $school;

    /**
     * @var \Speciality
     *
     * @ORM\ManyToOne(targetEntity="Speciality")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     * })
     */
    private $department;

    /**
     * @var \SchoolEmployee
     *
     * @ORM\ManyToOne(targetEntity="SchoolEmployee")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="school_employee_id", referencedColumnName="id")
     * })
     */
    private $schoolEmployee;



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
     * Set from.
     *
     * @param \DateTime|null $from
     *
     * @return PersonInCharge
     */
    public function setFrom($from = null)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from.
     *
     * @return \DateTime|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to.
     *
     * @param \DateTime|null $to
     *
     * @return PersonInCharge
     */
    public function setTo($to = null)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to.
     *
     * @return \DateTime|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set status.
     *
     * @param bool|null $status
     *
     * @return PersonInCharge
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
     * Set faculty.
     *
     * @param \Faculty|null $faculty
     *
     * @return PersonInCharge
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

    /**
     * Set degree.
     *
     * @param \Degree|null $degree
     *
     * @return PersonInCharge
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
     * Set school.
     *
     * @param \School|null $school
     *
     * @return PersonInCharge
     */
    public function setSchool(\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school.
     *
     * @return \School|null
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set department.
     *
     * @param \Speciality|null $department
     *
     * @return PersonInCharge
     */
    public function setDepartment(\Speciality $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department.
     *
     * @return \Speciality|null
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set schoolEmployee.
     *
     * @param \SchoolEmployee|null $schoolEmployee
     *
     * @return PersonInCharge
     */
    public function setSchoolEmployee(\SchoolEmployee $schoolEmployee = null)
    {
        $this->schoolEmployee = $schoolEmployee;

        return $this;
    }

    /**
     * Get schoolEmployee.
     *
     * @return \SchoolEmployee|null
     */
    public function getSchoolEmployee()
    {
        return $this->schoolEmployee;
    }
}
