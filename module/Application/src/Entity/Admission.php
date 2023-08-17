<?php

namespace Application\Entity;

use Application\Entity\Degree;
use Application\Entity\AcademicYear;
use Application\Entity\ClassOfStudy;
use Application\Entity\ProspectiveStudent;

use Doctrine\ORM\Mapping as ORM;

/**
 * Admission
 *
 * @ORM\Table(name="admission", indexes={@ORM\Index(name="fk_admission_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_admission_academic_year1_idx", columns={"academic_year_id"}), @ORM\Index(name="fk_admission_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class Admission
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="file_number", type="string", length=45, nullable=true)
     */
    private $fileNumber;

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
     * @ORM\Column(name="date_admission", type="datetime", nullable=true)
     */
    private $dateAdmission;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     */
    private $paymentDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;
     /**
     * @var float
     *
     * @ORM\Column(name="fees_paid", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesPaid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="entrance_type", type="string", length=255, nullable=true)
     */
    private $entranceType;    

    /**
     * @var AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

    /**
     * @var ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

    /**
     * @var Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;



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
     * Set status
     *
     * @param integer $status
     *
     * @return Admission
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
     * Set fileNumber
     *
     * @param string $fileNumber
     *
     * @return Admission
     */
    public function setFileNumber($fileNumber)
    {
        $this->fileNumber = $fileNumber;

        return $this;
    }

    /**
     * Get fileNumber
     *
     * @return string
     */
    public function getFileNumber()
    {
        return $this->fileNumber;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Admission
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
     * @return Admission
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
     * Set dateAdmission
     *
     * @param \DateTime $dateAdmission
     *
     * @return Admission
     */
    public function setDateAdmission($dateAdmission)
    {
        $this->dateAdmission = $dateAdmission;

        return $this;
    }

    /**
     * Get paymentDate
     *
     * @return \DateTime
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }
    
    /**
     * Set paymentDate
     *
     * @param \DateTime $paymentDate
     *
     * @return Admission
     */
    public function setPaymentDate($paymentDate)
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    /**
     * Get dateAdmission
     *
     * @return \DateTime
     */
    public function getDateAdmission()
    {
        return $this->dateAdmission;
    }    

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return Admission
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
     * Set feesPaid
     *
     * @param float $feesPaid
     *
     * @return AdminRegistration
     */
    public function setFeesPaid($feesPaid)
    {
        $this->feesPaid = $feesPaid;

        return $this;
    }
    
    /**
     * Get feesPaid
     *
     * @return float
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }
    
    /**
     * Set entranceType
     *
     * @param string $entranceType
     *
     * @return Admission
     */
    public function setEntranceType($entranceType)
    {
        $this->entranceType = $entranceType;

        return $this;
    }

    /**
     * Get entranceType
     *
     * @return string
     */
    public function getEntranceType()
    {
        return $this->entranceType;
    }    

    /**
     * Set academicYear
     *
     * @param AcademicYear $academicYear
     *
     * @return Admission
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear
     *
     * @return AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set classOfStudy
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return Admission
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set degree
     *
     * @param Degree $degree
     *
     * @return Admission
     */
    public function setDegree(Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }
}
