<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Installment
 *
 * @ORM\Table(name="installment", indexes={@ORM\Index(name="fk_installment_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_installment_degree1_idx", columns={"degree_id"})})
 * @ORM\Entity
 */
class Installment
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
     * @var float|null
     *
     * @ORM\Column(name="amount", type="float", precision=10, scale=0, nullable=true)
     */
    private $amount;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="deadline", type="datetime", nullable=true)
     */
    private $deadline;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="fees_id", type="integer", nullable=false)
     */
    private $feesId;

    /**
     * @var int
     *
     * @ORM\Column(name="degree_id", type="integer", nullable=false)
     */
    private $degreeId;

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
     * Set amount.
     *
     * @param float|null $amount
     *
     * @return Installment
     */
    public function setAmount($amount = null)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set deadline.
     *
     * @param \DateTime|null $deadline
     *
     * @return Installment
     */
    public function setDeadline($deadline = null)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline.
     *
     * @return \DateTime|null
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Installment
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
     * Set feesId.
     *
     * @param int $feesId
     *
     * @return Installment
     */
    public function setFeesId($feesId)
    {
        $this->feesId = $feesId;

        return $this;
    }

    /**
     * Get feesId.
     *
     * @return int
     */
    public function getFeesId()
    {
        return $this->feesId;
    }

    /**
     * Set degreeId.
     *
     * @param int $degreeId
     *
     * @return Installment
     */
    public function setDegreeId($degreeId)
    {
        $this->degreeId = $degreeId;

        return $this;
    }

    /**
     * Get degreeId.
     *
     * @return int
     */
    public function getDegreeId()
    {
        return $this->degreeId;
    }

    /**
     * Set classOfStudy.
     *
     * @param \ClassOfStudy|null $classOfStudy
     *
     * @return Installment
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
