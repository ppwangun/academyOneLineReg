<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * ContractFollowUp
 *
 * @ORM\Table(name="contract_follow_up", indexes={@ORM\Index(name="fk_contract_follow_up_contract1_idx", columns={"contract_id"})})
 * @ORM\Entity
 */
class ContractFollowUp
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
     * @ORM\Column(name="hr_volume", type="float", precision=10, scale=0, nullable=true)
     */
    private $hrVolume;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \Contract
     *
     * @ORM\ManyToOne(targetEntity="Contract")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     * })
     */
    private $contract;



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
     * Set hrVolume.
     *
     * @param float|null $hrVolume
     *
     * @return ContractFollowUp
     */
    public function setHrVolume($hrVolume = null)
    {
        $this->hrVolume = $hrVolume;
    
        return $this;
    }

    /**
     * Get hrVolume.
     *
     * @return float|null
     */
    public function getHrVolume()
    {
        return $this->hrVolume;
    }

    /**
     * Set date.
     *
     * @param \DateTime|null $date
     *
     * @return ContractFollowUp
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
     * Set contract.
     *
     * @param \Contract|null $contract
     *
     * @return ContractFollowUp
     */
    public function setContract(\Contract $contract = null)
    {
        $this->contract = $contract;
    
        return $this;
    }

    /**
     * Get contract.
     *
     * @return \Contract|null
     */
    public function getContract()
    {
        return $this->contract;
    }
}
