<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="registration_per_class_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class RegistrationPerClassView
{
    /**
    * @var string
    *
    * @ORM\Column(name="id", type="string", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="classe", type="string", length=45, nullable=true)
     */
    private $classe;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="niveau", type="integer", nullable=false)
    */
    private $niveau;
    
      /**
     * @var string
     *
     * @ORM\Column(name="faculty", type="string", length=45, nullable=true)
     */
    private $faculty;
    
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
     * Get faculty
     *
     * @return string
     */
    public function getFaculty()
    {
        return $this->faculty;
    }
}

