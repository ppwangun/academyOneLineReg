<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="registered_student_for_active_registration_year_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class RegisteredStudentForActiveRegistrationYearView
{
    /**
    * @var string
    *
    * @ORM\Column(name="id", type="string", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
    * @var integer
    *
    * @ORM\Column(name="registration_id", type="integer", nullable=false)
    */
    private $registrationId;
    
     /**
    * @var integer
    *
    * @ORM\Column(name="acadYrCode", type="integer", nullable=false)
    */
    private $acadYrCode;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="status", type="integer", nullable=false)
    */
    private $status;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="isStudentRepeating", type="integer", nullable=false)
    */
    private $isStudentRepeating;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="student_id", type="integer", nullable=false)
    */
    private $studentId;
    /**
    * @var string
    *
    * @ORM\Column(name="matricule", type="string", length=45, nullable=true)
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
    * @var string
    *
    * @ORM\Column(name="phone_number", type="string", length=255, nullable=true)
    */
    
    private $phone_number;
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
    */
    private $dateNaissance;
    
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", nullable=true)
    */
    private $class;
    
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_inscription", type="datetime", nullable=true)
    */
    private $dateInscription;
    
    
    /**
    * @var decimal
    *
    * @ORM\Column(name="fees", type="decimal", nullable=true)
    */
    private $fees;

    /**
    * @var string
    *
    * @ORM\Column(name="email", type="string", nullable=true)
    */
    private $email; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="gender", type="string", nullable=true)
    */
    private $gender;
    
    /**
    * @var string
    *
    * @ORM\Column(name="born_at", type="string", nullable=true)
    */
    private $bornPlace;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="region_of_origin", type="string", nullable=true)
    */
    private $regionOfOrigin; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="religion", type="string", nullable=true)
    */
    private $religion; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="marital_status", type="string", nullable=true)
    */
    private $maritalStatus;  
    
    /**
    * @var string
    *
    * @ORM\Column(name="working_status", type="string", nullable=true)
    */
    private $workingStatus;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="nationality", type="string", nullable=true)
    */
    private $nationality;
    
    /**
    * @var string
    *
    * @ORM\Column(name="father_name", type="string", nullable=true)
    */
    private $fatherName;
    
    /**
    * @var string
    *
    * @ORM\Column(name="father_phone_number", type="string", nullable=true)
    */
    private $fatherPhoneNumber; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="father_email", type="string", nullable=true)
    */
    private $fatherEmail;  
    
    /**
    * @var string
    *
    * @ORM\Column(name="mother_name", type="string", nullable=true)
    */
    private $motherName;
    
    /**
    * @var string
    *
    * @ORM\Column(name="mother_phone_number", type="string", nullable=true)
    */
    private $motherPhoneNumber; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="mother_email", type="string", nullable=true)
    */
    private $motherEmail;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="sponsor_name", type="string", nullable=true)
    */
    private $sponsorName;
    
    /**
    * @var string
    *
    * @ORM\Column(name="sponsor_phone_number", type="string", nullable=true)
    */
    private $sponsorPhoneNumber; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="sponsor_email", type="string", nullable=true)
    */
    private $sponsorEmail;     
    
     /**
     * id
     *
     * @return integer
     */
    
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
     * Get acadYrCode
     *
     * @return string
     */
    public function getAcadYrCode()
    {
        return $this->acadYrCode;
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
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
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
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateOfBirth;
    }
    
     /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    } 
    
     /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }
    
     /**
     * Get studentId
     *
     * @return integer
     */
    public function getStudentId()
    {
        return $this->studentId;
    }
    
     /**
     * Get registrationId
     *
     * @return integer
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
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
     * Get phone_number
     *
     * @return string
     */
    public function getStdPhoneNumber()
    {
        return $this->phone_number;
    }  
    
      /**
     * Get dateNaissance
     *
     * @return string
     */
    public function getBirthDate()
    {
        return $this->dateNaissance;
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
     * Get bornPlace
     *
     * @return string
     */
    public function getBornPlace()
    {
        return $this->bornPlace;
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
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
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
     * Get maritalStatus
     *
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
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
     * Get fatherName
     *
     * @return string
     */
    public function getFatherName()
    {
        return $this->fatherName;
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
     * Get fatherEmail
     *
     * @return string
     */
    public function getFatherEmail()
    {
        return $this->fatherEmail;
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
     * Get motherPhoneNumber
     *
     * @return string
     */
    public function getMotherPhoneNumber()
    {
        return $this->motherPhoneNumber;
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
     * Get sponsorName
     *
     * @return string
     */
    public function getSponsorName()
    {
        return $this->sponsorName;
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
     * Get sponsorEmail
     *
     * @return string
     */
    public function getSponsorEmail()
    {
        return $this->sponsorEmail;
    }
    
    /**
     * Get isStudentRepeating
     *
     * @return integer
     */
    public function getIsStudentRepeating()
    {
        return $this->isStudentRepeating;
    }    
}

