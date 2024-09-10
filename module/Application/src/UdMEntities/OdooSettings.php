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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=45, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
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
     * Get url.
     *
     * @return string
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
}
