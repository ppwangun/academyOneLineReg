<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrentYearTeachingUnitView
 *
 * @ORM\Table(name="current_year_ues_and_subjects_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class CurrentYearUesAndSubjectsView
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
    * @var integer
    *
    * @ORM\Column(name="subject_id", type="string", length=45, nullable=true)
    */
    private $subjectId;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="code_ue", type="string", length=45, nullable=true)
    */
    private $codeUe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="degree_code", type="string", length=45, nullable=true)
    */
    private $degreeCode;    
    
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
    
        /**
    * @var float
    *
    * @ORM\Column(name="credits", type="float",  nullable=true)
    */
    private $credits;
    /**
    * @var integer
    *
    * @ORM\Column(name="total_hrs", type="float",  nullable=true)
    */
    private $totalHrs;  
    /**
    * @var integer
    *
    * @ORM\Column(name="cm_hrs", type="float",  nullable=true)
    */
    private $cmHrs;     
    /**
    * @var integer
    *
    * @ORM\Column(name="td_hrs", type="float",  nullable=true)
    */
    private $tdHrs;
    /**
    * @var integer
    *
    * @ORM\Column(name="tp_hrs", type="float",  nullable=true)
    */
    private $tpHrs;     
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="is_previous_year_subject", type="integer", length=255, nullable=false)
    */
    private $isPreviousYearSubject;    
    
       /**
    * @var integer
    *
    * @ORM\Column(name="study_level", type="integer", nullable=true)
    */
    private $studyLevel;
    
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
    private $semId;
    /**
    * @var integer
    *
    * @ORM\Column(name="sem_ranking", type="integer", nullable=true)
    */
    private $semRanking;
    /**
    * @var integer
    *
    * @ORM\Column(name="teacher", type="integer", nullable=true)
    */
    private $teacher;    
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
     * Get id
     *
     * @return integer
     */
    public function getSubjectId()
    {
        return $this->subjectId;
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
     * Get string
     *
     * @return string
     */
    public function getNomUe()
    {
        return $this->nomUe;
    }
    
    
     /**
     * Get string
     *
     * @return string
     */
    public function getDegreeCode()
    {
        return $this->degreeCode;
    }    
    
     /**
     * Get float
     *
     * @return float
     */
    public function getCredits()
    {
        return $this->credits;
    }
     /**
     * Get integer
     *
     * @return integer
     */
    public function getTotalHrs()
    {
        return $this->totalHrs;
    } 
     /**
     * Get integer
     *
     * @return integer
     */
    public function getCmHrs()
    {
        return $this->cmHrs;
    } 
     /**
     * Get integer
     *
     * @return integer
     */
    public function getTdHrs()
    {
        return $this->tdHrs;
    } 
     /**
     * Get integer
     *
     * @return integer
     */
    public function getTpHrs()
    {
        return $this->tpHrs;
    }    
     /**
     * Get integer
     *
     * @return integer
     */
    public function getSemId()
    {
        return $this->semId;
    }
         /**
     * Get integer
     *
     * @return integer
     */
    public function getStudyLevel()
    {
        return $this->studyLevel;
    }
    
         /**
     * Get integer
     *
     * @return integer
     */
    public function getSemRanking()
    {
        return $this->semRanking;
    } 
    /**
     * Get integer
     *
     * @return integer
     */
    public function getTeacher()
    {
        return $this->teacher;
    }    
}

