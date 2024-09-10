<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AdminRegistration
 *
 * @ORM\Table(name="admin_registration", uniqueConstraints={@ORM\UniqueConstraint(name="contrat_ID_UNIQUE", columns={"contrat_id"})}, indexes={@ORM\Index(name="fk_admin_registration_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_admin_registration_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_admin_registration_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class AdminRegistration
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
     * @ORM\Column(name="registering_date", type="datetime", nullable=true)
     */
    private $registeringDate;

    /**
     * @var string|null
     *
     * @ORM\Column(name="registration_status", type="string", length=10, nullable=true, options={"default"="DRAFT"})
     */
    private $registrationStatus = 'DRAFT';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="pv_registration_date", type="datetime", nullable=true, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $pvRegistrationDate = 'CURRENT_TIMESTAMP';

    /**
     * @var int
     *
     * @ORM\Column(name="academic_year_id", type="integer", nullable=false)
     */
    private $academicYearId;

    /**
     * @var float|null
     *
     * @ORM\Column(name="fees_dotation", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesDotation;

    /**
     * @var float|null
     *
     * @ORM\Column(name="fees_paid", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesPaid = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="student_id", type="integer", nullable=false)
     */
    private $studentId;

    /**
     * @var float|null
     *
     * @ORM\Column(name="fees_balance_from_previous_year", type="float", precision=10, scale=0, nullable=true)
     */
    private $feesBalanceFromPreviousYear = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="contrat_id", type="string", length=45, nullable=true)
     */
    private $contratId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="moratorium_autorization", type="string", length=45, nullable=true)
     */
    private $moratoriumAutorization;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="decision", type="string", length=45, nullable=true)
     */
    private $decision;

    /**
     * @var int|null
     *
     * @ORM\Column(name="isStudentRepeating", type="integer", nullable=true)
     */
    private $isstudentrepeating = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="school_certificate_reference_id", type="string", length=45, nullable=true)
     */
    private $schoolCertificateReferenceId;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="school_certificate_availability_status", type="boolean", nullable=true)
     */
    private $schoolCertificateAvailabilityStatus = '0';

    /**
     * @var \ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;



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
     * Set registeringDate.
     *
     * @param \DateTime|null $registeringDate
     *
     * @return AdminRegistration
     */
    public function setRegisteringDate($registeringDate = null)
    {
        $this->registeringDate = $registeringDate;

        return $this;
    }

    /**
     * Get registeringDate.
     *
     * @return \DateTime|null
     */
    public function getRegisteringDate()
    {
        return $this->registeringDate;
    }

    /**
     * Set registrationStatus.
     *
     * @param string|null $registrationStatus
     *
     * @return AdminRegistration
     */
    public function setRegistrationStatus($registrationStatus = null)
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }

    /**
     * Get registrationStatus.
     *
     * @return string|null
     */
    public function getRegistrationStatus()
    {
        return $this->registrationStatus;
    }

    /**
     * Set pvRegistrationDate.
     *
     * @param \DateTime|null $pvRegistrationDate
     *
     * @return AdminRegistration
     */
    public function setPvRegistrationDate($pvRegistrationDate = null)
    {
        $this->pvRegistrationDate = $pvRegistrationDate;

        return $this;
    }

    /**
     * Get pvRegistrationDate.
     *
     * @return \DateTime|null
     */
    public function getPvRegistrationDate()
    {
        return $this->pvRegistrationDate;
    }

    /**
     * Set academicYearId.
     *
     * @param int $academicYearId
     *
     * @return AdminRegistration
     */
    public function setAcademicYearId($academicYearId)
    {
        $this->academicYearId = $academicYearId;

        return $this;
    }

    /**
     * Get academicYearId.
     *
     * @return int
     */
    public function getAcademicYearId()
    {
        return $this->academicYearId;
    }

    /**
     * Set feesDotation.
     *
     * @param float|null $feesDotation
     *
     * @return AdminRegistration
     */
    public function setFeesDotation($feesDotation = null)
    {
        $this->feesDotation = $feesDotation;

        return $this;
    }

    /**
     * Get feesDotation.
     *
     * @return float|null
     */
    public function getFeesDotation()
    {
        return $this->feesDotation;
    }

    /**
     * Set feesPaid.
     *
     * @param float|null $feesPaid
     *
     * @return AdminRegistration
     */
    public function setFeesPaid($feesPaid = null)
    {
        $this->feesPaid = $feesPaid;

        return $this;
    }

    /**
     * Get feesPaid.
     *
     * @return float|null
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }

    /**
     * Set studentId.
     *
     * @param int $studentId
     *
     * @return AdminRegistration
     */
    public function setStudentId($studentId)
    {
        $this->studentId = $studentId;

        return $this;
    }

    /**
     * Get studentId.
     *
     * @return int
     */
    public function getStudentId()
    {
        return $this->studentId;
    }

    /**
     * Set feesBalanceFromPreviousYear.
     *
     * @param float|null $feesBalanceFromPreviousYear
     *
     * @return AdminRegistration
     */
    public function setFeesBalanceFromPreviousYear($feesBalanceFromPreviousYear = null)
    {
        $this->feesBalanceFromPreviousYear = $feesBalanceFromPreviousYear;

        return $this;
    }

    /**
     * Get feesBalanceFromPreviousYear.
     *
     * @return float|null
     */
    public function getFeesBalanceFromPreviousYear()
    {
        return $this->feesBalanceFromPreviousYear;
    }

    /**
     * Set contratId.
     *
     * @param string|null $contratId
     *
     * @return AdminRegistration
     */
    public function setContratId($contratId = null)
    {
        $this->contratId = $contratId;

        return $this;
    }

    /**
     * Get contratId.
     *
     * @return string|null
     */
    public function getContratId()
    {
        return $this->contratId;
    }

    /**
     * Set moratoriumAutorization.
     *
     * @param string|null $moratoriumAutorization
     *
     * @return AdminRegistration
     */
    public function setMoratoriumAutorization($moratoriumAutorization = null)
    {
        $this->moratoriumAutorization = $moratoriumAutorization;

        return $this;
    }

    /**
     * Get moratoriumAutorization.
     *
     * @return string|null
     */
    public function getMoratoriumAutorization()
    {
        return $this->moratoriumAutorization;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return AdminRegistration
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
     * Set decision.
     *
     * @param string|null $decision
     *
     * @return AdminRegistration
     */
    public function setDecision($decision = null)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get decision.
     *
     * @return string|null
     */
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Set isstudentrepeating.
     *
     * @param int|null $isstudentrepeating
     *
     * @return AdminRegistration
     */
    public function setIsstudentrepeating($isstudentrepeating = null)
    {
        $this->isstudentrepeating = $isstudentrepeating;

        return $this;
    }

    /**
     * Get isstudentrepeating.
     *
     * @return int|null
     */
    public function getIsstudentrepeating()
    {
        return $this->isstudentrepeating;
    }

    /**
     * Set schoolCertificateReferenceId.
     *
     * @param string|null $schoolCertificateReferenceId
     *
     * @return AdminRegistration
     */
    public function setSchoolCertificateReferenceId($schoolCertificateReferenceId = null)
    {
        $this->schoolCertificateReferenceId = $schoolCertificateReferenceId;

        return $this;
    }

    /**
     * Get schoolCertificateReferenceId.
     *
     * @return string|null
     */
    public function getSchoolCertificateReferenceId()
    {
        return $this->schoolCertificateReferenceId;
    }

    /**
     * Set schoolCertificateAvailabilityStatus.
     *
     * @param bool|null $schoolCertificateAvailabilityStatus
     *
     * @return AdminRegistration
     */
    public function setSchoolCertificateAvailabilityStatus($schoolCertificateAvailabilityStatus = null)
    {
        $this->schoolCertificateAvailabilityStatus = $schoolCertificateAvailabilityStatus;

        return $this;
    }

    /**
     * Get schoolCertificateAvailabilityStatus.
     *
     * @return bool|null
     */
    public function getSchoolCertificateAvailabilityStatus()
    {
        return $this->schoolCertificateAvailabilityStatus;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return AdminRegistration
     */
    public function setClassOfStudy(\ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy.
     *
     * @return \ClassOfStudy|null
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }
}
