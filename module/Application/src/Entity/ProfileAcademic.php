<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * ProfileAcademic
 *
 * @ORM\Table(name="profile_academic")
 * @ORM\Entity
 */
class ProfileAcademic
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
     * @ORM\Column(name="minval", type="float", precision=10, scale=0, nullable=true)
     */
    private $minval;

    /**
     * @var float
     *
     * @ORM\Column(name="maxval", type="float", precision=10, scale=0, nullable=true)
     */
    private $maxval;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;



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
     * Set minval
     *
     * @param float $minval
     *
     * @return ProfileAcademic
     */
    public function setMinval($minval)
    {
        $this->minval = $minval;

        return $this;
    }

    /**
     * Get minval
     *
     * @return float
     */
    public function getMinval()
    {
        return $this->minval;
    }

    /**
     * Set maxval
     *
     * @param float $maxval
     *
     * @return ProfileAcademic
     */
    public function setMaxval($maxval)
    {
        $this->maxval = $maxval;

        return $this;
    }

    /**
     * Get maxval
     *
     * @return float
     */
    public function getMaxval()
    {
        return $this->maxval;
    }

    /**
     * Set grade
     *
     * @param string $grade
     *
     * @return ProfileAcademic
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }
}
