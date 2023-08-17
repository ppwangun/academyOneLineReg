<?php

namespace Application\Entity;


use Doctrine\ORM\Mapping as ORM;

use Application\Entity\ClassOfStudy;
use Application\Entity\Semester;
use Application\Entity\AcademicYear;

/**
 * SemesterAssociatedToClass
 *
 * @ORM\Table(name="semester_associated_to_class", indexes={@ORM\Index(name="fk_semester_associated_to_class_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_semester_associated_to_class_class_of_study1_idx", columns={"class_of_study_id"}), @ORM\Index(name="fk_semester_associated_to_class_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity
 */
class SemesterAssociatedToClass
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
     * @ORM\Column(name="mark_calculation_status", type="integer", nullable=true)
     */
    private $markCalculationStatus='0';   
    
    /**
     * @var integer
     *
     * @ORM\Column(name="sendSmsStatus", type="integer", nullable=true)
     */
    private $sendSmsStatus='0';   
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="transcriptReferenceGenerationStatus", type="integer", nullable=true)
     */
    private $transcriptReferenceGenerationStatus='0';     

    /**
     * @var AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     * })
     */
    private $academicYear;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set markCalculationStatus
     *
     * @param integer $markCalculationStatus
     *
     * @return SemesterAssociatedToClass
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
     * Set sendSmsStatus
     *
     * @param integer $sendSmsStatus
     *
     * @return SemesterAssociatedToClass
     */
    public function setSendSmsStatus($sendSmsStatus)
    {
        $this->sendSmsStatus = $sendSmsStatus;

        return $this;
    }

    /**
     * Get sendSmsStatus
     *
     * @return integer
     */
    public function getSendSmsStatus()
    {
        return $this->sendSmsStatus;
    }    

    /**
     * Set academicYear
     *
     * @param AcademicYear $academicYear
     *
     * @return SemesterAssociatedToClass
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear
     *
     * @return AcademicYear
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }

    /**
     * Set classOfStudy
     *
     * @param ClassOfStudy $classOfStudy
     *
     * @return SemesterAssociatedToClass
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
     * @return SemesterAssociatedToClass
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
     * Get transcriptReferenceGenerationStatus
     *
     * @return integer
     */
    public function getTranscriptReferenceGenerationStatus()
    {
        return $this->transcriptReferenceGenerationStatus;
    }

    /**
     * Set transcriptReferenceGenerationStatus
     *
     * @param integer $transcriptReferenceGenerationStatus
     *
     * @return SemesterAssociatedToClass
     */
    public function setTranscriptReferenceGenerationStatus($transcriptReferenceGenerationStatus)
    {
        $this->transcriptReferenceGenerationStatus = $transcriptReferenceGenerationStatus;

        return $this;
    }    
}
