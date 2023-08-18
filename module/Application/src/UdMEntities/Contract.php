<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract", indexes={@ORM\Index(name="fk_contract_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"}), @ORM\Index(name="fk_contract_teacher1_idx", columns={"teacher_id"})})
 * @ORM\Entity
 */
class Contract
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
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="volume_hrs_done", type="string", length=45, nullable=true)
     */
    private $volumeHrsDone;

    /**
     * @var int|null
     *
     * @ORM\Column(name="contractcol", type="integer", nullable=true)
     */
    private $contractcol;

    /**
     * @var \ClassOfStudyHasSemester
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;

    /**
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;



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
     * Set status.
     *
     * @param string|null $status
     *
     * @return Contract
     */
    public function setStatus($status = null)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status.
     *
     * @return string|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set volumeHrsDone.
     *
     * @param string|null $volumeHrsDone
     *
     * @return Contract
     */
    public function setVolumeHrsDone($volumeHrsDone = null)
    {
        $this->volumeHrsDone = $volumeHrsDone;
    
        return $this;
    }

    /**
     * Get volumeHrsDone.
     *
     * @return string|null
     */
    public function getVolumeHrsDone()
    {
        return $this->volumeHrsDone;
    }

    /**
     * Set contractcol.
     *
     * @param int|null $contractcol
     *
     * @return Contract
     */
    public function setContractcol($contractcol = null)
    {
        $this->contractcol = $contractcol;
    
        return $this;
    }

    /**
     * Get contractcol.
     *
     * @return int|null
     */
    public function getContractcol()
    {
        return $this->contractcol;
    }

    /**
     * Set classOfStudyHasSemester.
     *
     * @param \ClassOfStudyHasSemester|null $classOfStudyHasSemester
     *
     * @return Contract
     */
    public function setClassOfStudyHasSemester(\ClassOfStudyHasSemester $classOfStudyHasSemester = null)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;
    
        return $this;
    }

    /**
     * Get classOfStudyHasSemester.
     *
     * @return \ClassOfStudyHasSemester|null
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }

    /**
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return Contract
     */
    public function setTeacher(\Teacher $teacher = null)
    {
        $this->teacher = $teacher;
    
        return $this;
    }

    /**
     * Get teacher.
     *
     * @return \Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
