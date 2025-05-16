<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CursusAcademique
 *
 * @ORM\Table(name="cursus_academique", indexes={@ORM\Index(name="fk_cursus_academique_prospective_student1_idx", columns={"prospective_student_id"})})
 * @ORM\Entity
 */
class CursusAcademique
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
     * @ORM\Column(name="annee", type="string", length=45, nullable=true)
     */
    private $annee;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etablissement", type="string", length=45, nullable=true)
     */
    private $etablissement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="diplome", type="string", length=45, nullable=true)
     */
    private $diplome;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mention", type="string", length=45, nullable=true)
     */
    private $mention;

    /**
     * @var \ProspectiveStudent
     *
     * @ORM\ManyToOne(targetEntity="ProspectiveStudent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prospective_student_id", referencedColumnName="id")
     * })
     */
    private $prospectiveStudent;



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
     * Set annee.
     *
     * @param string|null $annee
     *
     * @return CursusAcademique
     */
    public function setAnnee($annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee.
     *
     * @return string|null
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set etablissement.
     *
     * @param string|null $etablissement
     *
     * @return CursusAcademique
     */
    public function setEtablissement($etablissement = null)
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement.
     *
     * @return string|null
     */
    public function getEtablissement()
    {
        return $this->etablissement;
    }

    /**
     * Set diplome.
     *
     * @param string|null $diplome
     *
     * @return CursusAcademique
     */
    public function setDiplome($diplome = null)
    {
        $this->diplome = $diplome;

        return $this;
    }

    /**
     * Get diplome.
     *
     * @return string|null
     */
    public function getDiplome()
    {
        return $this->diplome;
    }

    /**
     * Set mention.
     *
     * @param string|null $mention
     *
     * @return CursusAcademique
     */
    public function setMention($mention = null)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention.
     *
     * @return string|null
     */
    public function getMention()
    {
        return $this->mention;
    }

    /**
     * Set prospectiveStudent.
     *
     * @param \ProspectiveStudent|null $prospectiveStudent
     *
     * @return CursusAcademique
     */
    public function setProspectiveStudent(\ProspectiveStudent $prospectiveStudent = null)
    {
        $this->prospectiveStudent = $prospectiveStudent;

        return $this;
    }

    /**
     * Get prospectiveStudent.
     *
     * @return \ProspectiveStudent|null
     */
    public function getProspectiveStudent()
    {
        return $this->prospectiveStudent;
    }
}
