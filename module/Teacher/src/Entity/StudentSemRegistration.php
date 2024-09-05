<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

use Application\Entity\Student;
use Application\Entity\Semester;
/**
 * StudentSemRegistration
 *
 * @ORM\Table(name="student_sem_registration", indexes={@ORM\Index(name="fk_student_has_semester_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_student_has_semester_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentSemRegistration
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
     * @ORM\Column(name="mps_current_sem", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpsCurrentSem;

    /**
     * @var float
     *
     * @ORM\Column(name="mpc_previous", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpcPrevious;

    /**
     * @var float
     *
     * @ORM\Column(name="mpc_current_sem", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpcCurrentSem;

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credits_capitalized_current_sem", type="integer", nullable=true)
     */
    private $nbCreditsCapitalizedCurrentSem;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credits_capitalized_previous_sem", type="integer", nullable=true)
     */
    private $nbCreditsCapitalizedPreviousSem;    

    /**
     * @var integer
     *
     * @ORM\Column(name="nb_credtis_capitalized_previous", type="integer", nullable=true)
     */
    private $nbCredtisCapitalizedPrevious;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_cycle_previous_year", type="integer", nullable=true)
     */
    private $totalCreditsCyclePreviousYear;
   
    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_cycle_current_year", type="integer", nullable=true)
     */
    private $totalCreditsCycleCurrentYear;    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_registered_current_class", type="integer", nullable=true)
     */
    private $totalCreditRegisteredCurrentClass;    

    /**
     * @var integer
     *
     * @ORM\Column(name="total_credit_registered_current_sem", type="integer", nullable=true)
     */
    private $totalCreditRegisteredCurrentSem;
    
      /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_registered_previous_cycle", type="integer", nullable=true)
     */
    private $totalCreditRegisteredPreviousCycle;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_registered_current_cycle", type="integer", nullable=true)
     */
    private $totalCreditRegisteredCurrentCycle;
    
      /**
     * @var integer
     *
     * @ORM\Column(name="total_credit_registered_previous_sem", type="integer", nullable=true)
     */
    private $totalCreditRegisteredPreviousSem;    
    
    
     /**
     * @var integer
     *
     * @ORM\Column(name="validation_percentage", type="integer", nullable=true)
     */
    private $validationPercentage;  
    
     /**
     * @var string
     *
     * @ORM\Column(name="academic_profile", type="string", nullable=true)
     */
    private $academicProfile;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_previous_class", type="integer", nullable=true)
     */
    private $totalCreditsPreviousClass; 
    
    /**
     * @var integer
     *
     * @ORM\Column(name="total_credits_current_class", type="integer", nullable=true)
     */
    private $totalCreditsCurrentClass;  
    
    /**
     * @var integer
     *
     * @ORM\Column(name="counting_sem_registration", type="integer", nullable=true)
     */
    private $countingSemRegistration="0";   
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="transcriptReferenceId", type="string",  nullable=true)
     */
    private $transcriptReferenceId; 


    /**
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

    /**
     * @var Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



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
     * Set mpsCurrentSem
     *
     * @param float $mpsCurrentSem
     *
     * @return StudentSemInscription
     */
    public function setMpsCurrentSem($mpsCurrentSem)
    {
        $this->mpsCurrentSem = $mpsCurrentSem;

        return $this;
    }

    /**
     * Get mpsCurrentSem
     *
     * @return float
     */
    public function getMpsCurrentSem()
    {
        return $this->mpsCurrentSem;
    }

    /**
     * Set mpcPrevious
     *
     * @param float $mpcPrevious
     *
     * @return StudentSemInscription
     */
    public function setMpcPrevious($mpcPrevious)
    {
        $this->mpcPrevious = $mpcPrevious;

        return $this;
    }

    /**
     * Get mpcPrevious
     *
     * @return float
     */
    public function getMpcPrevious()
    {
        return $this->mpcPrevious;
    }

    /**
     * Set mpcCurrentSem
     *
     * @param float $mpcCurrentSem
     *
     * @return StudentSemInscription
     */
    public function setMpcCurrentSem($mpcCurrentSem)
    {
        $this->mpcCurrentSem = $mpcCurrentSem;

        return $this;
    }

    /**
     * Get mpcCurrentSem
     *
     * @return float
     */
    public function getMpcCurrentSem()
    {
        return $this->mpcCurrentSem;
    }

    /**
     * Set nbCreditsCapitalizedCurrentSem
     *
     * @param integer $nbCreditsCapitalizedCurrentSem
     *
     * @return StudentSemInscription
     */
    public function setNbCreditsCapitalizedCurrentSem($nbCreditsCapitalizedCurrentSem)
    {
        $this->nbCreditsCapitalizedCurrentSem = $nbCreditsCapitalizedCurrentSem;

        return $this;
    }

    /**
     * Get nbCreditsCapitalizedCurrentSem
     *
     * @return integer
     */
    public function getNbCreditsCapitalizedCurrentSem()
    {
        return $this->nbCreditsCapitalizedCurrentSem;
    }

    /**
     * Set nbCredtisCapitalizedPrevious
     *
     * @param integer $nbCredtisCapitalizedPrevious
     *
     * @return StudentSemInscription
     */
    public function setNbCredtisCapitalizedPrevious($nbCredtisCapitalizedPrevious)
    {
        $this->nbCredtisCapitalizedPrevious = $nbCredtisCapitalizedPrevious;

        return $this;
    }

    /**
     * Get nbCredtisCapitalizedPrevious
     *
     * @return integer
     */
    public function getNbCredtisCapitalizedPrevious()
    {
        return $this->nbCredtisCapitalizedPrevious;
    }

    /**
     * Set totalCreditsCyclePreviousYear
     *
     * @param integer $totalCreditsCyclePreviousYear
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditsCyclePreviousYear($totalCreditsCyclePreviousYear)
    {
        $this->totalCreditsCyclePreviousYear = $totalCreditsCyclePreviousYear;

        return $this;
    }

    /**
     * Get totalCreditsCyclePreviousYear
     *
     * @return integer
     */
    public function getTotalCreditsCyclePreviousYear()
    {
        return $this->totalCreditsCyclePreviousYear;
    }

    /**
     * Set totalCreditsCycleCurrentYear
     *
     * @param integer $totalCreditsCycleCurrentYear
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditsCycleCurrentYear($totalCreditsCycleCurrentYear)
    {
        $this->totalCreditsCycleCurrentYear = $totalCreditsCycleCurrentYear;

        return $this;
    }

    /**
     * Get totalCreditsCycleCurrentYear
     *
     * @return integer
     */
    public function getTotalCreditsCycleCurrentYear()
    {
        return $this->totalCreditsCycleCurrentYear;
    }    
    
    /**
     * Set totalCreditRegisteredCurrentSem
     *
     * @param integer $totalCreditRegisteredCurrentSem
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredCurrentSem($totalCreditRegisteredCurrentSem)
    {
        $this->totalCreditRegisteredCurrentSem = $totalCreditRegisteredCurrentSem;

        return $this;
    }

    /**
     * Get totalCreditRegisteredCurrentSem
     *
     * @return integer
     */
    public function getTotalCreditRegisteredCurrentSem()
    {
        return $this->totalCreditRegisteredCurrentSem;
    }

    /**
     * Set totalCreditRegisteredPreviousSem
     *
     * @param integer $totalCreditRegisteredPreviousSem
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredPreviousSem($totalCreditRegisteredPreviousSem)
    {
        $this->totalCreditRegisteredPreviousSem = $totalCreditRegisteredPreviousSem;

        return $this;
    }

    /**
     * Get totalCreditRegisteredPreviousSem
     *
     * @return integer
     */
    public function getTotalCreditRegisteredPreviousSem()
    {
        return $this->totalCreditRegisteredPreviousSem;
    }    
    
    /**
     * Set totalCreditRegisteredCurrentClass
     *
     * @param integer $totalCreditRegisteredCurrentClass
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredCurrentClass($totalCreditRegisteredCurrentClass)
    {
        $this->totalCreditRegisteredCurrentClass = $totalCreditRegisteredCurrentClass;

        return $this;
    }

 
    
    /**
     * Set totalCreditRegisteredPreviousClass
     *
     * @param integer $totalCreditRegisteredPreviousClass
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredPreviousClass($totalCreditRegisteredPreviousClass)
    {
        $this->totalCreditRegisteredPreviousClass = $totalCreditRegisteredPreviousClass;

        return $this;
    }
    
    /**
     * Get validationPercentage
     *
     * @return integer
     */
    public function getValidationPercentage()
    {
        return $this->validationPercentage;
    }    

    /**
     * Set validationPercentage
     *
     * @param integer $validationPercentage
     *
     * @return StudentSemInscription
     */
    public function setValidationPercentage($validationPercentage)
    {
        $this->validationPercentage = $validationPercentage;

        return $this;
    }

    /**
     * Get academicProfile
     *
     * @return integer
     */
    public function getAcademicProfile()
    {
        return $this->academicProfile;
    }    

    /**
     * Set academicProfile
     *
     * @param integer $academicProfile
     *
     * @return StudentSemInscription
     */
    public function setAcademicProfile($academicProfile)
    {
        $this->academicProfile = $academicProfile;

        return $this;
    }    
    
    /**
     * Get totalCreditRegisteredCurrentClass
     *
     * @return integer
     */
    public function getTotalCreditRegisteredCurrentClass()
    {
        return $this->totalCreditRegisteredCurrentClass;
    }     
    /**
     * Set semester
     *
     * @param Semester $semester
     *
     * @return StudentSemInscription
     */
    public function setSemester(Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester
     *
     * @return Semester
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set student
     *
     * @param Student $student
     *
     * @return StudentSemInscription
     */
    public function setStudent(Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return Student
     */
    public function getStudent()
    {
        return $this->student;
    }
    
    /**
     * Get totalCreditsPreviousClass
     *
     * @return integer
     */
    public function getTotalCreditsPreviousClass()
    {
        return $this->totalCreditsPreviousClass;
    }

    /**
     * Set totalCreditsPreviousClass
     *
     * @param integer $totalCreditsPreviousClass
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditsPreviousClass($totalCreditsPreviousClass)
    {
        $this->totalCreditsPreviousClass = $totalCreditsPreviousClass;

        return $this;
    }    
    
    /**
     * Get totalCreditsCurrentClass
     *
     * @return integer
     */
    public function getTotalCreditsCurrentClass()
    {
        return $this->totalCreditsCurrentClass;
    }

    /**
     * Set totalCreditsCurrentClass
     *
     * @param integer $totalCreditsCurrentClass
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditsCurrentClass($totalCreditsCurrentClass)
    {
        $this->totalCreditsCurrentClass = $totalCreditsCurrentClass;

        return $this;
    }  
    
    /**
     * Get nbCreditsCapitalizedPreviousSem
     *
     * @return integer
     */
    public function getNbCreditsCapitalizedPreviousSem()
    {
        return $this->nbCreditsCapitalizedPreviousSem;
    }

    /**
     * Set nbCreditsCapitalizedPreviousSem
     *
     * @param integer $nbCreditsCapitalizedPreviousSem
     *
     * @return StudentSemInscription
     */
    public function setNbCreditsCapitalizedPreviousSem($nbCreditsCapitalizedPreviousSem)
    {
        $this->nbCreditsCapitalizedPreviousSem = $nbCreditsCapitalizedPreviousSem;

        return $this;
    } 
    
    /**
     * Get totalCreditRegisteredPreviousCycle
     *
     * @return integer
     */
    public function getTotalCreditRegisteredPreviousCycle()
    {
        return $this->totalCreditRegisteredPreviousCycle;
    }    
    
    /**
     * Set totalCreditRegisteredPreviousCycle
     *
     * @param integer $totalCreditRegisteredPreviousCycle
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredPreviousCycle($totalCreditRegisteredPreviousCycle)
    {
        $this->totalCreditRegisteredPreviousCycle = $totalCreditRegisteredPreviousCycle;

        return $this;
    } 
    
    /**
     * Get totalCreditRegisteredCurrentCycle
     *
     * @return integer
     */
    public function getTotalCreditRegisteredCurrentCycle()
    {
        return $this->totalCreditRegisteredCurrentCycle;
    }    
    
    /**
     * Set totalCreditRegisteredCurrentCycle
     *
     * @param integer $totalCreditRegisteredCurrentCycle
     *
     * @return StudentSemInscription
     */
    public function setTotalCreditRegisteredCurrentCycle($totalCreditRegisteredCurrentCycle)
    {
        $this->totalCreditRegisteredCurrentCycle = $totalCreditRegisteredCurrentCycle;

        return $this;
    } 

    /**
     * Get countingSemRegistration
     *
     * @return integer
     */
    public function getCountingSemRegistration()
    {
        return $this->countingSemRegistration;
    }    
    
    /**
     * Set countingSemRegistration
     *
     * @param integer $countingSemRegistration
     *
     * @return StudentSemInscription
     */
    public function setCountingSemRegistration($countingSemRegistration)
    {
        $this->countingSemRegistration = $countingSemRegistration;

        return $this;
    }

    /**
     * Set transcriptReferenceId
     *
     * @param string $transcriptReferenceId
     *
     * @return AdminRegistration
     */
    public function setTranscriptReferenceId($transcriptReferenceId)
    {
        $this->transcriptReferenceId = $transcriptReferenceId;

        return $this;
    }

    /**
     * Get transcriptReferenceId
     *
     * @return string
     */
    public function getTranscriptReferenceId()
    {
        return $this->transcriptReferenceId;
    } 

    
}
