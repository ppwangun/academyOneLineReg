<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExamType
 *
 * @ORM\Table(name="exam_type")
 * @ORM\Entity
 */
class ExamType
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
     * @ORM\Column(name="code", type="string", nullable=false)
     * 
     */
    private $code;



    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;


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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return TeachingUnit
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Set name
     *
     * @param string $name
     *
     * @return ExamType
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

  
}
