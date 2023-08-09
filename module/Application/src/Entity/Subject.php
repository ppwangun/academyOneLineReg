<?php

namespace Application\Entity;

use Application\Entity\TeachingUnit;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subject
 *
 * @ORM\Table(name="subject", uniqueConstraints={@ORM\UniqueConstraint(name="subject_codel_UNIQUE", columns={"subject_code"})}, indexes={@ORM\Index(name="fk_subject_teaching_unit1_idx", columns={"teaching_unit_id"})})
 * @ORM\Entity
 */
class Subject
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject_code", type="string", length=45, nullable=true)
     */
    private $subjectCode;

    /**
     * @var string
     *
     * @ORM\Column(name="subject_name", type="string", length=45, nullable=true)
     */
    private $subjectName;

    /**
     * @var float
     *
     * @ORM\Column(name="poids", type="string", length=45, nullable=true)
     */
    private $poids;

    /**
     * @var TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;



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
     * Set subjectCode
     *
     * @param string $subjectCode
     *
     * @return Subject
     */
    public function setSubjectCode($subjectCode)
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    /**
     * Get subjectCode
     *
     * @return string
     */
    public function getSubjectCode()
    {
        return $this->subjectCode;
    }

    /**
     * Set subjectName
     *
     * @param string $subjectName
     *
     * @return Subject
     */
    public function setSubjectName($subjectName)
    {
        $this->subjectName = $subjectName;

        return $this;
    }

    /**
     * Get subjectName
     *
     * @return string
     */
    public function getSubjectName()
    {
        return $this->subjectName;
    }

    /**
     * Set poids
     *
     * @param string $poids
     *
     * @return Subject
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;

        return $this;
    }

    /**
     * Get poids
     *
     * @return float
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set teachingUnit
     *
     * @param TeachingUnit $teachingUnit
     *
     * @return Subject
     */
    public function setTeachingUnit(TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit
     *
     * @return TeachingUnit
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }

  
}
