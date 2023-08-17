<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countries
 *
 * @ORM\Table(name="countries")
 * @ORM\Entity
 */
class Countries
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
     * @ORM\Column(name="sortname", type="string", length=3, nullable=false)
     */
    private $sortname;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="phonecode", type="integer", nullable=false)
     */
    private $phonecode;



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
     * Set sortname
     *
     * @param string $sortname
     *
     * @return Countries
     */
    public function setSortname($sortname)
    {
        $this->sortname = $sortname;

        return $this;
    }

    /**
     * Get sortname
     *
     * @return string
     */
    public function getSortname()
    {
        return $this->sortname;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Countries
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
     * Set phonecode
     *
     * @param integer $phonecode
     *
     * @return Countries
     */
    public function setPhonecode($phonecode)
    {
        $this->phonecode = $phonecode;

        return $this;
    }

    /**
     * Get phonecode
     *
     * @return integer
     */
    public function getPhonecode()
    {
        return $this->phonecode;
    }
}
