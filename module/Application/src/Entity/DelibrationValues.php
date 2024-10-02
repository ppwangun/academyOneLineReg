<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Deliberation;

/**
 * DelibrationValues
 *
 * @ORM\Table(name="delibration_values", indexes={@ORM\Index(name="fk_delibration_values_deliberation1_idx", columns={"deliberation_id"})})
 * @ORM\Entity
 */
class DelibrationValues
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
     * @var float
     *
     * @ORM\Column(name="minsur20", type="float", precision=10, scale=0, nullable=true)
     */
    private $minsur20;

    /**
     * @var float
     *
     * @ORM\Column(name="minsur100", type="float", precision=10, scale=0, nullable=true)
     */
    private $minsur100;

    /**
     * @var float
     *
     * @ORM\Column(name="maxsur20", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxsur20;

    /**
     * @var float
     *
     * @ORM\Column(name="maxsur100", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxsur100;

    /**
     * @var float
     *
     * @ORM\Column(name="deliberationValue", type="float", precision=10, scale=0, nullable=true)
     */
    private $deliberationvalue;

    /**
     * @var Deliberation
     *
     * @ORM\ManyToOne(targetEntity="Deliberation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deliberation_id", referencedColumnName="id")
     * })
     */
    private $deliberation;



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
     * Set minsur20
     *
     * @param float $minsur20
     *
     * @return DelibrationValues
     */
    public function setMinsur20($minsur20)
    {
        $this->minsur20 = $minsur20;

        return $this;
    }

    /**
     * Get minsur20
     *
     * @return float
     */
    public function getMinsur20()
    {
        return $this->minsur20;
    }

    /**
     * Set minsur100
     *
     * @param float $minsur100
     *
     * @return DelibrationValues
     */
    public function setMinsur100($minsur100)
    {
        $this->minsur100 = $minsur100;

        return $this;
    }

    /**
     * Get minsur100
     *
     * @return float
     */
    public function getMinsur100()
    {
        return $this->minsur100;
    }

    /**
     * Set maxsur20
     *
     * @param float $maxsur20
     *
     * @return DelibrationValues
     */
    public function setMaxsur20($maxsur20)
    {
        $this->maxsur20 = $maxsur20;

        return $this;
    }

    /**
     * Get maxsur20
     *
     * @return float
     */
    public function getMaxsur20()
    {
        return $this->maxsur20;
    }

    /**
     * Set maxsur100
     *
     * @param float $maxsur100
     *
     * @return DelibrationValues
     */
    public function setMaxsur100($maxsur100)
    {
        $this->maxsur100 = $maxsur100;

        return $this;
    }

    /**
     * Get maxsur100
     *
     * @return float
     */
    public function getMaxsur100()
    {
        return $this->maxsur100;
    }

    /**
     * Set deliberationvalue
     *
     * @param float $deliberationvalue
     *
     * @return DelibrationValues
     */
    public function setDeliberationvalue($deliberationvalue)
    {
        $this->deliberationvalue = $deliberationvalue;

        return $this;
    }

    /**
     * Get deliberationvalue
     *
     * @return float
     */
    public function getDeliberationvalue()
    {
        return $this->deliberationvalue;
    }

    /**
     * Set deliberation
     *
     * @param Deliberation $deliberation
     *
     * @return DelibrationValues
     */
    public function setDeliberation(Deliberation $deliberation = null)
    {
        $this->deliberation = $deliberation;

        return $this;
    }

    /**
     * Get deliberation
     *
     * @return Deliberation
     */
    public function getDeliberation()
    {
        return $this->deliberation;
    }
}
