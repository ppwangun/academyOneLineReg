<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="registered_student_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class RegisteredStudentView
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
    * @ORM\Column(name="matricule", type="string", length=45, nullable=true)
    */
    private $matricule;
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom", type="string", length=255, nullable=true)
    */
    private $nom;
    
    /**
    * @var string
    *
    * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
    */
    private $prenom;
    
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_naissance", type="datetime", nullable=true)
    */
    private $dateNaissance;
    
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", nullable=true)
    */
    private $class;
    
    /**
    * @var \DateTime
    *
    * @ORM\Column(name="date_inscription", type="datetime", nullable=true)
    */
    private $dateInscription;
    
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
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }
    
      /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    
     /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
    
     /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateOfBirth;
    }
    
     /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    } 
    
     /**
     * Get dateInscription
     *
     * @return \DateTime
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }
}

