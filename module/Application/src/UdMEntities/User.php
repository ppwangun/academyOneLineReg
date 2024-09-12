<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pwd_reset_token", type="string", length=45, nullable=true)
     */
    private $pwdResetToken;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="pwd_reset_token_creation_date", type="datetime", nullable=true)
     */
    private $pwdResetTokenCreationDate;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="first_connection", type="boolean", nullable=true, options={"default"="1"})
     */
    private $firstConnection = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="connected_status", type="boolean", nullable=true)
     */
    private $connectedStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last_connected_date", type="string", length=45, nullable=true)
     */
    private $lastConnectedDate;



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
     * Set email.
     *
     * @param string|null $email
     *
     * @return User
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return User
     */
    public function setNom($nom = null)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom.
     *
     * @param string|null $prenom
     *
     * @return User
     */
    public function setPrenom($prenom = null)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return User
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
     * Set status.
     *
     * @param string|null $status
     *
     * @return User
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime|null $dateCreated
     *
     * @return User
     */
    public function setDateCreated($dateCreated = null)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime|null
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set pwdResetToken.
     *
     * @param string|null $pwdResetToken
     *
     * @return User
     */
    public function setPwdResetToken($pwdResetToken = null)
    {
        $this->pwdResetToken = $pwdResetToken;

        return $this;
    }

    /**
     * Get pwdResetToken.
     *
     * @return string|null
     */
    public function getPwdResetToken()
    {
        return $this->pwdResetToken;
    }

    /**
     * Set pwdResetTokenCreationDate.
     *
     * @param \DateTime|null $pwdResetTokenCreationDate
     *
     * @return User
     */
    public function setPwdResetTokenCreationDate($pwdResetTokenCreationDate = null)
    {
        $this->pwdResetTokenCreationDate = $pwdResetTokenCreationDate;

        return $this;
    }

    /**
     * Get pwdResetTokenCreationDate.
     *
     * @return \DateTime|null
     */
    public function getPwdResetTokenCreationDate()
    {
        return $this->pwdResetTokenCreationDate;
    }

    /**
     * Set firstConnection.
     *
     * @param bool|null $firstConnection
     *
     * @return User
     */
    public function setFirstConnection($firstConnection = null)
    {
        $this->firstConnection = $firstConnection;

        return $this;
    }

    /**
     * Get firstConnection.
     *
     * @return bool|null
     */
    public function getFirstConnection()
    {
        return $this->firstConnection;
    }

    /**
     * Set connectedStatus.
     *
     * @param bool|null $connectedStatus
     *
     * @return User
     */
    public function setConnectedStatus($connectedStatus = null)
    {
        $this->connectedStatus = $connectedStatus;

        return $this;
    }

    /**
     * Get connectedStatus.
     *
     * @return bool|null
     */
    public function getConnectedStatus()
    {
        return $this->connectedStatus;
    }

    /**
     * Set lastConnectedDate.
     *
     * @param string|null $lastConnectedDate
     *
     * @return User
     */
    public function setLastConnectedDate($lastConnectedDate = null)
    {
        $this->lastConnectedDate = $lastConnectedDate;

        return $this;
    }

    /**
     * Get lastConnectedDate.
     *
     * @return string|null
     */
    public function getLastConnectedDate()
    {
        return $this->lastConnectedDate;
    }
}
