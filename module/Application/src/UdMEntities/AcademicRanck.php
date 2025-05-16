<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AcademicRanck
 *
 * @ORM\Table(name="academic_ranck")
 * @ORM\Entity
 */
class AcademicRanck
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
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var float|null
     *
     * @ORM\Column(name="payment_rate", type="float", precision=10, scale=0, nullable=true)
     */
    private $paymentRate;



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
     * Set name.
     *
     * @param string|null $name
     *
     * @return AcademicRanck
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
     * Set code.
     *
     * @param string|null $code
     *
     * @return AcademicRanck
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
     * Set paymentRate.
     *
     * @param float|null $paymentRate
     *
     * @return AcademicRanck
     */
    public function setPaymentRate($paymentRate = null)
    {
        $this->paymentRate = $paymentRate;

        return $this;
    }

    /**
     * Get paymentRate.
     *
     * @return float|null
     */
    public function getPaymentRate()
    {
        return $this->paymentRate;
    }
}
