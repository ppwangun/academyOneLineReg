<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Application\Entity\AcademicYear;
use Application\Entity\Semester;
use Application\MyRepository\ExamSessionRepository;

/**
 * ExamSession
 * 
 * @ORM\Table(name="exam_session", indexes={@ORM\Index(name="fk_exam_session_academic_year1_idx", columns={"academic_year_id"})})
 * @ORM\Entity(repositoryClass="Application\MyRepository\ExamSessionRepository")
 */
class ExamSession
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="session_type", type="string", length=45, nullable=true)
     */
    private $sessionType;    

    /**
     * @var string|null
     *
     * @ORM\Column(name="session_code", type="string", length=45, nullable=true)
     */
    private $sessionCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="session_name", type="string", length=45, nullable=true)
     */
    private $sessionName;



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
     * @var \Doctrine\Common\Collections\Collection|Semester[]
     *
     * @ORM\ManyToMany(targetEntity="Semester", mappedBy="examSessions")
     */
    protected $semesters;  
    
    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->semesters = new ArrayCollection();
    }

    /**
     * @param Semester $semester
     */
    public function addSemester(Semester $semester)
    {
        if ($this->semesters->contains($semester)) {
            return;
        }

        $this->semesters->add($semester);
        $semester->addExamSession($this);
    }

    /**
     * @param Semester $semester
     */
    public function removeSemester(Semester $semester)
    {
        if (!$this->semesters->contains($semester)) {
            return;
        }

        $this->semesters->removeElement($semester);
        $semester->removeExamSession($this);
    }    



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sessionType.
     *
     * @param string|null $sessionType
     *
     * @return ExamSession
     */
    public function setSessionType($sessionType = null)
    {
        $this->sessionType = $sessionType;

        return $this;
    }

    /**
     * Get sessionType.
     *
     * @return string|null
     */
    public function getSessionType()
    {
        return $this->sessionType;
    }

     /**
     * Set sessionCode.
     *
     * @param string|null $sessionCode
     *
     * @return ExamSession
     */
    public function setSessionCode($sessionCode = null)
    {
        $this->sessionCode = $sessionCode;

        return $this;
    }

    /**
     * Get sessionCode.
     *
     * @return string|null
     */
    public function getSessionCode()
    {
        return $this->sessionCode;
    }   
    
    /**
     * Set sessionName.
     *
     * @param string|null $sessionName
     *
     * @return ExamSession
     */
    public function setSessionName($sessionName = null)
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    /**
     * Get sessionName.
     *
     * @return string|null
     */
    public function getSessionName()
    {
        return $this->sessionName;
    }

    /**
     * Set academicYear.
     *
     * @param AcademicYear|null $academicYear
     *
     * @return ExamSession
     */
    public function setAcademicYear(AcademicYear $academicYear = null)
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    /**
     * Get academicYear.
     *
     * @return AcademicYear|null
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }
}
