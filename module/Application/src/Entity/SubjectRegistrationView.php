<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CurrentYearTeachingUnitView
 *
 * @ORM\Table(name="subject_registration_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class SubjectRegistrationView
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
    * @ORM\Column(name="status", type="integer", nullable=true)
    */
    private $status;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="id_ue", type="integer", nullable=false)
    * 
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $idUe;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="id_subject", type="integer", nullable=false)
    * 
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $idSubject;    
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
 
    /**
    * @var integer
    *
    * @ORM\Column(name="credits", type="integer", nullable=true)
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
    * @ORM\Column(name="student_current_classe", type="string", length=255, nullable=true)
    */
    private $studentCurrentClasse;    
    /**
    * @var string
    *
    * @ORM\Column(name="semester", type="string", nullable=true)
    */
    private $semester;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="sem_ranking", type="integer", nullable=true)
    */
    private $semRanking;    
    /**
    * @var integer
    *
    * @ORM\Column(name="student_id", type="integer",  nullable=false)
    */
    private $studentId;
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom", type="string", length=255, nullable=true)
    */
    private $nom;
    
    /**
    * @var string
    *
    * @ORM\Column(name="matricule", type="string", length=45, nullable=true)
    */
    private $matricule;
    
     /**
     * Get id
     *
     * @return integer
     */

    /**
     * @var float
     *
     * @ORM\Column(name="note_CC", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCc;

    /**
     * @var float
     *
     * @ORM\Column(name="note_CCTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteCctp;

    /**
     * @var float
     *
     * @ORM\Column(name="note_EXAMTP", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExamtp;

    /**
     * @var float
     *
     * @ORM\Column(name="note_EXAM", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteExam;

    /**
     * @var float
     *
     * @ORM\Column(name="note_STAGEC", type="float", precision=10, scale=0, nullable=true)
     */
   // private $noteStagec;

    /**
     * @var float
     *
     * @ORM\Column(name="note_STAGEE", type="float", precision=10, scale=0, nullable=true)
     */
    //private $noteStagee;

    /**
     * @var float
     *
     * @ORM\Column(name="note_final", type="float", precision=10, scale=0, nullable=true)
     */
    private $noteFinal;

    /**
     * @var string
     *
     * @ORM\Column(name="grade", type="string", length=45, nullable=true)
     */
    private $grade;

    /**
     * @var float
     *
     * @ORM\Column(name="points", type="float", precision=10, scale=0, nullable=true)
     */
    private $points;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isFromRatrappage", type="integer",  nullable=true)
     */
    private $isFromRatrappage = '0';    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isFromDeliberation", type="integer",  nullable=true)
     */
    private $isFromDeliberation = '0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isRepeated", type="integer",  nullable=true)
     */
    private $isRepeated = '0';
    
    public function getId()
    {
        return $this->id;
    }

    
    public function getIdSubject()
    {
        return $this->idSubject;
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
     * Get isFromDeliberation
     *
     * @return float
     */
    public function getIsFromDeliberation()
    {
        return $this->isFromDeliberation;
    }

    /**
     * Get isFromRatrappage
     *
     * @return float
     */
    public function getIsFromRatrappage()
    {
        return $this->isFromRatrappage;
    }  
    
    public function setMatricule($matricule)
    {
         $this->matricule = $matricule;
    }     
    
    public function setNom($nom)
    {
         $this->nom = $nom;
    }
    
    public function setStudentCurrentClasse($studentCurrentClasse)
    {
         $this->studentCurrentClasse = $studentCurrentClasse;
    } 
    
    public function setStatus($status)
    {
         $this->status = $status;
    }   
    public function getIsRepeated()
    {
         return $this->isRepeated ;
    }    
}

