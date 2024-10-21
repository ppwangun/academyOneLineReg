<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StudentParent
 *
 * @ORM\Table(name="student_parent", indexes={@ORM\Index(name="fk_student_parent_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_student_parent_prospective_student1_idx", columns={"prospective_student_id"})})
 * @ORM\Entity
 */
class StudentParent
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
     * @ORM\Column(name="profession", type="string", length=45, nullable=true)
     */
    private $profession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="country", type="string", length=45, nullable=true)
     */
    private $country;

    /**
     * @var string|null
     *
     * @ORM\Column(name="city", type="string", length=45, nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="parent_tye", type="string", length=45, nullable=true)
     */
    private $parentTye;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tutor_ralation_with_student", type="string", length=45, nullable=true)
     */
    private $tutorRalationWithStudent;

    /**
     * @var \ProspectiveStudent
     *
     * @ORM\ManyToOne(targetEntity="ProspectiveStudent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prospective_student_id", referencedColumnName="id")
     * })
     */
    private $prospectiveStudent;

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
     * @return StudentParent
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
     * Set profession.
     *
     * @param string|null $profession
     *
     * @return StudentParent
     */
    public function setProfession($profession = null)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession.
     *
     * @return string|null
     */
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return StudentParent
     */
    public function setPhoneNumber($phoneNumber = null)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber.
     *
     * @return string|null
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return StudentParent
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set country.
     *
     * @param string|null $country
     *
     * @return StudentParent
     */
    public function setCountry($country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return string|null
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city.
     *
     * @param string|null $city
     *
     * @return StudentParent
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set parentTye.
     *
     * @param string|null $parentTye
     *
     * @return StudentParent
     */
    public function setParentTye($parentTye = null)
    {
        $this->parentTye = $parentTye;

        return $this;
    }

    /**
     * Get parentTye.
     *
     * @return string|null
     */
    public function getParentTye()
    {
        return $this->parentTye;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return StudentParent
     */
    public function setAdresse($adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set tutorRalationWithStudent.
     *
     * @param string|null $tutorRalationWithStudent
     *
     * @return StudentParent
     */
    public function setTutorRalationWithStudent($tutorRalationWithStudent = null)
    {
        $this->tutorRalationWithStudent = $tutorRalationWithStudent;

        return $this;
    }

    /**
     * Get tutorRalationWithStudent.
     *
     * @return string|null
     */
    public function getTutorRalationWithStudent()
    {
        return $this->tutorRalationWithStudent;
    }

    /**
     * Set prospectiveStudent.
     *
     * @param \ProspectiveStudent|null $prospectiveStudent
     *
     * @return StudentParent
     */
    public function setProspectiveStudent(\ProspectiveStudent $prospectiveStudent = null)
    {
        $this->prospectiveStudent = $prospectiveStudent;

        return $this;
    }

    /**
     * Get prospectiveStudent.
     *
     * @return \ProspectiveStudent|null
     */
    public function getProspectiveStudent()
    {
        return $this->prospectiveStudent;
    }

    /**
     * Set student.
     *
     * @param \Student|null $student
     *
     * @return StudentParent
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
}
