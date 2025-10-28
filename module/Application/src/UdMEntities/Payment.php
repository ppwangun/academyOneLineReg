<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payment", indexes={@ORM\Index(name="fk_payment_admin_registration1_idx", columns={"admin_registration_id"}), @ORM\Index(name="fk_payment_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class Payment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="amount", type="string", length=45, nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_transaction", type="datetime", nullable=true)
     */
    private $dateTransaction;

    /**
     * @var int|null
     *
     * @ORM\Column(name="from_balance", type="integer", nullable=true)
     */
    private $fromBalance = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="mobile_payment_id", type="string", length=45, nullable=true)
     */
    private $mobilePaymentId;

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
     * @var \AcademicYear
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;



    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Payment
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set amount.
     *
     * @param string|null $amount
     *
     * @return Payment
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return string|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set dateTransaction.
     *
     * @param \DateTime|null $dateTransaction
     *
     * @return Payment
     */
    public function setDateTransaction($dateTransaction = null)
    {
        $this->dateTransaction = $dateTransaction;

        return $this;
    }

    /**
     * Get dateTransaction.
     *
     * @return \DateTime|null
     */
    public function getDateTransaction()
    {
        return $this->dateTransaction;
    }

    /**
     * Set fromBalance.
     *
     * @param int|null $fromBalance
     *
     * @return Payment
     */
    public function setFromBalance($fromBalance = null)
    {
        $this->fromBalance = $fromBalance;

        return $this;
    }

    /**
     * Get fromBalance.
     *
     * @return int|null
     */
    public function getFromBalance()
    {
        return $this->fromBalance;
    }

    /**
     * Set mobilePaymentId.
     *
     * @param string|null $mobilePaymentId
     *
     * @return Payment
     */
    public function setMobilePaymentId($mobilePaymentId = null)
    {
        $this->mobilePaymentId = $mobilePaymentId;

        return $this;
    }

    /**
     * Get mobilePaymentId.
     *
     * @return string|null
     */
    public function getMobilePaymentId()
    {
        return $this->mobilePaymentId;
    }

    /**
     * Set adminRegistration.
     *
     * @param \AdminRegistration|null $adminRegistration
     *
     * @return Payment
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
     * Set academicYear.
     *
     * @param \AcademicYear $academicYear
     *
     * @return Payment
     */
    public function setAcademicYear(\AcademicYear $academicYear)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return \AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }
}
