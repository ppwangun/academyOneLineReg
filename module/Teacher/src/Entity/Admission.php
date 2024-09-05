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
 * @ORM\Table(name="admission", indexes={@ORM\Index(name="fk_admission_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_admission_prospective_student1_idx", columns={"prospective_student_id"}), @ORM\Index(name="fk_admission_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_admission_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class Admission
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
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="file_number", type="string", length=45, nullable=true)
     */
    private $fileNumber;

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
     * @ORM\Column(name="date_admission", type="datetime", nullable=true)
     */
    private $dateAdmission;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone_number", type="string", length=45, nullable=true)
     */
    private $phoneNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fees_paid", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $feesPaid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entrance_type", type="string", length=45, nullable=true)
     */
    private $entranceType;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     */
    private $paymentDate;

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
     * @var ProspectiveStudent
     *
     * @ORM\ManyToOne(targetEntity="ProspectiveStudent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prospective_student_id", referencedColumnName="id")
     * })
     */
    private $prospectiveStudent;



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
     * Set status.
     *
     * @param int|null $status
     *
     * @return Admission
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
     * Set fileNumber.
     *
     * @param string|null $fileNumber
     *
     * @return Admission
     */
    public function setFileNumber($fileNumber = null)
    {
        $this->fileNumber = $fileNumber;
    
        return $this;
    }

    /**
     * Get fileNumber.
     *
     * @return string|null
     */
    public function getFileNumber()
    {
        return $this->fileNumber;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return Admission
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
     * @return Admission
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
     * Set dateAdmission.
     *
     * @param \DateTime|null $dateAdmission
     *
     * @return Admission
     */
    public function setDateAdmission($dateAdmission = null)
    {
        $this->dateAdmission = $dateAdmission;
    
        return $this;
    }

    /**
     * Get dateAdmission.
     *
     * @return \DateTime|null
     */
    public function getDateAdmission()
    {
        return $this->dateAdmission;
    }

    /**
     * Set phoneNumber.
     *
     * @param string|null $phoneNumber
     *
     * @return Admission
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
     * Set feesPaid.
     *
     * @param string|null $feesPaid
     *
     * @return Admission
     */
    public function setFeesPaid($feesPaid = null)
    {
        $this->feesPaid = $feesPaid;
    
        return $this;
    }

    /**
     * Get feesPaid.
     *
     * @return string|null
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }

    /**
     * Set entranceType.
     *
     * @param string|null $entranceType
     *
     * @return Admission
     */
    public function setEntranceType($entranceType = null)
    {
        $this->entranceType = $entranceType;
    
        return $this;
    }

    /**
     * Get entranceType.
     *
     * @return string|null
     */
    public function getEntranceType()
    {
        return $this->entranceType;
    }

    /**
     * Set paymentDate.
     *
     * @param \DateTime|null $paymentDate
     *
     * @return Admission
     */
    public function setPaymentDate($paymentDate = null)
    {
        $this->paymentDate = $paymentDate;
    
        return $this;
    }

    /**
     * Get paymentDate.
     *
     * @return \DateTime|null
     */
    public function getPaymentDate()
    {
        return $this->paymentDate;
    }

    /**
     * Set academicYear.
     *
     * @param AcademicYear|null $academicYear
     *
     * @return Admission
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;
    
        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set classOfStudy.
     *
     * @param ClassOfStudy|null $classOfStudy
     *
     * @return Admission
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;
    
        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return ClassOfStudy|null
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set degree.
     *
     * @param Degree|null $degree
     *
     * @return Admission
     */
    public function setDegree(Degree $degree = null)
    {
        $this->degree = $degree;
    
        return $this;
    }

    /**
     * Get degree.
     *
     * @return Degree|null
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set prospectiveStudent.
     *
     * @param ProspectiveStudent|null $prospectiveStudent
     *
     * @return Admission
     */
    public function setProspectiveStudent(ProspectiveStudent $prospectiveStudent = null)
    {
        $this->prospectiveStudent = $prospectiveStudent;
    
        return $this;
    }

    /**
     * Get prospectiveStudent.
     *
     * @return ProspectiveStudent|null
     */
    public function getProspectiveStudent()
    {
        return $this->prospectiveStudent;
    }
}
