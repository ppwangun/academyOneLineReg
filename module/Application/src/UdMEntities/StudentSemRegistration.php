<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * StudentSemRegistration
 *
 * @ORM\Table(name="student_sem_registration", indexes={@ORM\Index(name="fk_student_has_semester_semester1_idx", columns={"semester_id"}), @ORM\Index(name="fk_student_sem_registration_student1_idx", columns={"student_id"})})
 * @ORM\Entity
 */
class StudentSemRegistration
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
     * @var float|null
     *
     * @ORM\Column(name="mpc_current_sem", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpcCurrentSem;

    /**
     * @var float|null
     *
     * @ORM\Column(name="mps_current_sem", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpsCurrentSem;

    /**
     * @var float|null
     *
     * @ORM\Column(name="mpc_previous", type="float", precision=10, scale=0, nullable=true)
     */
    private $mpcPrevious;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_credits_capitalized_current_sem", type="integer", nullable=true)
     */
    private $nbCreditsCapitalizedCurrentSem;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_credtis_capitalized_previous", type="integer", nullable=true)
     */
    private $nbCredtisCapitalizedPrevious;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credit_registered", type="integer", nullable=true)
     */
    private $totalCreditRegistered;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credit_registered_current_sem", type="integer", nullable=true)
     */
    private $totalCreditRegisteredCurrentSem;

    /**
     * @var float|null
     *
     * @ORM\Column(name="validation_percentage", type="float", precision=10, scale=0, nullable=true)
     */
    private $validationPercentage;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_registered_current_class", type="integer", nullable=true)
     */
    private $totalCreditsRegisteredCurrentClass;

    /**
     * @var string|null
     *
     * @ORM\Column(name="academic_profile", type="string", length=45, nullable=true)
     */
    private $academicProfile;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_previous_class", type="integer", nullable=true)
     */
    private $totalCreditsPreviousClass;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_current_class", type="integer", nullable=true)
     */
    private $totalCreditsCurrentClass;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_credits_capitalized_previous_sem", type="integer", nullable=true)
     */
    private $nbCreditsCapitalizedPreviousSem;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credit_registered_previous_sem", type="integer", nullable=true)
     */
    private $totalCreditRegisteredPreviousSem;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_cycle_previous_year", type="integer", nullable=true)
     */
    private $totalCreditsCyclePreviousYear;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_cycle_current_year", type="integer", nullable=true)
     */
    private $totalCreditsCycleCurrentYear;

    /**
     * @var int|null
     *
     * @ORM\Column(name="counting_sem_registration", type="integer", nullable=true)
     */
    private $countingSemRegistration;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_registered_current_cycle", type="integer", nullable=true)
     */
    private $totalCreditsRegisteredCurrentCycle;

    /**
     * @var int|null
     *
     * @ORM\Column(name="total_credits_registered_previous_cycle", type="integer", nullable=true)
     */
    private $totalCreditsRegisteredPreviousCycle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="transcriptReferenceId", type="string", length=45, nullable=true)
     */
    private $transcriptreferenceid;

    /**
     * @var \Semester
     *
     * @ORM\ManyToOne(targetEntity="Semester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * })
     */
    private $semester;

    /**
     * @var \Student
     *
     * @ORM\ManyToOne(targetEntity="Student")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     * })
     */
    private $student;



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
     * Set mpcCurrentSem.
     *
     * @param float|null $mpcCurrentSem
     *
     * @return StudentSemRegistration
     */
    public function setMpcCurrentSem($mpcCurrentSem = null)
    {
        $this->mpcCurrentSem = $mpcCurrentSem;

        return $this;
    }

    /**
     * Get mpcCurrentSem.
     *
     * @return float|null
     */
    public function getMpcCurrentSem()
    {
        return $this->mpcCurrentSem;
    }

    /**
     * Set mpsCurrentSem.
     *
     * @param float|null $mpsCurrentSem
     *
     * @return StudentSemRegistration
     */
    public function setMpsCurrentSem($mpsCurrentSem = null)
    {
        $this->mpsCurrentSem = $mpsCurrentSem;

        return $this;
    }

    /**
     * Get mpsCurrentSem.
     *
     * @return float|null
     */
    public function getMpsCurrentSem()
    {
        return $this->mpsCurrentSem;
    }

    /**
     * Set mpcPrevious.
     *
     * @param float|null $mpcPrevious
     *
     * @return StudentSemRegistration
     */
    public function setMpcPrevious($mpcPrevious = null)
    {
        $this->mpcPrevious = $mpcPrevious;

        return $this;
    }

    /**
     * Get mpcPrevious.
     *
     * @return float|null
     */
    public function getMpcPrevious()
    {
        return $this->mpcPrevious;
    }

    /**
     * Set nbCreditsCapitalizedCurrentSem.
     *
     * @param int|null $nbCreditsCapitalizedCurrentSem
     *
     * @return StudentSemRegistration
     */
    public function setNbCreditsCapitalizedCurrentSem($nbCreditsCapitalizedCurrentSem = null)
    {
        $this->nbCreditsCapitalizedCurrentSem = $nbCreditsCapitalizedCurrentSem;

        return $this;
    }

    /**
     * Get nbCreditsCapitalizedCurrentSem.
     *
     * @return int|null
     */
    public function getNbCreditsCapitalizedCurrentSem()
    {
        return $this->nbCreditsCapitalizedCurrentSem;
    }

    /**
     * Set nbCredtisCapitalizedPrevious.
     *
     * @param int|null $nbCredtisCapitalizedPrevious
     *
     * @return StudentSemRegistration
     */
    public function setNbCredtisCapitalizedPrevious($nbCredtisCapitalizedPrevious = null)
    {
        $this->nbCredtisCapitalizedPrevious = $nbCredtisCapitalizedPrevious;

        return $this;
    }

    /**
     * Get nbCredtisCapitalizedPrevious.
     *
     * @return int|null
     */
    public function getNbCredtisCapitalizedPrevious()
    {
        return $this->nbCredtisCapitalizedPrevious;
    }

    /**
     * Set totalCreditRegistered.
     *
     * @param int|null $totalCreditRegistered
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditRegistered($totalCreditRegistered = null)
    {
        $this->totalCreditRegistered = $totalCreditRegistered;

        return $this;
    }

    /**
     * Get totalCreditRegistered.
     *
     * @return int|null
     */
    public function getTotalCreditRegistered()
    {
        return $this->totalCreditRegistered;
    }

    /**
     * Set totalCreditRegisteredCurrentSem.
     *
     * @param int|null $totalCreditRegisteredCurrentSem
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditRegisteredCurrentSem($totalCreditRegisteredCurrentSem = null)
    {
        $this->totalCreditRegisteredCurrentSem = $totalCreditRegisteredCurrentSem;

        return $this;
    }

    /**
     * Get totalCreditRegisteredCurrentSem.
     *
     * @return int|null
     */
    public function getTotalCreditRegisteredCurrentSem()
    {
        return $this->totalCreditRegisteredCurrentSem;
    }

    /**
     * Set validationPercentage.
     *
     * @param float|null $validationPercentage
     *
     * @return StudentSemRegistration
     */
    public function setValidationPercentage($validationPercentage = null)
    {
        $this->validationPercentage = $validationPercentage;

        return $this;
    }

    /**
     * Get validationPercentage.
     *
     * @return float|null
     */
    public function getValidationPercentage()
    {
        return $this->validationPercentage;
    }

    /**
     * Set totalCreditsRegisteredCurrentClass.
     *
     * @param int|null $totalCreditsRegisteredCurrentClass
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsRegisteredCurrentClass($totalCreditsRegisteredCurrentClass = null)
    {
        $this->totalCreditsRegisteredCurrentClass = $totalCreditsRegisteredCurrentClass;

        return $this;
    }

    /**
     * Get totalCreditsRegisteredCurrentClass.
     *
     * @return int|null
     */
    public function getTotalCreditsRegisteredCurrentClass()
    {
        return $this->totalCreditsRegisteredCurrentClass;
    }

    /**
     * Set academicProfile.
     *
     * @param string|null $academicProfile
     *
     * @return StudentSemRegistration
     */
    public function setAcademicProfile($academicProfile = null)
    {
        $this->academicProfile = $academicProfile;

        return $this;
    }

    /**
     * Get academicProfile.
     *
     * @return string|null
     */
    public function getAcademicProfile()
    {
        return $this->academicProfile;
    }

    /**
     * Set totalCreditsPreviousClass.
     *
     * @param int|null $totalCreditsPreviousClass
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsPreviousClass($totalCreditsPreviousClass = null)
    {
        $this->totalCreditsPreviousClass = $totalCreditsPreviousClass;

        return $this;
    }

    /**
     * Get totalCreditsPreviousClass.
     *
     * @return int|null
     */
    public function getTotalCreditsPreviousClass()
    {
        return $this->totalCreditsPreviousClass;
    }

    /**
     * Set totalCreditsCurrentClass.
     *
     * @param int|null $totalCreditsCurrentClass
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsCurrentClass($totalCreditsCurrentClass = null)
    {
        $this->totalCreditsCurrentClass = $totalCreditsCurrentClass;

        return $this;
    }

    /**
     * Get totalCreditsCurrentClass.
     *
     * @return int|null
     */
    public function getTotalCreditsCurrentClass()
    {
        return $this->totalCreditsCurrentClass;
    }

    /**
     * Set nbCreditsCapitalizedPreviousSem.
     *
     * @param int|null $nbCreditsCapitalizedPreviousSem
     *
     * @return StudentSemRegistration
     */
    public function setNbCreditsCapitalizedPreviousSem($nbCreditsCapitalizedPreviousSem = null)
    {
        $this->nbCreditsCapitalizedPreviousSem = $nbCreditsCapitalizedPreviousSem;

        return $this;
    }

    /**
     * Get nbCreditsCapitalizedPreviousSem.
     *
     * @return int|null
     */
    public function getNbCreditsCapitalizedPreviousSem()
    {
        return $this->nbCreditsCapitalizedPreviousSem;
    }

    /**
     * Set totalCreditRegisteredPreviousSem.
     *
     * @param int|null $totalCreditRegisteredPreviousSem
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditRegisteredPreviousSem($totalCreditRegisteredPreviousSem = null)
    {
        $this->totalCreditRegisteredPreviousSem = $totalCreditRegisteredPreviousSem;

        return $this;
    }

    /**
     * Get totalCreditRegisteredPreviousSem.
     *
     * @return int|null
     */
    public function getTotalCreditRegisteredPreviousSem()
    {
        return $this->totalCreditRegisteredPreviousSem;
    }

    /**
     * Set totalCreditsCyclePreviousYear.
     *
     * @param int|null $totalCreditsCyclePreviousYear
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsCyclePreviousYear($totalCreditsCyclePreviousYear = null)
    {
        $this->totalCreditsCyclePreviousYear = $totalCreditsCyclePreviousYear;

        return $this;
    }

    /**
     * Get totalCreditsCyclePreviousYear.
     *
     * @return int|null
     */
    public function getTotalCreditsCyclePreviousYear()
    {
        return $this->totalCreditsCyclePreviousYear;
    }

    /**
     * Set totalCreditsCycleCurrentYear.
     *
     * @param int|null $totalCreditsCycleCurrentYear
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsCycleCurrentYear($totalCreditsCycleCurrentYear = null)
    {
        $this->totalCreditsCycleCurrentYear = $totalCreditsCycleCurrentYear;

        return $this;
    }

    /**
     * Get totalCreditsCycleCurrentYear.
     *
     * @return int|null
     */
    public function getTotalCreditsCycleCurrentYear()
    {
        return $this->totalCreditsCycleCurrentYear;
    }

    /**
     * Set countingSemRegistration.
     *
     * @param int|null $countingSemRegistration
     *
     * @return StudentSemRegistration
     */
    public function setCountingSemRegistration($countingSemRegistration = null)
    {
        $this->countingSemRegistration = $countingSemRegistration;

        return $this;
    }

    /**
     * Get countingSemRegistration.
     *
     * @return int|null
     */
    public function getCountingSemRegistration()
    {
        return $this->countingSemRegistration;
    }

    /**
     * Set totalCreditsRegisteredCurrentCycle.
     *
     * @param int|null $totalCreditsRegisteredCurrentCycle
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsRegisteredCurrentCycle($totalCreditsRegisteredCurrentCycle = null)
    {
        $this->totalCreditsRegisteredCurrentCycle = $totalCreditsRegisteredCurrentCycle;

        return $this;
    }

    /**
     * Get totalCreditsRegisteredCurrentCycle.
     *
     * @return int|null
     */
    public function getTotalCreditsRegisteredCurrentCycle()
    {
        return $this->totalCreditsRegisteredCurrentCycle;
    }

    /**
     * Set totalCreditsRegisteredPreviousCycle.
     *
     * @param int|null $totalCreditsRegisteredPreviousCycle
     *
     * @return StudentSemRegistration
     */
    public function setTotalCreditsRegisteredPreviousCycle($totalCreditsRegisteredPreviousCycle = null)
    {
        $this->totalCreditsRegisteredPreviousCycle = $totalCreditsRegisteredPreviousCycle;

        return $this;
    }

    /**
     * Get totalCreditsRegisteredPreviousCycle.
     *
     * @return int|null
     */
    public function getTotalCreditsRegisteredPreviousCycle()
    {
        return $this->totalCreditsRegisteredPreviousCycle;
    }

    /**
     * Set transcriptreferenceid.
     *
     * @param string|null $transcriptreferenceid
     *
     * @return StudentSemRegistration
     */
    public function setTranscriptreferenceid($transcriptreferenceid = null)
    {
        $this->transcriptreferenceid = $transcriptreferenceid;

        return $this;
    }

    /**
     * Get transcriptreferenceid.
     *
     * @return string|null
     */
    public function getTranscriptreferenceid()
    {
        return $this->transcriptreferenceid;
    }

    /**
     * Set semester.
     *
     * @param \Semester|null $semester
     *
     * @return StudentSemRegistration
     */
    public function setSemester(\Semester $semester = null)
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * Get semester.
     *
     * @return \Semester|null
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * Set student.
     *
     * @param \Student|null $student
     *
     * @return StudentSemRegistration
     */
    public function setStudent(\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student.
     *
     * @return \Student|null
     */
    public function getStudent()
    {
        return $this->student;
    }
}
