<?php



namespace Application\Entity;



use Doctrine\ORM\Mapping as ORM;
use Application\Entity\TrainingCurriculum;
use Application\Entity\Degree;
use Application\Entity\Grade;
use Application\Entity\Deliberation;

/**
 * ClassOfStudy
 *
 * @ORM\Table(name="class_of_study", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})}, indexes={@ORM\Index(name="fk_class_of_study_degree1_idx", columns={"degree_id"}), @ORM\Index(name="fk_class_of_study_grade1_idx", columns={"grade_id"}), @ORM\Index(name="fk_class_of_study_deliberation1_idx", columns={"deliberation_id"}), @ORM\Index(name="fk_class_of_study_cycle1_idx", columns={"cycle_id"})})
 * @ORM\Entity
 */
class ClassOfStudy
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var int|null
     *
     * @ORM\Column(name="study_level", type="integer", nullable=true)
     */
    private $studyLevel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_core_curriculum", type="integer", nullable=true)
     */
    private $isCoreCurriculum = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_end_of_core_curriculum", type="integer", nullable=true)
     */
    private $isEndOfCoreCurriculum = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_end_cycle", type="integer", nullable=true)
     */
    private $isEndCycle = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_end_degree_training", type="integer", nullable=true)
     */
    private $isEndDegreeTraining = '0';

    /**
     * @var TrainingCurriculum
     *
     * @ORM\ManyToOne(targetEntity="TrainingCurriculum")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cycle_id", referencedColumnName="id")
     * })
     */
    private $cycle;

    /**
     * @var Degree
     *
     * @ORM\ManyToOne(targetEntity="Degree")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="degree_id", referencedColumnName="id")
     * })
     */
    private $degree;

    /**
     * @var Deliberation
     *
     * @ORM\ManyToOne(targetEntity="Deliberation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="deliberation_id", referencedColumnName="id")
     * })
     */
    private $deliberation;

    /**
     * @var Grade
     *
     * @ORM\ManyToOne(targetEntity="Grade")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grade_id", referencedColumnName="id")
     * })
     */
    private $grade;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AcademicYear", inversedBy="classOfStudy")
     * @ORM\JoinTable(name="class_of_study_has_academic_year",
     *   joinColumns={
     *     @ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="academic_year_id", referencedColumnName="id")
     *   }
     * )
     */
    private $academicYear = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->academicYear = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set code.
     *
     * @param string|null $code
     *
     * @return ClassOfStudy
     */
    public function setCode($code = null)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set studyLevel.
     *
     * @param int|null $studyLevel
     *
     * @return ClassOfStudy
     */
    public function setStudyLevel($studyLevel = null)
    {
        $this->studyLevel = $studyLevel;
    
        return $this;
    }

    /**
     * Get studyLevel.
     *
     * @return int|null
     */
    public function getStudyLevel()
    {
        return $this->studyLevel;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return ClassOfStudy
     */
    public function setName($name = null)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isCoreCurriculum.
     *
     * @param int|null $isCoreCurriculum
     *
     * @return ClassOfStudy
     */
    public function setIsCoreCurriculum($isCoreCurriculum = null)
    {
        $this->isCoreCurriculum = $isCoreCurriculum;
    
        return $this;
    }

    /**
     * Get isCoreCurriculum.
     *
     * @return int|null
     */
    public function getIsCoreCurriculum()
    {
        return $this->isCoreCurriculum;
    }

    /**
     * Set isEndOfCoreCurriculum.
     *
     * @param int|null $isEndOfCoreCurriculum
     *
     * @return ClassOfStudy
     */
    public function setIsEndOfCoreCurriculum($isEndOfCoreCurriculum = null)
    {
        $this->isEndOfCoreCurriculum = $isEndOfCoreCurriculum;
    
        return $this;
    }

    /**
     * Get isEndOfCoreCurriculum.
     *
     * @return int|null
     */
    public function getIsEndOfCoreCurriculum()
    {
        return $this->isEndOfCoreCurriculum;
    }

    /**
     * Set isEndCycle.
     *
     * @param int|null $isEndCycle
     *
     * @return ClassOfStudy
     */
    public function setIsEndCycle($isEndCycle = null)
    {
        $this->isEndCycle = $isEndCycle;
    
        return $this;
    }

    /**
     * Get isEndCycle.
     *
     * @return int|null
     */
    public function getIsEndCycle()
    {
        return $this->isEndCycle;
    }

    /**
     * Set isEndDegreeTraining.
     *
     * @param int|null $isEndDegreeTraining
     *
     * @return ClassOfStudy
     */
    public function setIsEndDegreeTraining($isEndDegreeTraining = null)
    {
        $this->isEndDegreeTraining = $isEndDegreeTraining;
    
        return $this;
    }

    /**
     * Get isEndDegreeTraining.
     *
     * @return int|null
     */
    public function getIsEndDegreeTraining()
    {
        return $this->isEndDegreeTraining;
    }

    /**
     * Set cycle.
     *
     * @param TrainingCurriculum|null $cycle
     *
     * @return ClassOfStudy
     */
    public function setCycle(TrainingCurriculum $cycle = null)
    {
        $this->cycle = $cycle;
    
        return $this;
    }

    /**
     * Get cycle.
     *
     * @return TrainingCurriculum|null
     */
    public function getCycle()
    {
        return $this->cycle;
    }

    /**
     * Set degree.
     *
     * @param Degree|null $degree
     *
     * @return ClassOfStudy
     */
    public function setDegree(Degree $degree = null)
    {
        $this->degree = $degree;
    
        return $this;
    }

    /**
     * Get degree.
     *
     * @return Degree|null
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Set deliberation.
     *
     * @param Deliberation|null $deliberation
     *
     * @return ClassOfStudy
     */
    public function setDeliberation(Deliberation $deliberation = null)
    {
        $this->deliberation = $deliberation;
    
        return $this;
    }

    /**
     * Get deliberation.
     *
     * @return Deliberation|null
     */
    public function getDeliberation()
    {
        return $this->deliberation;
    }

    /**
     * Set grade.
     *
     * @param Grade|null $grade
     *
     * @return ClassOfStudy
     */
    public function setGrade(Grade $grade = null)
    {
        $this->grade = $grade;
    
        return $this;
    }

    /**
     * Get grade.
     *
     * @return Grade|null
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Add academicYear.
     *
     * @param AcademicYear $academicYear
     *
     * @return ClassOfStudy
     */
    public function addAcademicYear(AcademicYear $academicYear)
    {
        $this->academicYear[] = $academicYear;
    
        return $this;
    }

    /**
     * Remove academicYear.
     *
     * @param AcademicYear $academicYear
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAcademicYear(AcademicYear $academicYear)
    {
        return $this->academicYear->removeElement($academicYear);
    }

    /**
     * Get academicYear.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcademicYear()
    {
        return $this->academicYear;
    }
}
