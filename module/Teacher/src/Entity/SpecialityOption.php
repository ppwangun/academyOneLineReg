<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Speciality;
/**
 * SpecialityOption
 *
 * @ORM\Table(name="speciality_option", indexes={@ORM\Index(name="fk_speciality_option_speciality1_idx", columns={"speciality_id"})})
 * @ORM\Entity
 */
class SpecialityOption
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
     * @ORM\Column(name="Code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="Name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true, options={"default"="1"})
     */
    private $status = 1;

    /**
     * @var Speciality
     *
     * @ORM\ManyToOne(targetEntity="Speciality")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="speciality_id", referencedColumnName="id")
     * })
     */
    private $speciality;



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
     * Set code.
     *
     * @param string|null $code
     *
     * @return SpecialityOption
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
     * Set name.
     *
     * @param string|null $name
     *
     * @return SpecialityOption
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
     * Set status.
     *
     * @param int|null $status
     *
     * @return SpecialityOption
     */
    public function setStatus($status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set speciality.
     *
     * @param Speciality|null $speciality
     *
     * @return SpecialityOption
     */
    public function setSpeciality(Speciality $speciality = null)
    {
        $this->speciality = $speciality;
    
        return $this;
    }

    /**
     * Get speciality.
     *
     * @return Speciality|null
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }
}
