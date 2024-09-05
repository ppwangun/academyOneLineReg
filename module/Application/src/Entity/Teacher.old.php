<?php
namespace Application\Entity;

use Application\Entity\AcademicRanck;
use Application\Entity\Faculty;



use Doctrine\ORM\Mapping as ORM;

/**
 * Teacher
 *
 * @ORM\Table(name="teacher", indexes={@ORM\Index(name="fk_teacher_academic_ranck1_idx", columns={"academic_ranck_id"}), @ORM\Index(name="fk_teacher_faculty1_idx", columns={"faculty_id"})})
 * @ORM\Entity
 */
class Teacher
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
     * @ORM\Column(name="civility", type="string", length=45, nullable=true)
     */
    private $civility;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="surname", type="string", length=45, nullable=true)
     */
    private $surname;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="birth_date", type="datetime", nullable=true)
     */
    private $birthDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="marital_status", type="string", length=45, nullable=true)
     */
    private $maritalStatus;

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
     * @ORM\Column(name="high_degree", type="string", length=45, nullable=true)
     */
    private $highDegree;

    /**
     * @var string|null
     *
     * @ORM\Column(name="speciality", type="string", length=45, nullable=true)
     */
    private $speciality;

    /**
     * @var string|null
     *
     * @ORM\Column(name="current_employer", type="string", length=45, nullable=true)
     */
    private $currentEmployer;

    /**
     * @var string|null
     *
     * @ORM\Column(name="living_city", type="string", length=45, nullable=true)
     */
    private $livingCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="living_country", type="string", length=45, nullable=true)
     */
    private $livingCountry;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nationality", type="string", length=45, nullable=true)
     */
    private $nationality;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=45, nullable=true)
     */
    private $type;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var AcademicRanck
     *
     * @ORM\ManyToOne(targetEntity="AcademicRanck")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_ranck_id", referencedColumnName="id")
     * })
     */
    private $academicRanck;

    /**
     * @var Faculty
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
     * Set civility.
     *
     * @param string|null $civility
     *
     * @return Teacher
     */
    public function setCivility($civility = null)
    {
        $this->civility = $civility;
    
        return $this;
    }

    /**
     * Get civility.
     *
     * @return string|null
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Teacher
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
     * Set surname.
     *
     * @param string|null $surname
     *
     * @return Teacher
     */
    public function setSurname($surname = null)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname.
     *
     * @return string|null
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set birthDate.
     *
     * @param \DateTime|null $birthDate
     *
     * @return Teacher
     */
    public function setBirthDate($birthDate = null)
    {
        $this->birthDate = $birthDate;
    
        return $this;
    }

    /**
     * Get birthDate.
     *
     * @return \DateTime|null
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set maritalStatus.
     *
     * @param string|null $maritalStatus
     *
     * @return Teacher
     */
    public function setMaritalStatus($maritalStatus = null)
    {
        $this->maritalStatus = $maritalStatus;
    
        return $this;
    }

    /**
     * Get maritalStatus.
     *
     * @return string|null
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Teacher
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
     * @return Teacher
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
     * Set highDegree.
     *
     * @param string|null $highDegree
     *
     * @return Teacher
     */
    public function setHighDegree($highDegree = null)
    {
        $this->highDegree = $highDegree;
    
        return $this;
    }

    /**
     * Get highDegree.
     *
     * @return string|null
     */
    public function getHighDegree()
    {
        return $this->highDegree;
    }

    /**
     * Set speciality.
     *
     * @param string|null $speciality
     *
     * @return Teacher
     */
    public function setSpeciality($speciality = null)
    {
        $this->speciality = $speciality;
    
        return $this;
    }

    /**
     * Get speciality.
     *
     * @return string|null
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set currentEmployer.
     *
     * @param string|null $currentEmployer
     *
     * @return Teacher
     */
    public function setCurrentEmployer($currentEmployer = null)
    {
        $this->currentEmployer = $currentEmployer;
    
        return $this;
    }

    /**
     * Get currentEmployer.
     *
     * @return string|null
     */
    public function getCurrentEmployer()
    {
        return $this->currentEmployer;
    }

    /**
     * Set livingCity.
     *
     * @param string|null $livingCity
     *
     * @return Teacher
     */
    public function setLivingCity($livingCity = null)
    {
        $this->livingCity = $livingCity;
    
        return $this;
    }

    /**
     * Get livingCity.
     *
     * @return string|null
     */
    public function getLivingCity()
    {
        return $this->livingCity;
    }

    /**
     * Set livingCountry.
     *
     * @param string|null $livingCountry
     *
     * @return Teacher
     */
    public function setLivingCountry($livingCountry = null)
    {
        $this->livingCountry = $livingCountry;
    
        return $this;
    }

    /**
     * Get livingCountry.
     *
     * @return string|null
     */
    public function getLivingCountry()
    {
        return $this->livingCountry;
    }

    /**
     * Set nationality.
     *
     * @param string|null $nationality
     *
     * @return Teacher
     */
    public function setNationality($nationality = null)
    {
        $this->nationality = $nationality;
    
        return $this;
    }

    /**
     * Get nationality.
     *
     * @return string|null
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set type.
     *
     * @param string|null $type
     *
     * @return Teacher
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
     * Set status.
     *
     * @param bool|null $status
     *
     * @return Teacher
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
     * Set academicRanck.
     *
     * @param AcademicRanck|null $academicRanck
     *
     * @return Teacher
     */
    public function setAcademicRanck(AcademicRanck $academicRanck = null)
    {
        $this->academicRanck = $academicRanck;
    
        return $this;
    }

    /**
     * Get academicRanck.
     *
     * @return AcademicRanck|null
     */
    public function getAcademicRanck()
    {
        return $this->academicRanck;
    }

    /**
     * Set faculty.
     *
     * @param Faculty|null $faculty
     *
     * @return Teacher
     */
    public function setFaculty(Faculty $faculty = null)
    {
        $this->faculty = $faculty;
    
        return $this;
    }

    /**
     * Get faculty.
     *
     * @return Faculty|null
     */
    public function getFaculty()
    {
        return $this->faculty;
    }
}
