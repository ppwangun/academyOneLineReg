<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Deliberation;

use Application\Entity\ClassOfStudy;

/**
 * DelibrationCondition
 *
 * @ORM\Table(name="delibration_condition", indexes={@ORM\Index(name="fk_delibration_values_deliberation1_idx", columns={"deliberation_id"})})
 * @ORM\Entity
 */
class DelibrationCondition
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
     * @var ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;




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
     * Set condition
     *
     * @param string $condition
     *
     * @return DelibrationCondition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * Get condition
     *
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
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
    
/**
     * Set classOfStudy
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return Deliberation
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }    
}
