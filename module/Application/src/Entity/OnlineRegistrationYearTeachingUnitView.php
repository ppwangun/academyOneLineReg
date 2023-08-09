<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OnlineRegistrationYearTeachingUnitView
 *
 * @ORM\Table(name="online_registration_year_teaching_unit_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class OnlineRegistrationYearTeachingUnitView
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
    * @ORM\Column(name="code_ue", type="string", length=45, nullable=true)
    */
    private $codeUe;

    /**
    * @var integer
    *
    * @ORM\Column(name="is_previous_year_subject", type="integer",  nullable=true)
    */
    private $isPreviousYearSubject;    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
    
        /**
    * @var integer
    *
    * @ORM\Column(name="credits", type="integer",  nullable=true)
    */
    private $credits;
    
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="semester", type="string", nullable=true)
    */
    private $semester;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="semID", type="integer", nullable=true)
    */
    private $semID;    
    
    /**
    * @var integer
    *
    * @ORM\Column(name="sem_ranking", type="integer", nullable=true)
    */
    private $semRanking;
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
     * Get string
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }
    
     /**
     * Get string
     *
     * @return string
     */
    public function getSemester()
    {
        return $this->semester;
    } 
    
    
     /**
     * Get string
     *
     * @return string
     */
    public function getCodeUe()
    {
        return $this->codeUe;
    }
     /**
     * Get integer
     *
     * @return integer
     */
    public function getCredits()
    {
        return $this->credits;
    }      
}

