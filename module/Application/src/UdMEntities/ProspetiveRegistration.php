<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ProspetiveRegistration
 *
 * @ORM\Table(name="prospetive_registration", indexes={@ORM\Index(name="fk_prospetive_registration_prospective_student1_idx", columns={"prospective_student_id"}), @ORM\Index(name="fk_prospetive_registration_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class ProspetiveRegistration
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
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string|null
     *
     * @ORM\Column(name="registration_decision", type="string", length=45, nullable=true)
     */
    private $registrationDecision;

    /**
     * @var string|null
     *
     * @ORM\Column(name="payment_proof_path", type="string", length=255, nullable=true)
     */
    private $paymentProofPath;

    /**
     * @var string|null
     *
     * @ORM\Column(name="num_dossier", type="string", length=45, nullable=true)
     */
    private $numDossier;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type_admission", type="string", length=45, nullable=true)
     */
    private $typeAdmission;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_1", type="string", length=45, nullable=true)
     */
    private $choixFormation1;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_2", type="string", length=45, nullable=true)
     */
    private $choixFormation2;

    /**
     * @var string|null
     *
     * @ORM\Column(name="choix_formation_3", type="string", length=45, nullable=true)
     */
    private $choixFormation3;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prospetive_registrationcol", type="string", length=45, nullable=true)
     */
    private $prospetiveRegistrationcol;

    /**
     * @var \AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return ProspetiveRegistration
     */
    public function setDate($date = null)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set registrationDecision.
     *
     * @param string|null $registrationDecision
     *
     * @return ProspetiveRegistration
     */
    public function setRegistrationDecision($registrationDecision = null)
    {
        $this->registrationDecision = $registrationDecision;

        return $this;
    }

    /**
     * Get registrationDecision.
     *
     * @return string|null
     */
    public function getRegistrationDecision()
    {
        return $this->registrationDecision;
    }

    /**
     * Set paymentProofPath.
     *
     * @param string|null $paymentProofPath
     *
     * @return ProspetiveRegistration
     */
    public function setPaymentProofPath($paymentProofPath = null)
    {
        $this->paymentProofPath = $paymentProofPath;

        return $this;
    }

    /**
     * Get paymentProofPath.
     *
     * @return string|null
     */
    public function getPaymentProofPath()
    {
        return $this->paymentProofPath;
    }

    /**
     * Set numDossier.
     *
     * @param string|null $numDossier
     *
     * @return ProspetiveRegistration
     */
    public function setNumDossier($numDossier = null)
    {
        $this->numDossier = $numDossier;

        return $this;
    }

    /**
     * Get numDossier.
     *
     * @return string|null
     */
    public function getNumDossier()
    {
        return $this->numDossier;
    }

    /**
     * Set typeAdmission.
     *
     * @param string|null $typeAdmission
     *
     * @return ProspetiveRegistration
     */
    public function setTypeAdmission($typeAdmission = null)
    {
        $this->typeAdmission = $typeAdmission;

        return $this;
    }

    /**
     * Get typeAdmission.
     *
     * @return string|null
     */
    public function getTypeAdmission()
    {
        return $this->typeAdmission;
    }

    /**
     * Set status.
     *
     * @param string|null $status
     *
     * @return ProspetiveRegistration
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set choixFormation1.
     *
     * @param string|null $choixFormation1
     *
     * @return ProspetiveRegistration
     */
    public function setChoixFormation1($choixFormation1 = null)
    {
        $this->choixFormation1 = $choixFormation1;

        return $this;
    }

    /**
     * Get choixFormation1.
     *
     * @return string|null
     */
    public function getChoixFormation1()
    {
        return $this->choixFormation1;
    }

    /**
     * Set choixFormation2.
     *
     * @param string|null $choixFormation2
     *
     * @return ProspetiveRegistration
     */
    public function setChoixFormation2($choixFormation2 = null)
    {
        $this->choixFormation2 = $choixFormation2;

        return $this;
    }

    /**
     * Get choixFormation2.
     *
     * @return string|null
     */
    public function getChoixFormation2()
    {
        return $this->choixFormation2;
    }

    /**
     * Set choixFormation3.
     *
     * @param string|null $choixFormation3
     *
     * @return ProspetiveRegistration
     */
    public function setChoixFormation3($choixFormation3 = null)
    {
        $this->choixFormation3 = $choixFormation3;

        return $this;
    }

    /**
     * Get choixFormation3.
     *
     * @return string|null
     */
    public function getChoixFormation3()
    {
        return $this->choixFormation3;
    }

    /**
     * Set prospetiveRegistrationcol.
     *
     * @param string|null $prospetiveRegistrationcol
     *
     * @return ProspetiveRegistration
     */
    public function setProspetiveRegistrationcol($prospetiveRegistrationcol = null)
    {
        $this->prospetiveRegistrationcol = $prospetiveRegistrationcol;

        return $this;
    }

    /**
     * Get prospetiveRegistrationcol.
     *
     * @return string|null
     */
    public function getProspetiveRegistrationcol()
    {
        return $this->prospetiveRegistrationcol;
    }

    /**
     * Set academicYear.
     *
     * @param \AcademicYear|null $academicYear
     *
     * @return ProspetiveRegistration
     */
    public function setAcademicYear(\AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return \AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set prospectiveStudent.
     *
     * @param \ProspectiveStudent|null $prospectiveStudent
     *
     * @return ProspetiveRegistration
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
}
