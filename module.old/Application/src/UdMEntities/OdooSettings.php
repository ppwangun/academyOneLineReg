<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * OdooSettings
 *
 * @ORM\Table(name="odoo_settings")
 * @ORM\Entity
 */
class OdooSettings
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
     * @ORM\Column(name="url", type="string", length=45, nullable=true)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="database_name", type="string", length=45, nullable=true)
     */
    private $databaseName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="login", type="string", length=45, nullable=true)
     */
    private $login;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=45, nullable=true)
     */
    private $password;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="activate_status", type="boolean", nullable=true)
     */
    private $activateStatus = '0';



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
     * Set url.
     *
     * @param string|null $url
     *
     * @return OdooSettings
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set databaseName.
     *
     * @param string|null $databaseName
     *
     * @return OdooSettings
     */
    public function setDatabaseName($databaseName = null)
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    /**
     * Get databaseName.
     *
     * @return string|null
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * Set login.
     *
     * @param string|null $login
     *
     * @return OdooSettings
     */
    public function setLogin($login = null)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login.
     *
     * @return string|null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return OdooSettings
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set activateStatus.
     *
     * @param bool|null $activateStatus
     *
     * @return OdooSettings
     */
    public function setActivateStatus($activateStatus = null)
    {
        $this->activateStatus = $activateStatus;

        return $this;
    }

    /**
     * Get activateStatus.
     *
     * @return bool|null
     */
    public function getActivateStatus()
    {
        return $this->activateStatus;
    }
}
