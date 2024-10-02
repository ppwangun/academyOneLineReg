<?php



/**
 * Moratorium
 */
class Moratorium
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $dateOfValidity;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \AdminRegistration
     */
    private $adminRegistration;


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
     * Set dateOfValidity
     *
     * @param string $dateOfValidity
     *
     * @return Moratorium
     */
    public function setDateOfValidity($dateOfValidity)
    {
        $this->dateOfValidity = $dateOfValidity;

        return $this;
    }

    /**
     * Get dateOfValidity
     *
     * @return string
     */
    public function getDateOfValidity()
    {
        return $this->dateOfValidity;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Moratorium
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
     * Set adminRegistration
     *
     * @param \AdminRegistration $adminRegistration
     *
     * @return Moratorium
     */
    public function setAdminRegistration(\AdminRegistration $adminRegistration = null)
    {
        $this->adminRegistration = $adminRegistration;

        return $this;
    }

    /**
     * Get adminRegistration
     *
     * @return \AdminRegistration
     */
    public function getAdminRegistration()
    {
        return $this->adminRegistration;
    }
}

