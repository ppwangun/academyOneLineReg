<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class Student
{
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active student.
    const STATUS_SUSPENDED    = 2; // student that has suspended his trainning
    const STATUS_RETIRED      = 3; // Student that has completed his training.
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=45, nullable=false)
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string
     *
     * @ORM\Column(name="born_at", type="string", length=45, nullable=true)
     */
    private $bornAt;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=45, nullable=true)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="nationality", type="string", length=45, nullable=true)
     */
    private $nationality;

    /**
     * @var string
     *
     * @ORM\Column(name="region_of_origin", type="string", length=255, nullable=true)
     */
    private $regionOfOrigin;

    /**
     * @var blob
     *
     * @ORM\Column(name="photo", type="blob", length=65535, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="handicap", type="string", length=45, nullable=true)
     */
    private $handicap;

    /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string", length=45, nullable=true)
     */
    private $religion;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=45, nullable=true)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="marital_status", type="string", length=45, nullable=true)
     */
    private $maritalStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="working_status", type="string", length=45, nullable=true)
     */
    private $workingStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="father_name", type="string", length=255, nullable=true)
     */
    private $fatherName;

    /**
     * @var string
     *
     * @ORM\Column(name="father_profession", type="string", length=45, nullable=true)
     */
    private $fatherProfession;

    /**
     * @var string
     *
     * @ORM\Column(name="father_phone_number", type="string", length=45, nullable=true)
     */
    private $fatherPhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="father_email", type="string", length=45, nullable=true)
     */
    private $fatherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="father_country", type="string", length=45, nullable=true)
     */
    private $fatherCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_name", type="string", length=255, nullable=true)
     */
    private $motherName;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_profession", type="string", length=45, nullable=true)
     */
    private $motherProfession;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_phone_number", type="string", length=45, nullable=true)
     */
    private $motherPhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_email", type="string", length=45, nullable=true)
     */
    private $motherEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_country", type="string", length=45, nullable=true)
     */
    private $motherCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="last_school", type="string", length=255, nullable=true)
     */
    private $lastSchool;

    /**
     * @var string
     *
     * @ORM\Column(name="entering_degree", type="string", length=45, nullable=true)
     */
    private $enteringDegree;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_ID", type="string", length=45, nullable=true)
     */
    private $degreeId;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_exam_center", type="string", length=45, nullable=true)
     */
    private $degreeExamCenter;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_option", type="string", length=45, nullable=true)
     */
    private $degreeOption;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_reference_id", type="string", length=45, nullable=true)
     */
    private $degreeReferenceId;

    /**
     * @var string
     *
     * @ORM\Column(name="studentcol", type="string", length=45, nullable=true)
     */
    private $studentcol;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_jury_number", type="string", length=45, nullable=true)
     */
    private $degreeJuryNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_session", type="string", length=45, nullable=true)
     */
    private $degreeSession;

    /**
     * @var string
     *
     * @ORM\Column(name="sportive_information", type="text", length=65535, nullable=true)
     */
    private $sportiveInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="cultural_information", type="text", length=65535, nullable=true)
     */
    private $culturalInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="associative_information", type="text", length=65535, nullable=true)
     */
    private $associativeInformation;

    /**
     * @var string
     *
     * @ORM\Column(name="it_knowledge", type="text", length=65535, nullable=true)
     */
    private $itKnowledge;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_name", type="string", length=255, nullable=true)
     */
    private $sponsorName;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_profession", type="string", length=255, nullable=true)
     */
    private $sponsorProfession;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_phone_number", type="string", length=255, nullable=true)
     */
    private $sponsorPhoneNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_email", type="string", length=255, nullable=true)
     */
    private $sponsorEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_country", type="string", length=255, nullable=true)
     */
    private $sponsorCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="sponsor_city", type="string", length=255, nullable=true)
     */
    private $sponsorCity;

    /**
     * @var string
     *
     * @ORM\Column(name="father_city", type="string", length=255, nullable=true)
     */
    private $fatherCity;

    /**
     * @var string
     *
     * @ORM\Column(name="mother_city", type="string", length=255, nullable=true)
     */
    private $motherCity;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Student
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Student
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Student
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateOfBirth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Student
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * Get dateOfBirth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set bornAt
     *
     * @param string $bornAt
     *
     * @return Student
     */
    public function setBornAt($bornAt)
    {
        $this->bornAt = $bornAt;

        return $this;
    }

    /**
     * Get bornAt
     *
     * @return string
     */
    public function getBornAt()
    {
        return $this->bornAt;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Student
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return Student
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return Student
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set regionOfOrigin
     *
     * @param string $regionOfOrigin
     *
     * @return Student
     */
    public function setRegionOfOrigin($regionOfOrigin)
    {
        $this->regionOfOrigin = $regionOfOrigin;

        return $this;
    }

    /**
     * Get regionOfOrigin
     *
     * @return string
     */
    public function getRegionOfOrigin()
    {
        return $this->regionOfOrigin;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Student
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set handicap
     *
     * @param string $handicap
     *
     * @return Student
     */
    public function setHandicap($handicap)
    {
        $this->handicap = $handicap;

        return $this;
    }

    /**
     * Get handicap
     *
     * @return string
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * Set religion
     *
     * @param string $religion
     *
     * @return Student
     */
    public function setReligion($religion)
    {
        $this->religion = $religion;

        return $this;
    }

    /**
     * Get religion
     *
     * @return string
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set language
     *
     * @param string $language
     *
     * @return Student
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set maritalStatus
     *
     * @param string $maritalStatus
     *
     * @return Student
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;

        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * Set workingStatus
     *
     * @param string $workingStatus
     *
     * @return Student
     */
    public function setWorkingStatus($workingStatus)
    {
        $this->workingStatus = $workingStatus;

        return $this;
    }

    /**
     * Get workingStatus
     *
     * @return string
     */
    public function getWorkingStatus()
    {
        return $this->workingStatus;
    }

    /**
     * Set fatherName
     *
     * @param string $fatherName
     *
     * @return Student
     */
    public function setFatherName($fatherName)
    {
        $this->fatherName = $fatherName;

        return $this;
    }

    /**
     * Get fatherName
     *
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * Set fatherProfession
     *
     * @param string $fatherProfession
     *
     * @return Student
     */
    public function setFatherProfession($fatherProfession)
    {
        $this->fatherProfession = $fatherProfession;

        return $this;
    }

    /**
     * Get fatherProfession
     *
     * @return string
     */
    public function getFatherProfession()
    {
        return $this->fatherProfession;
    }

    /**
     * Set fatherPhoneNumber
     *
     * @param string $fatherPhoneNumber
     *
     * @return Student
     */
    public function setFatherPhoneNumber($fatherPhoneNumber)
    {
        $this->fatherPhoneNumber = $fatherPhoneNumber;

        return $this;
    }

    /**
     * Get fatherPhoneNumber
     *
     * @return string
     */
    public function getFatherPhoneNumber()
    {
        return $this->fatherPhoneNumber;
    }

    /**
     * Set fatherEmail
     *
     * @param string $fatherEmail
     *
     * @return Student
     */
    public function setFatherEmail($fatherEmail)
    {
        $this->fatherEmail = $fatherEmail;

        return $this;
    }

    /**
     * Get fatherEmail
     *
     * @return string
     */
    public function getFatherEmail()
    {
        return $this->fatherEmail;
    }

    /**
     * Set fatherCountry
     *
     * @param string $fatherCountry
     *
     * @return Student
     */
    public function setFatherCountry($fatherCountry)
    {
        $this->fatherCountry = $fatherCountry;

        return $this;
    }

    /**
     * Get fatherCountry
     *
     * @return string
     */
    public function getFatherCountry()
    {
        return $this->fatherCountry;
    }

    /**
     * Set motherName
     *
     * @param string $motherName
     *
     * @return Student
     */
    public function setMotherName($motherName)
    {
        $this->motherName = $motherName;

        return $this;
    }

    /**
     * Get motherName
     *
     * @return string
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * Set motherProfession
     *
     * @param string $motherProfession
     *
     * @return Student
     */
    public function setMotherProfession($motherProfession)
    {
        $this->motherProfession = $motherProfession;

        return $this;
    }

    /**
     * Get motherProfession
     *
     * @return string
     */
    public function getMotherProfession()
    {
        return $this->motherProfession;
    }

    /**
     * Set motherPhoneNumber
     *
     * @param string $motherPhoneNumber
     *
     * @return Student
     */
    public function setMotherPhoneNumber($motherPhoneNumber)
    {
        $this->motherPhoneNumber = $motherPhoneNumber;

        return $this;
    }

    /**
     * Get motherPhoneNumber
     *
     * @return string
     */
    public function getMotherPhoneNumber()
    {
        return $this->motherPhoneNumber;
    }

    /**
     * Set motherEmail
     *
     * @param string $motherEmail
     *
     * @return Student
     */
    public function setMotherEmail($motherEmail)
    {
        $this->motherEmail = $motherEmail;

        return $this;
    }

    /**
     * Get motherEmail
     *
     * @return string
     */
    public function getMotherEmail()
    {
        return $this->motherEmail;
    }

    /**
     * Set motherCountry
     *
     * @param string $motherCountry
     *
     * @return Student
     */
    public function setMotherCountry($motherCountry)
    {
        $this->motherCountry = $motherCountry;

        return $this;
    }

    /**
     * Get motherCountry
     *
     * @return string
     */
    public function getMotherCountry()
    {
        return $this->motherCountry;
    }

    /**
     * Set lastSchool
     *
     * @param string $lastSchool
     *
     * @return Student
     */
    public function setLastSchool($lastSchool)
    {
        $this->lastSchool = $lastSchool;

        return $this;
    }

    /**
     * Get lastSchool
     *
     * @return string
     */
    public function getLastSchool()
    {
        return $this->lastSchool;
    }

    /**
     * Set enteringDegree
     *
     * @param string $enteringDegree
     *
     * @return Student
     */
    public function setEnteringDegree($enteringDegree)
    {
        $this->enteringDegree = $enteringDegree;

        return $this;
    }

    /**
     * Get enteringDegree
     *
     * @return string
     */
    public function getEnteringDegree()
    {
        return $this->enteringDegree;
    }

    /**
     * Set degreeId
     *
     * @param string $degreeId
     *
     * @return Student
     */
    public function setDegreeId($degreeId)
    {
        $this->degreeId = $degreeId;

        return $this;
    }

    /**
     * Get degreeId
     *
     * @return string
     */
    public function getDegreeId()
    {
        return $this->degreeId;
    }

    /**
     * Set degreeExamCenter
     *
     * @param string $degreeExamCenter
     *
     * @return Student
     */
    public function setDegreeExamCenter($degreeExamCenter)
    {
        $this->degreeExamCenter = $degreeExamCenter;

        return $this;
    }

    /**
     * Get degreeExamCenter
     *
     * @return string
     */
    public function getDegreeExamCenter()
    {
        return $this->degreeExamCenter;
    }

    /**
     * Set degreeOption
     *
     * @param string $degreeOption
     *
     * @return Student
     */
    public function setDegreeOption($degreeOption)
    {
        $this->degreeOption = $degreeOption;

        return $this;
    }

    /**
     * Get degreeOption
     *
     * @return string
     */
    public function getDegreeOption()
    {
        return $this->degreeOption;
    }

    /**
     * Set degreeReferenceId
     *
     * @param string $degreeReferenceId
     *
     * @return Student
     */
    public function setDegreeReferenceId($degreeReferenceId)
    {
        $this->degreeReferenceId = $degreeReferenceId;

        return $this;
    }

    /**
     * Get degreeReferenceId
     *
     * @return string
     */
    public function getDegreeReferenceId()
    {
        return $this->degreeReferenceId;
    }

    /**
     * Set studentcol
     *
     * @param string $studentcol
     *
     * @return Student
     */
    public function setStudentcol($studentcol)
    {
        $this->studentcol = $studentcol;

        return $this;
    }

    /**
     * Get studentcol
     *
     * @return string
     */
    public function getStudentcol()
    {
        return $this->studentcol;
    }

    /**
     * Set degreeJuryNumber
     *
     * @param string $degreeJuryNumber
     *
     * @return Student
     */
    public function setDegreeJuryNumber($degreeJuryNumber)
    {
        $this->degreeJuryNumber = $degreeJuryNumber;

        return $this;
    }

    /**
     * Get degreeJuryNumber
     *
     * @return string
     */
    public function getDegreeJuryNumber()
    {
        return $this->degreeJuryNumber;
    }

    /**
     * Set degreeSession
     *
     * @param string $degreeSession
     *
     * @return Student
     */
    public function setDegreeSession($degreeSession)
    {
        $this->degreeSession = $degreeSession;

        return $this;
    }

    /**
     * Get degreeSession
     *
     * @return string
     */
    public function getDegreeSession()
    {
        return $this->degreeSession;
    }

    /**
     * Set sportiveInformation
     *
     * @param string $sportiveInformation
     *
     * @return Student
     */
    public function setSportiveInformation($sportiveInformation)
    {
        $this->sportiveInformation = $sportiveInformation;

        return $this;
    }

    /**
     * Get sportiveInformation
     *
     * @return string
     */
    public function getSportiveInformation()
    {
        return $this->sportiveInformation;
    }

    /**
     * Set culturalInformation
     *
     * @param string $culturalInformation
     *
     * @return Student
     */
    public function setCulturalInformation($culturalInformation)
    {
        $this->culturalInformation = $culturalInformation;

        return $this;
    }

    /**
     * Get culturalInformation
     *
     * @return string
     */
    public function getCulturalInformation()
    {
        return $this->culturalInformation;
    }

    /**
     * Set associativeInformation
     *
     * @param string $associativeInformation
     *
     * @return Student
     */
    public function setAssociativeInformation($associativeInformation)
    {
        $this->associativeInformation = $associativeInformation;

        return $this;
    }

    /**
     * Get associativeInformation
     *
     * @return string
     */
    public function getAssociativeInformation()
    {
        return $this->associativeInformation;
    }

    /**
     * Set itKnowledge
     *
     * @param string $itKnowledge
     *
     * @return Student
     */
    public function setItKnowledge($itKnowledge)
    {
        $this->itKnowledge = $itKnowledge;

        return $this;
    }

    /**
     * Get itKnowledge
     *
     * @return string
     */
    public function getItKnowledge()
    {
        return $this->itKnowledge;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Student
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set sponsorName
     *
     * @param string $sponsorName
     *
     * @return Student
     */
    public function setSponsorName($sponsorName)
    {
        $this->sponsorName = $sponsorName;

        return $this;
    }

    /**
     * Get sponsorName
     *
     * @return string
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
    }

    /**
     * Set sponsorProfession
     *
     * @param string $sponsorProfession
     *
     * @return Student
     */
    public function setSponsorProfession($sponsorProfession)
    {
        $this->sponsorProfession = $sponsorProfession;

        return $this;
    }

    /**
     * Get sponsorProfession
     *
     * @return string
     */
    public function getSponsorProfession()
    {
        return $this->sponsorProfession;
    }

    /**
     * Set sponsorPhoneNumber
     *
     * @param string $sponsorPhoneNumber
     *
     * @return Student
     */
    public function setSponsorPhoneNumber($sponsorPhoneNumber)
    {
        $this->sponsorPhoneNumber = $sponsorPhoneNumber;

        return $this;
    }

    /**
     * Get sponsorPhoneNumber
     *
     * @return string
     */
    public function getSponsorPhoneNumber()
    {
        return $this->sponsorPhoneNumber;
    }

    /**
     * Set sponsorEmail
     *
     * @param string $sponsorEmail
     *
     * @return Student
     */
    public function setSponsorEmail($sponsorEmail)
    {
        $this->sponsorEmail = $sponsorEmail;

        return $this;
    }

    /**
     * Get sponsorEmail
     *
     * @return string
     */
    public function getSponsorEmail()
    {
        return $this->sponsorEmail;
    }

    /**
     * Set sponsorCountry
     *
     * @param string $sponsorCountry
     *
     * @return Student
     */
    public function setSponsorCountry($sponsorCountry)
    {
        $this->sponsorCountry = $sponsorCountry;

        return $this;
    }

    /**
     * Get sponsorCountry
     *
     * @return string
     */
    public function getSponsorCountry()
    {
        return $this->sponsorCountry;
    }

    /**
     * Set sponsorCity
     *
     * @param string $sponsorCity
     *
     * @return Student
     */
    public function setSponsorCity($sponsorCity)
    {
        $this->sponsorCity = $sponsorCity;

        return $this;
    }

    /**
     * Get sponsorCity
     *
     * @return string
     */
    public function getSponsorCity()
    {
        return $this->sponsorCity;
    }

    /**
     * Set fatherCity
     *
     * @param string $fatherCity
     *
     * @return Student
     */
    public function setFatherCity($fatherCity)
    {
        $this->fatherCity = $fatherCity;

        return $this;
    }

    /**
     * Get fatherCity
     *
     * @return string
     */
    public function getFatherCity()
    {
        return $this->fatherCity;
    }

    /**
     * Set motherCity
     *
     * @param string $motherCity
     *
     * @return Student
     */
    public function setMotherCity($motherCity)
    {
        $this->motherCity = $motherCity;

        return $this;
    }

    /**
     * Get motherCity
     *
     * @return string
     */
    public function getMotherCity()
    {
        return $this->motherCity;
    }
}
