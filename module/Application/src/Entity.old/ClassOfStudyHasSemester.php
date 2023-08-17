<?php
namespace Application\Entity;

use Application\Entity\TeachingUnit;
use Application\Entity\ClassOfStudy;
use Application\Entity\Semester;
use Application\Entity\Subject;


use Doctrine\ORM\Mapping as ORM;

/**
 * ClassOfStudyHasSemester
 *
 * @ORM\Table(name="class_of_study_has_semester", indexes={@ORM\Index(name="fk_class_of_study_has_semester_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_class_of_study_has_semester_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_class_of_study_has_semester_teaching_unit1_idx", columns={"teaching_unit_id"}), @ORM\Index(name="fk_class_of_study_has_semester_subject1_idx", columns={"subject_id"})})
 * @ORM\Entity
 */
class ClassOfStudyHasSemester
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
     * @ORM\Column(name="credits", type="float", precision=10, scale=0, nullable=true)
     */
    private $credits;

    /**
     * @var integer
     *
     * @ORM\Column(name="hours_volume", type="integer", nullable=true)
     */
    private $hoursVolume;

    /**
     * @var float
     *
     * @ORM\Column(name="subject_credits", type="float", precision=10, scale=0, nullable=true)
     */
    private $subjectCredits;
    
    /**
     * @var float
     *
     * @ORM\Column(name="subject_weight", type="float", precision=10, scale=0, nullable=true)
     */
    private $subjectWeight='0.5';    

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_hours", type="integer", nullable=true)
     */
    private $subjectHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="cm_hours", type="integer", nullable=true)
     */
    private $cmHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="tp_hours", type="integer", nullable=true)
     */
    private $tpHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="td_hours", type="integer", nullable=true)
     */
    private $tdHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_cm_hours", type="integer", nullable=true)
     */
    private $subjectCmHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_td_hours", type="integer", nullable=true)
     */
    private $subjectTdHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="subject_tp_hours", type="integer", nullable=true)
     */
    private $subjectTpHours;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status='1';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_repeated_subject", type="integer", nullable=true)
     */
    private $isReapeatedYearSubject='0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="is_previous_year_subject", type="integer", nullable=true)
     */
    private $isPreviousYearSubject='0';    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="mark_calculation_status", type="integer", nullable=true)
     */
    private $markCalculationStatus='0';  
    
    /**
     * @var integer
     *
     * @ORM\Column(name="module_consolidation_status", type="integer", nullable=true)
     */
    private $moduleConsolidationStatus='0';  
    
    /**
     * @var ClassOfStudy
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     * })
     */
    private $classOfStudy;

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
     * @var Subject
     *
     * @ORM\ManyToOne(targetEntity="Subject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subject_id", referencedColumnName="id")
     * })
     */
    private $subject;

    /**
     * @var TeachingUnit
     *
     * @ORM\ManyToOne(targetEntity="TeachingUnit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teaching_unit_id", referencedColumnName="id")
     * })
     */
    private $teachingUnit;



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
     * Set credits
     *
     * @param float $credits
     *
     * @return ClassOfStudyHasSemester
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits
     *
     * @return float
     */
    public function getCredits()
    {
        return $this->credits;
    }

    /**
     * Set hoursVolume
     *
     * @param integer $hoursVolume
     *
     * @return ClassOfStudyHasSemester
     */
    public function setHoursVolume($hoursVolume)
    {
        $this->hoursVolume = $hoursVolume;

        return $this;
    }

    /**
     * Get hoursVolume
     *
     * @return integer
     */
    public function getHoursVolume()
    {
        return $this->hoursVolume;
    }

    /**
     * Set subjectCredits
     *
     * @param float $subjectCredits
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectCredits($subjectCredits)
    {
        $this->subjectCredits = $subjectCredits;

        return $this;
    }

    /**
     * Get subjectCredits
     *
     * @return float
     */
    public function getSubjectCredits()
    {
        return $this->subjectCredits;
    }
    
    /**
     * Set weight
     *
     * @param float $weight
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectWeight($weight)
    {
        $this->subjectWeight= $weight;

        return $this;
    }    
    
    /**
     * Get weight
     *
     * @return float
     */
    public function getSubjectWeight()
    {
        return $this->subjectWeight;
    }    

    /**
     * Set subjectHours
     *
     * @param integer $subjectHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectHours($subjectHours)
    {
        $this->subjectHours = $subjectHours;

        return $this;
    }

    /**
     * Get subjectHours
     *
     * @return integer
     */
    public function getSubjectHours()
    {
        return $this->subjectHours;
    }

    /**
     * Set cmHours
     *
     * @param integer $cmHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setCmHours($cmHours)
    {
        $this->cmHours = $cmHours;

        return $this;
    }

    /**
     * Get cmHours
     *
     * @return integer
     */
    public function getCmHours()
    {
        return $this->cmHours;
    }

    /**
     * Set tpHours
     *
     * @param integer $tpHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTpHours($tpHours)
    {
        $this->tpHours = $tpHours;

        return $this;
    }

    /**
     * Get tpHours
     *
     * @return integer
     */
    public function getTpHours()
    {
        return $this->tpHours;
    }

    /**
     * Set tdHours
     *
     * @param integer $tdHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTdHours($tdHours)
    {
        $this->tdHours = $tdHours;

        return $this;
    }

    /**
     * Get tdHours
     *
     * @return integer
     */
    public function getTdHours()
    {
        return $this->tdHours;
    }

    /**
     * Set subjectCmHours
     *
     * @param integer $subjectCmHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectCmHours($subjectCmHours)
    {
        $this->subjectCmHours = $subjectCmHours;

        return $this;
    }

    /**
     * Get subjectCmHours
     *
     * @return integer
     */
    public function getSubjectCmHours()
    {
        return $this->subjectCmHours;
    }

    /**
     * Set subjectTdHours
     *
     * @param integer $subjectTdHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectTdHours($subjectTdHours)
    {
        $this->subjectTdHours = $subjectTdHours;

        return $this;
    }

    /**
     * Get subjectTdHours
     *
     * @return integer
     */
    public function getSubjectTdHours()
    {
        return $this->subjectTdHours;
    }

    /**
     * Set subjectTpHours
     *
     * @param integer $subjectTpHours
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubjectTpHours($subjectTpHours)
    {
        $this->subjectTpHours = $subjectTpHours;

        return $this;
    }

    /**
     * Get subjectTpHours
     *
     * @return integer
     */
    public function getSubjectTpHours()
    {
        return $this->subjectTpHours;
    }
 
    /**
     * Set status
     *
     * @param integer $status
     *
     * @return ClassOfStudyHasSemester
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

 /**
     * Set isPreviousYearSubject
     *
     * @param integer $isPreviousYearSubject
     *
     * @return ClassOfStudyHasSemester
     */
    public function setIsPreviousYearSubject($isPreviousYearSubject)
    {
        $this->isPreviousYearSubject = $isPreviousYearSubject;

        return $this;
    }

    /**
     * Get isPreviousYearSubject
     *
     * @return integer
     */
    public function getisPreviousYearSubject()
    {
        return $this->isPreviousYearSubject;
    }

 /**
     * Set isRepeatedSubject
     *
     * @param integer $isRepeatedSubject
     *
     * @return ClassOfStudyHasSemester
     */
    public function setIsRepeatedSubject($isRepeatedSubject)
    {
        $this->isRepeatedSubject = $isRepeatedSubject;

        return $this;
    }

    /**
     * Get isRepeatedSubject
     *
     * @return integer
     */
    public function getisRepeatedSubject()
    {
        return $this->isRepeatedSubject;
    }
    
    /**
     * Set markCalculationStatus
     *
     * @param integer $markCalculationStatus
     *
     * @return ClassOfStudyHasSemester
     */
    public function setMarkCalculationStatus($markCalculationStatus)
    {
        $this->markCalculationStatus = $markCalculationStatus;

        return $this;
    }

    /**
     * Get markCalculationStatus
     *
     * @return integer
     */
    public function getMarkCalculationStatus()
    {
        return $this->markCalculationStatus;
    }
    
    /**
     * Set moduleConsolidationStatus
     *
     * @param integer $moduleConsolidationStatus
     *
     * @return ClassOfStudyHasSemester
     */
    public function setModuleConsolidationStatus($moduleConsolidationStatus)
    {
        $this->moduleConsolidationStatus = $moduleConsolidationStatus;

        return $this;
    }

    /**
     * Get moduleConsolidationStatus
     *
     * @return integer
     */
    public function getModuleConsolidationStatus()
    {
        return $this->moduleConsolidationStatus;
    }
    
    /**
     * Set classOfStudy
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return ClassOfStudyHasSemester
     */
    public function setClassOfStudy(ClassOfStudy $classOfStudy = null)
    {
        $this->classOfStudy = $classOfStudy;

        return $this;
    }

    /**
     * Get classOfStudy
     *
     * @return ClassOfStudy
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }

    /**
     * Set semester
     *
     * @param Semester $semester
     *
     * @return ClassOfStudyHasSemester
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
     * Set subject
     *
     * @param Subject $subject
     *
     * @return ClassOfStudyHasSemester
     */
    public function setSubject(Subject $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return Subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set teachingUnit
     *
     * @param TeachingUnit $teachingUnit
     *
     * @return ClassOfStudyHasSemester
     */
    public function setTeachingUnit(TeachingUnit $teachingUnit = null)
    {
        $this->teachingUnit = $teachingUnit;

        return $this;
    }

    /**
     * Get teachingUnit
     *
     * @return TeachingUnit
     */
    public function getTeachingUnit()
    {
        return $this->teachingUnit;
    }
 
   
}
