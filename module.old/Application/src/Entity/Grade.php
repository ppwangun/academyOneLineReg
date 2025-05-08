<?php
namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;
use Application\Entity\ClassOfStudy;

/**
 * Grade
 *
 * @ORM\Table(name="grade", indexes={@ORM\Index(name="fk_grage_class_of_study1_idx", columns={"class_of_study_id"})})
 * @ORM\Entity
 */
class Grade
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
     * @return Grade
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
