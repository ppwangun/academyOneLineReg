<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolEmployee
 *
 * @ORM\Table(name="school_employee")
 * @ORM\Entity
 */
class SchoolEmployee
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
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=45, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="date_naissance", type="string", length=45, nullable=true)
     */
    private $dateNaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieu_naissance", type="string", length=45, nullable=true)
     */
    private $lieuNaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="num_tel", type="string", length=45, nullable=true)
     */
    private $numTel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email;

    /**
     * @var string|null
     *
     * @ORM\Column(name="num_whatsapp", type="string", length=45, nullable=true)
     */
    private $numWhatsapp;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse;



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
     * Set nom.
     *
     * @param string|null $nom
     *
     * @return SchoolEmployee
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
     * @return SchoolEmployee
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
     * Set dateNaissance.
     *
     * @param string|null $dateNaissance
     *
     * @return SchoolEmployee
     */
    public function setDateNaissance($dateNaissance = null)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance.
     *
     * @return string|null
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set lieuNaissance.
     *
     * @param string|null $lieuNaissance
     *
     * @return SchoolEmployee
     */
    public function setLieuNaissance($lieuNaissance = null)
    {
        $this->lieuNaissance = $lieuNaissance;

        return $this;
    }

    /**
     * Get lieuNaissance.
     *
     * @return string|null
     */
    public function getLieuNaissance()
    {
        return $this->lieuNaissance;
    }

    /**
     * Set numTel.
     *
     * @param string|null $numTel
     *
     * @return SchoolEmployee
     */
    public function setNumTel($numTel = null)
    {
        $this->numTel = $numTel;

        return $this;
    }

    /**
     * Get numTel.
     *
     * @return string|null
     */
    public function getNumTel()
    {
        return $this->numTel;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return SchoolEmployee
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
     * Set numWhatsapp.
     *
     * @param string|null $numWhatsapp
     *
     * @return SchoolEmployee
     */
    public function setNumWhatsapp($numWhatsapp = null)
    {
        $this->numWhatsapp = $numWhatsapp;

        return $this;
    }

    /**
     * Get numWhatsapp.
     *
     * @return string|null
     */
    public function getNumWhatsapp()
    {
        return $this->numWhatsapp;
    }

    /**
     * Set adresse.
     *
     * @param string|null $adresse
     *
     * @return SchoolEmployee
     */
    public function setAdresse($adresse = null)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse.
     *
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}
