<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrentYearUeExamsView
 *
 * @ORM\Table(name="current_year_subject_exams_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class CurrentYearSubjectExamsView
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id
    * 
    */
    private $id;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="acadYrId", type="integer", nullable=false)
    * 
    */
    private $acadYrId;     
    
    /**
    * @var string
    *
    * @ORM\Column(name="code", type="string", length=45, nullable=true)
    */
    private $code;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="coshs", type="integer", nullable=false)
    * @ORM\Id
    * 
    */
    private $coshs;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="type", type="string", length=255, nullable=true)
    */
    private $type;
    
     /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;
    
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="sem", type="string", nullable=true)
    */
    private $semester;
 
    /**
    * @var integer
    *
    * @ORM\Column(name="subject_id", type="integer", nullable=true)
    */
    private $subjectId;
    
    /**
    * @var string
    *
    * @ORM\Column(name="subject", type="string", nullable=true)
    */
    private $subject;

    /**
    * @var integer
    *
    * @ORM\Column(name="ue_id", type="integer", nullable=true)
    */
    private $ueId;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="ue", type="string", nullable=true)
    */
    private $ue;

    /**
    * @var integer
    *
    * @ORM\Column(name="is_mark_registered", type="integer", nullable=true)
    */
    private $isMarkRegistered;

    /**
    * @var integer
    *
    * @ORM\Column(name="is_mark_validated", type="integer", nullable=true)
    */
    private $isMarkValidated;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="is_mark_confirmed", type="integer", nullable=true)
    */
    private $isMarkConfirmed;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="status", type="integer", nullable=true)
    */
    private $status;
    
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
    public function getType()
    {
        return $this->type;
    }
    
     /**
     * Get isMarkValidated
     *
     * @return integer
     */
    public function getIsMarkValidated()
    {
        return $this->isMarkValidated;
    }
    
     /**
     * Get isMarkRegistered
     *
     * @return integer
     */
    public function getIsMarkRegistered()
    {
        return $this->isMarkRegistered;
    }  
    
     /**
     * Get isMarkConfirmed
     *
     * @return integer
     */
    public function getIsMarkConfirmed()
    {
        return $this->isMarkConfirmed;
    } 
    
     /**
     * Get coshs
     *
     * @return integer
     */
    public function getCoshs()
    {
        return $this->coshs;
    }

    /* Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }     
}

