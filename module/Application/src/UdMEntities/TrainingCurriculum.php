<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TrainingCurriculum
 *
 * @ORM\Table(name="training_curriculum", indexes={@ORM\Index(name="fk_cycle_degree1_idx", columns={"degree_id"})})
 * @ORM\Entity
 */
class TrainingCurriculum
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var int|null
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var int|null
     *
     * @ORM\Column(name="cycle_level", type="integer", nullable=true)
     */
    private $cycleLevel;

    /**
     * @var \Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;



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
     * Set name.
     *
     * @param string|null $name
     *
     * @return TrainingCurriculum
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code.
     *
     * @param string|null $code
     *
     * @return TrainingCurriculum
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set duration.
     *
     * @param int|null $duration
     *
     * @return TrainingCurriculum
     */
    public function setDuration($duration = null)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration.
     *
     * @return int|null
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set cycleLevel.
     *
     * @param int|null $cycleLevel
     *
     * @return TrainingCurriculum
     */
    public function setCycleLevel($cycleLevel = null)
    {
        $this->cycleLevel = $cycleLevel;

        return $this;
    }

    /**
     * Get cycleLevel.
     *
     * @return int|null
     */
    public function getCycleLevel()
    {
        return $this->cycleLevel;
    }

    /**
     * Set degree.
     *
     * @param \Degree|null $degree
     *
     * @return TrainingCurriculum
     */
    public function setDegree(\Degree $degree = null)
    {
        $this->degree = $degree;

        return $this;
    }

    /**
     * Get degree.
     *
     * @return \Degree|null
     */
    public function getDegree()
    {
        return $this->degree;
    }
}
