<?php
namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Deliberation
 *
 * @ORM\Table(name="deliberation")
 * @ORM\Entity
 */
class Deliberation
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
     * @ORM\Column(name="label", type="string",  nullable=true)
     */
    private $label;
    
    /**
     * @var string
     *
     * @ORM\Column(name="delibCondition", type="string",  nullable=true)
     */
    private $delibCondition;

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
     * Set label
     *
     * @param string $label
     *
     * @return DelibrationCondition
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
    
    /**
     * Set delibCondition
     *
     * @param string $delibCondition
     *
     * @return Delibration
     */
    public function setDelibCondition($delibCondition)
    {
        $this->delibCondition = $delibCondition;

        return $this;
    }

    /**
     * Get delibCondition
     *
     * @return string
     */
    public function getDelibCondition()
    {
        return $this->delibCondition;
    }
    
}
