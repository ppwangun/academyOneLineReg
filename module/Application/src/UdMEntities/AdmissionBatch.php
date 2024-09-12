<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AdmissionBatch
 *
 * @ORM\Table(name="admission_batch", indexes={@ORM\Index(name="fk_admission_batch_admission1_idx", columns={"admission_id"}), @ORM\Index(name="fk_admission_batch_student1_idx", columns={"student_id"}), @ORM\Index(name="fk_admission_batch_admin_registration1_idx", columns={"admin_registration_id"})})
 * @ORM\Entity
 */
class AdmissionBatch
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="batch_name", type="string", length=255, nullable=true)
     */
    private $batchName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="admission_date", type="string", length=45, nullable=true)
     */
    private $admissionDate;

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
     * @var \AdminRegistration
     *
     * @ORM\ManyToOne(targetEntity="AdminRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admin_registration_id", referencedColumnName="id")
     * })
     */
    private $adminRegistration;

    /**
     * @var \Admission
     *
     * @ORM\ManyToOne(targetEntity="Admission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="admission_id", referencedColumnName="id")
     * })
     */
    private $admission;



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
     * Set code.
     *
     * @param string|null $code
     *
     * @return AdmissionBatch
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set batchName.
     *
     * @param string|null $batchName
     *
     * @return AdmissionBatch
     */
    public function setBatchName($batchName = null)
    {
        $this->batchName = $batchName;

        return $this;
    }

    /**
     * Get batchName.
     *
     * @return string|null
     */
    public function getBatchName()
    {
        return $this->batchName;
    }

    /**
     * Set admissionDate.
     *
     * @param string|null $admissionDate
     *
     * @return AdmissionBatch
     */
    public function setAdmissionDate($admissionDate = null)
    {
        $this->admissionDate = $admissionDate;

        return $this;
    }

    /**
     * Get admissionDate.
     *
     * @return string|null
     */
    public function getAdmissionDate()
    {
        return $this->admissionDate;
    }

    /**
     * Set student.
     *
     * @param \Student|null $student
     *
     * @return AdmissionBatch
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

    /**
     * Set adminRegistration.
     *
     * @param \AdminRegistration|null $adminRegistration
     *
     * @return AdmissionBatch
     */
    public function setAdminRegistration(\AdminRegistration $adminRegistration = null)
    {
        $this->adminRegistration = $adminRegistration;

        return $this;
    }

    /**
     * Get adminRegistration.
     *
     * @return \AdminRegistration|null
     */
    public function getAdminRegistration()
    {
        return $this->adminRegistration;
    }

    /**
     * Set admission.
     *
     * @param \Admission|null $admission
     *
     * @return AdmissionBatch
     */
    public function setAdmission(\Admission $admission = null)
    {
        $this->admission = $admission;

        return $this;
    }

    /**
     * Get admission.
     *
     * @return \Admission|null
     */
    public function getAdmission()
    {
        return $this->admission;
    }
}
