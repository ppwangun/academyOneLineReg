<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Degree;

/**
 * Cycle
 *
 * @ORM\Table(name="training_curriculum", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})}, indexes={@ORM\Index(name="fk_cycle_degree1_idx", columns={"degree_id"})})
 * @ORM\Entity
 */
class TrainingCurriculum
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=false)
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="cycle_level", type="integer", nullable=true)
     */
    private $cycleLevel;

    /**
     * @var Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree", inversedBy="degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;



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
     * Set name
     *
     * @param string $name
     *
     * @return Cycle
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Cycle
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Cycle
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

/**
     * Set cycleLevel
     *
     * @param integer $cycleLevel
     *
     * @return TrainingCurriculum
     */
    public function setCycleLevel($cycleLevel)
    {
        $this->cycleLevel = $cycleLevel;

        return $this;
    }

    /**
     * Get cycleLevel
     *
     * @return integer
     */
    public function getCycleLevel()
    {
        return $this->cycleLevel;
    }
   
    /**
     * Set degree
     *
     * @param Degree $degree
     *
     * @return Cycle
     */
    public function setDegree(Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree
     *
     * @return Degree
     */
    public function getDegree()
    {
        return $this->degree;
    }

  
}
