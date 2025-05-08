<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject", indexes={@ORM\Index(name="fk_subject_teaching_unit1_idx", columns={"teaching_unit_id"})})
 * @ORM\Entity
 */
class Subject
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
     * @ORM\Column(name="subject_code", type="string", length=45, nullable=true)
     */
    private $subjectCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="subject_name", type="string", length=255, nullable=true)
     */
    private $subjectName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nbre_ECTS", type="string", length=45, nullable=true)
     */
    private $nbreEcts;

    /**
     * @var float|null
     *
     * @ORM\Column(name="poids", type="float", precision=10, scale=0, nullable=true)
     */
    private $poids;

    /**
     * @var \TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;



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
     * Set subjectCode.
     *
     * @param string|null $subjectCode
     *
     * @return Subject
     */
    public function setSubjectCode($subjectCode = null)
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    /**
     * Get subjectCode.
     *
     * @return string|null
     */
    public function getSubjectCode()
    {
        return $this->subjectCode;
    }

    /**
     * Set subjectName.
     *
     * @param string|null $subjectName
     *
     * @return Subject
     */
    public function setSubjectName($subjectName = null)
    {
        $this->subjectName = $subjectName;

        return $this;
    }

    /**
     * Get subjectName.
     *
     * @return string|null
     */
    public function getSubjectName()
    {
        return $this->subjectName;
    }

    /**
     * Set nbreEcts.
     *
     * @param string|null $nbreEcts
     *
     * @return Subject
     */
    public function setNbreEcts($nbreEcts = null)
    {
        $this->nbreEcts = $nbreEcts;

        return $this;
    }

    /**
     * Get nbreEcts.
     *
     * @return string|null
     */
    public function getNbreEcts()
    {
        return $this->nbreEcts;
    }

    /**
     * Set poids.
     *
     * @param float|null $poids
     *
     * @return Subject
     */
    public function setPoids($poids = null)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids.
     *
     * @return float|null
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set teachingUnit.
     *
     * @param \TeachingUnit|null $teachingUnit
     *
     * @return Subject
     */
    public function setTeachingUnit(\TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit.
     *
     * @return \TeachingUnit|null
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }
}
