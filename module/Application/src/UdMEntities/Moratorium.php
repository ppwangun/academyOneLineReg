<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Moratorium
 *
 * @ORM\Table(name="moratorium", indexes={@ORM\Index(name="fk_moratoire_admin_registration1_idx", columns={"admin_registration_id"})})
 * @ORM\Entity
 */
class Moratorium
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="date_of_validity", type="string", length=45, nullable=true)
     */
    private $dateOfValidity;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"unsigned"=true})
     */
    private $status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="admin_registration_id", type="integer", nullable=true)
     */
    private $adminRegistrationId;

    /**
     * @var \AdminRegistration
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="AdminRegistration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id", referencedColumnName="id")
     * })
     */
    private $id;



    /**
     * Set dateOfValidity.
     *
     * @param string|null $dateOfValidity
     *
     * @return Moratorium
     */
    public function setDateOfValidity($dateOfValidity = null)
    {
        $this->dateOfValidity = $dateOfValidity;

        return $this;
    }

    /**
     * Get dateOfValidity.
     *
     * @return string|null
     */
    public function getDateOfValidity()
    {
        return $this->dateOfValidity;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return Moratorium
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
     * Set adminRegistrationId.
     *
     * @param int|null $adminRegistrationId
     *
     * @return Moratorium
     */
    public function setAdminRegistrationId($adminRegistrationId = null)
    {
        $this->adminRegistrationId = $adminRegistrationId;

        return $this;
    }

    /**
     * Get adminRegistrationId.
     *
     * @return int|null
     */
    public function getAdminRegistrationId()
    {
        return $this->adminRegistrationId;
    }

    /**
     * Set id.
     *
     * @param \AdminRegistration $id
     *
     * @return Moratorium
     */
    public function setId(\AdminRegistration $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return \AdminRegistration
     */
    public function getId()
    {
        return $this->id;
    }
}
