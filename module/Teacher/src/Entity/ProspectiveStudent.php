<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProspectiveStudent
 *
 * @ORM\Table(name="prospective_student")
 * @ORM\Entity
 */
class ProspectiveStudent
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_of_birth", type="datetime", nullable=true)
     */
    private $dateOfBirth;

    /**
     * @var string|null
     *
     * @ORM\Column(name="born_at", type="string", length=45, nullable=true)
     */
    private $bornAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="id_number", type="string", length=45, nullable=true)
     */
    private $idNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="gender", type="string", length=45, nullable=true)
     */
    private $gender;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nationality", type="string", length=45, nullable=true)
     */
    private $nationality;

    /**
     * @var string|null
     *
     * @ORM\Column(name="region_of_origin", type="string", length=255, nullable=true)
     */
    private $regionOfOrigin;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo", type="blob", length=0, nullable=true)
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="handicap", type="string", length=45, nullable=true)
     */
    private $handicap;

    /**
     * @var string|null
     *
     * @ORM\Column(name="religion", type="string", length=45, nullable=true)
     */
    private $religion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="language", type="string", length=45, nullable=true)
     */
    private $language;

    /**
     * @var string|null
     *
     * @ORM\Column(name="marital_status", type="string", length=45, nullable=true)
     */
    private $maritalStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="working_status", type="string", length=45, nullable=true)
     */
    private $workingStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_name", type="string", length=255, nullable=true)
     */
    private $fatherName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_profession", type="string", length=45, nullable=true)
     */
    private $fatherProfession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_phone_number", type="string", length=45, nullable=true)
     */
    private $fatherPhoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_email", type="string", length=45, nullable=true)
     */
    private $fatherEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_country", type="string", length=45, nullable=true)
     */
    private $fatherCountry;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_name", type="string", length=255, nullable=true)
     */
    private $motherName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_profession", type="string", length=45, nullable=true)
     */
    private $motherProfession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_phone_number", type="string", length=45, nullable=true)
     */
    private $motherPhoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_email", type="string", length=45, nullable=true)
     */
    private $motherEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_country", type="string", length=45, nullable=true)
     */
    private $motherCountry;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_school", type="string", length=255, nullable=true)
     */
    private $lastSchool;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entering_degree", type="string", length=45, nullable=true)
     */
    private $enteringDegree;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_ID", type="string", length=45, nullable=true)
     */
    private $degreeId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_exam_center", type="string", length=45, nullable=true)
     */
    private $degreeExamCenter;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_option", type="string", length=45, nullable=true)
     */
    private $degreeOption;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_reference_id", type="string", length=45, nullable=true)
     */
    private $degreeReferenceId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="studentcol", type="string", length=45, nullable=true)
     */
    private $studentcol;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_jury_number", type="string", length=45, nullable=true)
     */
    private $degreeJuryNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="degree_session", type="string", length=45, nullable=true)
     */
    private $degreeSession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sportive_information", type="text", length=65535, nullable=true)
     */
    private $sportiveInformation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cultural_information", type="text", length=65535, nullable=true)
     */
    private $culturalInformation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="associative_information", type="text", length=65535, nullable=true)
     */
    private $associativeInformation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="it_knowledge", type="text", length=65535, nullable=true)
     */
    private $itKnowledge;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_name", type="string", length=255, nullable=true)
     */
    private $sponsorName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_profession", type="string", length=255, nullable=true)
     */
    private $sponsorProfession;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_phone_number", type="string", length=255, nullable=true)
     */
    private $sponsorPhoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_email", type="string", length=255, nullable=true)
     */
    private $sponsorEmail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_country", type="string", length=255, nullable=true)
     */
    private $sponsorCountry;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sponsor_city", type="string", length=255, nullable=true)
     */
    private $sponsorCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="father_city", type="string", length=255, nullable=true)
     */
    private $fatherCity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mother_city", type="string", length=255, nullable=true)
     */
    private $motherCity;



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
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return ProspectiveStudent
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string|null $prenom
     *
     * @return ProspectiveStudent
     */
    public function setPrenom($prenom = null)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateOfBirth.
     *
     * @param \DateTime|null $dateOfBirth
     *
     * @return ProspectiveStudent
     */
    public function setDateOfBirth($dateOfBirth = null)
    {
        $this->dateOfBirth = $dateOfBirth;
    
        return $this;
    }

    /**
     * Get dateOfBirth.
     *
     * @return \DateTime|null
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set bornAt.
     *
     * @param string|null $bornAt
     *
     * @return ProspectiveStudent
     */
    public function setBornAt($bornAt = null)
    {
        $this->bornAt = $bornAt;
    
        return $this;
    }

    /**
     * Get bornAt.
     *
     * @return string|null
     */
    public function getBornAt()
    {
        return $this->bornAt;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return ProspectiveStudent
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
     * Set idNumber.
     *
     * @param string|null $idNumber
     *
     * @return ProspectiveStudent
     */
    public function setIdNumber($idNumber = null)
    {
        $this->idNumber = $idNumber;
    
        return $this;
    }

    /**
     * Get idNumber.
     *
     * @return string|null
     */
    public function getIdNumber()
    {
        return $this->idNumber;
    }

    /**
     * Set gender.
     *
     * @param string|null $gender
     *
     * @return ProspectiveStudent
     */
    public function setGender($gender = null)
    {
        $this->gender = $gender;
    
        return $this;
    }

    /**
     * Get gender.
     *
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return ProspectiveStudent
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
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return ProspectiveStudent
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
     * Set nationality.
     *
     * @param string|null $nationality
     *
     * @return ProspectiveStudent
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
     * Set regionOfOrigin.
     *
     * @param string|null $regionOfOrigin
     *
     * @return ProspectiveStudent
     */
    public function setRegionOfOrigin($regionOfOrigin = null)
    {
        $this->regionOfOrigin = $regionOfOrigin;
    
        return $this;
    }

    /**
     * Get regionOfOrigin.
     *
     * @return string|null
     */
    public function getRegionOfOrigin()
    {
        return $this->regionOfOrigin;
    }

    /**
     * Set photo.
     *
     * @param string|null $photo
     *
     * @return ProspectiveStudent
     */
    public function setPhoto($photo = null)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo.
     *
     * @return string|null
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set handicap.
     *
     * @param string|null $handicap
     *
     * @return ProspectiveStudent
     */
    public function setHandicap($handicap = null)
    {
        $this->handicap = $handicap;
    
        return $this;
    }

    /**
     * Get handicap.
     *
     * @return string|null
     */
    public function getHandicap()
    {
        return $this->handicap;
    }

    /**
     * Set religion.
     *
     * @param string|null $religion
     *
     * @return ProspectiveStudent
     */
    public function setReligion($religion = null)
    {
        $this->religion = $religion;
    
        return $this;
    }

    /**
     * Get religion.
     *
     * @return string|null
     */
    public function getReligion()
    {
        return $this->religion;
    }

    /**
     * Set language.
     *
     * @param string|null $language
     *
     * @return ProspectiveStudent
     */
    public function setLanguage($language = null)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language.
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set maritalStatus.
     *
     * @param string|null $maritalStatus
     *
     * @return ProspectiveStudent
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
     * Set workingStatus.
     *
     * @param string|null $workingStatus
     *
     * @return ProspectiveStudent
     */
    public function setWorkingStatus($workingStatus = null)
    {
        $this->workingStatus = $workingStatus;
    
        return $this;
    }

    /**
     * Get workingStatus.
     *
     * @return string|null
     */
    public function getWorkingStatus()
    {
        return $this->workingStatus;
    }

    /**
     * Set fatherName.
     *
     * @param string|null $fatherName
     *
     * @return ProspectiveStudent
     */
    public function setFatherName($fatherName = null)
    {
        $this->fatherName = $fatherName;
    
        return $this;
    }

    /**
     * Get fatherName.
     *
     * @return string|null
     */
    public function getFatherName()
    {
        return $this->fatherName;
    }

    /**
     * Set fatherProfession.
     *
     * @param string|null $fatherProfession
     *
     * @return ProspectiveStudent
     */
    public function setFatherProfession($fatherProfession = null)
    {
        $this->fatherProfession = $fatherProfession;
    
        return $this;
    }

    /**
     * Get fatherProfession.
     *
     * @return string|null
     */
    public function getFatherProfession()
    {
        return $this->fatherProfession;
    }

    /**
     * Set fatherPhoneNumber.
     *
     * @param string|null $fatherPhoneNumber
     *
     * @return ProspectiveStudent
     */
    public function setFatherPhoneNumber($fatherPhoneNumber = null)
    {
        $this->fatherPhoneNumber = $fatherPhoneNumber;
    
        return $this;
    }

    /**
     * Get fatherPhoneNumber.
     *
     * @return string|null
     */
    public function getFatherPhoneNumber()
    {
        return $this->fatherPhoneNumber;
    }

    /**
     * Set fatherEmail.
     *
     * @param string|null $fatherEmail
     *
     * @return ProspectiveStudent
     */
    public function setFatherEmail($fatherEmail = null)
    {
        $this->fatherEmail = $fatherEmail;
    
        return $this;
    }

    /**
     * Get fatherEmail.
     *
     * @return string|null
     */
    public function getFatherEmail()
    {
        return $this->fatherEmail;
    }

    /**
     * Set fatherCountry.
     *
     * @param string|null $fatherCountry
     *
     * @return ProspectiveStudent
     */
    public function setFatherCountry($fatherCountry = null)
    {
        $this->fatherCountry = $fatherCountry;
    
        return $this;
    }

    /**
     * Get fatherCountry.
     *
     * @return string|null
     */
    public function getFatherCountry()
    {
        return $this->fatherCountry;
    }

    /**
     * Set motherName.
     *
     * @param string|null $motherName
     *
     * @return ProspectiveStudent
     */
    public function setMotherName($motherName = null)
    {
        $this->motherName = $motherName;
    
        return $this;
    }

    /**
     * Get motherName.
     *
     * @return string|null
     */
    public function getMotherName()
    {
        return $this->motherName;
    }

    /**
     * Set motherProfession.
     *
     * @param string|null $motherProfession
     *
     * @return ProspectiveStudent
     */
    public function setMotherProfession($motherProfession = null)
    {
        $this->motherProfession = $motherProfession;
    
        return $this;
    }

    /**
     * Get motherProfession.
     *
     * @return string|null
     */
    public function getMotherProfession()
    {
        return $this->motherProfession;
    }

    /**
     * Set motherPhoneNumber.
     *
     * @param string|null $motherPhoneNumber
     *
     * @return ProspectiveStudent
     */
    public function setMotherPhoneNumber($motherPhoneNumber = null)
    {
        $this->motherPhoneNumber = $motherPhoneNumber;
    
        return $this;
    }

    /**
     * Get motherPhoneNumber.
     *
     * @return string|null
     */
    public function getMotherPhoneNumber()
    {
        return $this->motherPhoneNumber;
    }

    /**
     * Set motherEmail.
     *
     * @param string|null $motherEmail
     *
     * @return ProspectiveStudent
     */
    public function setMotherEmail($motherEmail = null)
    {
        $this->motherEmail = $motherEmail;
    
        return $this;
    }

    /**
     * Get motherEmail.
     *
     * @return string|null
     */
    public function getMotherEmail()
    {
        return $this->motherEmail;
    }

    /**
     * Set motherCountry.
     *
     * @param string|null $motherCountry
     *
     * @return ProspectiveStudent
     */
    public function setMotherCountry($motherCountry = null)
    {
        $this->motherCountry = $motherCountry;
    
        return $this;
    }

    /**
     * Get motherCountry.
     *
     * @return string|null
     */
    public function getMotherCountry()
    {
        return $this->motherCountry;
    }

    /**
     * Set lastSchool.
     *
     * @param string|null $lastSchool
     *
     * @return ProspectiveStudent
     */
    public function setLastSchool($lastSchool = null)
    {
        $this->lastSchool = $lastSchool;
    
        return $this;
    }

    /**
     * Get lastSchool.
     *
     * @return string|null
     */
    public function getLastSchool()
    {
        return $this->lastSchool;
    }

    /**
     * Set enteringDegree.
     *
     * @param string|null $enteringDegree
     *
     * @return ProspectiveStudent
     */
    public function setEnteringDegree($enteringDegree = null)
    {
        $this->enteringDegree = $enteringDegree;
    
        return $this;
    }

    /**
     * Get enteringDegree.
     *
     * @return string|null
     */
    public function getEnteringDegree()
    {
        return $this->enteringDegree;
    }

    /**
     * Set degreeId.
     *
     * @param string|null $degreeId
     *
     * @return ProspectiveStudent
     */
    public function setDegreeId($degreeId = null)
    {
        $this->degreeId = $degreeId;
    
        return $this;
    }

    /**
     * Get degreeId.
     *
     * @return string|null
     */
    public function getDegreeId()
    {
        return $this->degreeId;
    }

    /**
     * Set degreeExamCenter.
     *
     * @param string|null $degreeExamCenter
     *
     * @return ProspectiveStudent
     */
    public function setDegreeExamCenter($degreeExamCenter = null)
    {
        $this->degreeExamCenter = $degreeExamCenter;
    
        return $this;
    }

    /**
     * Get degreeExamCenter.
     *
     * @return string|null
     */
    public function getDegreeExamCenter()
    {
        return $this->degreeExamCenter;
    }

    /**
     * Set degreeOption.
     *
     * @param string|null $degreeOption
     *
     * @return ProspectiveStudent
     */
    public function setDegreeOption($degreeOption = null)
    {
        $this->degreeOption = $degreeOption;
    
        return $this;
    }

    /**
     * Get degreeOption.
     *
     * @return string|null
     */
    public function getDegreeOption()
    {
        return $this->degreeOption;
    }

    /**
     * Set degreeReferenceId.
     *
     * @param string|null $degreeReferenceId
     *
     * @return ProspectiveStudent
     */
    public function setDegreeReferenceId($degreeReferenceId = null)
    {
        $this->degreeReferenceId = $degreeReferenceId;
    
        return $this;
    }

    /**
     * Get degreeReferenceId.
     *
     * @return string|null
     */
    public function getDegreeReferenceId()
    {
        return $this->degreeReferenceId;
    }

    /**
     * Set studentcol.
     *
     * @param string|null $studentcol
     *
     * @return ProspectiveStudent
     */
    public function setStudentcol($studentcol = null)
    {
        $this->studentcol = $studentcol;
    
        return $this;
    }

    /**
     * Get studentcol.
     *
     * @return string|null
     */
    public function getStudentcol()
    {
        return $this->studentcol;
    }

    /**
     * Set degreeJuryNumber.
     *
     * @param string|null $degreeJuryNumber
     *
     * @return ProspectiveStudent
     */
    public function setDegreeJuryNumber($degreeJuryNumber = null)
    {
        $this->degreeJuryNumber = $degreeJuryNumber;
    
        return $this;
    }

    /**
     * Get degreeJuryNumber.
     *
     * @return string|null
     */
    public function getDegreeJuryNumber()
    {
        return $this->degreeJuryNumber;
    }

    /**
     * Set degreeSession.
     *
     * @param string|null $degreeSession
     *
     * @return ProspectiveStudent
     */
    public function setDegreeSession($degreeSession = null)
    {
        $this->degreeSession = $degreeSession;
    
        return $this;
    }

    /**
     * Get degreeSession.
     *
     * @return string|null
     */
    public function getDegreeSession()
    {
        return $this->degreeSession;
    }

    /**
     * Set sportiveInformation.
     *
     * @param string|null $sportiveInformation
     *
     * @return ProspectiveStudent
     */
    public function setSportiveInformation($sportiveInformation = null)
    {
        $this->sportiveInformation = $sportiveInformation;
    
        return $this;
    }

    /**
     * Get sportiveInformation.
     *
     * @return string|null
     */
    public function getSportiveInformation()
    {
        return $this->sportiveInformation;
    }

    /**
     * Set culturalInformation.
     *
     * @param string|null $culturalInformation
     *
     * @return ProspectiveStudent
     */
    public function setCulturalInformation($culturalInformation = null)
    {
        $this->culturalInformation = $culturalInformation;
    
        return $this;
    }

    /**
     * Get culturalInformation.
     *
     * @return string|null
     */
    public function getCulturalInformation()
    {
        return $this->culturalInformation;
    }

    /**
     * Set associativeInformation.
     *
     * @param string|null $associativeInformation
     *
     * @return ProspectiveStudent
     */
    public function setAssociativeInformation($associativeInformation = null)
    {
        $this->associativeInformation = $associativeInformation;
    
        return $this;
    }

    /**
     * Get associativeInformation.
     *
     * @return string|null
     */
    public function getAssociativeInformation()
    {
        return $this->associativeInformation;
    }

    /**
     * Set itKnowledge.
     *
     * @param string|null $itKnowledge
     *
     * @return ProspectiveStudent
     */
    public function setItKnowledge($itKnowledge = null)
    {
        $this->itKnowledge = $itKnowledge;
    
        return $this;
    }

    /**
     * Get itKnowledge.
     *
     * @return string|null
     */
    public function getItKnowledge()
    {
        return $this->itKnowledge;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return ProspectiveStudent
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
     * Set sponsorName.
     *
     * @param string|null $sponsorName
     *
     * @return ProspectiveStudent
     */
    public function setSponsorName($sponsorName = null)
    {
        $this->sponsorName = $sponsorName;
    
        return $this;
    }

    /**
     * Get sponsorName.
     *
     * @return string|null
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
    }

    /**
     * Set sponsorProfession.
     *
     * @param string|null $sponsorProfession
     *
     * @return ProspectiveStudent
     */
    public function setSponsorProfession($sponsorProfession = null)
    {
        $this->sponsorProfession = $sponsorProfession;
    
        return $this;
    }

    /**
     * Get sponsorProfession.
     *
     * @return string|null
     */
    public function getSponsorProfession()
    {
        return $this->sponsorProfession;
    }

    /**
     * Set sponsorPhoneNumber.
     *
     * @param string|null $sponsorPhoneNumber
     *
     * @return ProspectiveStudent
     */
    public function setSponsorPhoneNumber($sponsorPhoneNumber = null)
    {
        $this->sponsorPhoneNumber = $sponsorPhoneNumber;
    
        return $this;
    }

    /**
     * Get sponsorPhoneNumber.
     *
     * @return string|null
     */
    public function getSponsorPhoneNumber()
    {
        return $this->sponsorPhoneNumber;
    }

    /**
     * Set sponsorEmail.
     *
     * @param string|null $sponsorEmail
     *
     * @return ProspectiveStudent
     */
    public function setSponsorEmail($sponsorEmail = null)
    {
        $this->sponsorEmail = $sponsorEmail;
    
        return $this;
    }

    /**
     * Get sponsorEmail.
     *
     * @return string|null
     */
    public function getSponsorEmail()
    {
        return $this->sponsorEmail;
    }

    /**
     * Set sponsorCountry.
     *
     * @param string|null $sponsorCountry
     *
     * @return ProspectiveStudent
     */
    public function setSponsorCountry($sponsorCountry = null)
    {
        $this->sponsorCountry = $sponsorCountry;
    
        return $this;
    }

    /**
     * Get sponsorCountry.
     *
     * @return string|null
     */
    public function getSponsorCountry()
    {
        return $this->sponsorCountry;
    }

    /**
     * Set sponsorCity.
     *
     * @param string|null $sponsorCity
     *
     * @return ProspectiveStudent
     */
    public function setSponsorCity($sponsorCity = null)
    {
        $this->sponsorCity = $sponsorCity;
    
        return $this;
    }

    /**
     * Get sponsorCity.
     *
     * @return string|null
     */
    public function getSponsorCity()
    {
        return $this->sponsorCity;
    }

    /**
     * Set fatherCity.
     *
     * @param string|null $fatherCity
     *
     * @return ProspectiveStudent
     */
    public function setFatherCity($fatherCity = null)
    {
        $this->fatherCity = $fatherCity;
    
        return $this;
    }

    /**
     * Get fatherCity.
     *
     * @return string|null
     */
    public function getFatherCity()
    {
        return $this->fatherCity;
    }

    /**
     * Set motherCity.
     *
     * @param string|null $motherCity
     *
     * @return ProspectiveStudent
     */
    public function setMotherCity($motherCity = null)
    {
        $this->motherCity = $motherCity;
    
        return $this;
    }

    /**
     * Get motherCity.
     *
     * @return string|null
     */
    public function getMotherCity()
    {
        return $this->motherCity;
    }
}
