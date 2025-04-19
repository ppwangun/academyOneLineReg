<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * AcademicYear
 *
 * @ORM\Table(name="academic_year", uniqueConstraints={@ORM\UniqueConstraint(name="code_UNIQUE", columns={"code"})}, indexes={@ORM\Index(name="fk_academic_year_academic_year1_idx", columns={"previous_year"})})
 * @ORM\Entity
 */
class AcademicYear
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="starting_date", type="datetime", nullable=true)
     */
    private $startingDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="ending_date", type="datetime", nullable=true)
     */
    private $endingDate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="is_default", type="integer", nullable=true)
     */
    private $isDefault;

    /**
     * @var int|null
     *
     * @ORM\Column(name="online_registration_default_year", type="integer", nullable=true)
     */
    private $onlineRegistrationDefaultYear = '0';

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status = '0';

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="admission_starting_date", type="datetime", nullable=true)
     */
    private $admissionStartingDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="admission_ending_date", type="datetime", nullable=true)
     */
    private $admissionEndingDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="admin_registration_starting_date", type="datetime", nullable=true)
     */
    private $adminRegistrationStartingDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="admin_registration_ending_date", type="datetime", nullable=true)
     */
    private $adminRegistrationEndingDate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="previous_year", type="integer", nullable=true)
     */
    private $previousYear;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prefix", type="string", length=4, nullable=true)
     */
    private $prefix;

    /**
     * @var string|null
     *
     * @ORM\Column(name="entrance_fees", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $entranceFees;

    /**
     * @var string|null
     *
     * @ORM\Column(name="registration_fees", type="decimal", precision=10, scale=0, nullable=true)
     */
    private $registrationFees;

    /**
     * @var int|null
     *
     * @ORM\Column(name="totalSmsSent", type="integer", nullable=true)
     */
    private $totalsmssent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ClassOfStudy", mappedBy="academicYear")
     */
    private $classOfStudy = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classOfStudy = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $code
     *
     * @return AcademicYear
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return AcademicYear
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
     * Set startingDate.
     *
     * @param \DateTime|null $startingDate
     *
     * @return AcademicYear
     */
    public function setStartingDate($startingDate = null)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    /**
     * Get startingDate.
     *
     * @return \DateTime|null
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * Set endingDate.
     *
     * @param \DateTime|null $endingDate
     *
     * @return AcademicYear
     */
    public function setEndingDate($endingDate = null)
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    /**
     * Get endingDate.
     *
     * @return \DateTime|null
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * Set isDefault.
     *
     * @param int|null $isDefault
     *
     * @return AcademicYear
     */
    public function setIsDefault($isDefault = null)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return int|null
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set onlineRegistrationDefaultYear.
     *
     * @param int|null $onlineRegistrationDefaultYear
     *
     * @return AcademicYear
     */
    public function setOnlineRegistrationDefaultYear($onlineRegistrationDefaultYear = null)
    {
        $this->onlineRegistrationDefaultYear = $onlineRegistrationDefaultYear;

        return $this;
    }

    /**
     * Get onlineRegistrationDefaultYear.
     *
     * @return int|null
     */
    public function getOnlineRegistrationDefaultYear()
    {
        return $this->onlineRegistrationDefaultYear;
    }

    /**
     * Set status.
     *
     * @param int|null $status
     *
     * @return AcademicYear
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set admissionStartingDate.
     *
     * @param \DateTime|null $admissionStartingDate
     *
     * @return AcademicYear
     */
    public function setAdmissionStartingDate($admissionStartingDate = null)
    {
        $this->admissionStartingDate = $admissionStartingDate;

        return $this;
    }

    /**
     * Get admissionStartingDate.
     *
     * @return \DateTime|null
     */
    public function getAdmissionStartingDate()
    {
        return $this->admissionStartingDate;
    }

    /**
     * Set admissionEndingDate.
     *
     * @param \DateTime|null $admissionEndingDate
     *
     * @return AcademicYear
     */
    public function setAdmissionEndingDate($admissionEndingDate = null)
    {
        $this->admissionEndingDate = $admissionEndingDate;

        return $this;
    }

    /**
     * Get admissionEndingDate.
     *
     * @return \DateTime|null
     */
    public function getAdmissionEndingDate()
    {
        return $this->admissionEndingDate;
    }

    /**
     * Set adminRegistrationStartingDate.
     *
     * @param \DateTime|null $adminRegistrationStartingDate
     *
     * @return AcademicYear
     */
    public function setAdminRegistrationStartingDate($adminRegistrationStartingDate = null)
    {
        $this->adminRegistrationStartingDate = $adminRegistrationStartingDate;

        return $this;
    }

    /**
     * Get adminRegistrationStartingDate.
     *
     * @return \DateTime|null
     */
    public function getAdminRegistrationStartingDate()
    {
        return $this->adminRegistrationStartingDate;
    }

    /**
     * Set adminRegistrationEndingDate.
     *
     * @param \DateTime|null $adminRegistrationEndingDate
     *
     * @return AcademicYear
     */
    public function setAdminRegistrationEndingDate($adminRegistrationEndingDate = null)
    {
        $this->adminRegistrationEndingDate = $adminRegistrationEndingDate;

        return $this;
    }

    /**
     * Get adminRegistrationEndingDate.
     *
     * @return \DateTime|null
     */
    public function getAdminRegistrationEndingDate()
    {
        return $this->adminRegistrationEndingDate;
    }

    /**
     * Set previousYear.
     *
     * @param int|null $previousYear
     *
     * @return AcademicYear
     */
    public function setPreviousYear($previousYear = null)
    {
        $this->previousYear = $previousYear;

        return $this;
    }

    /**
     * Get previousYear.
     *
     * @return int|null
     */
    public function getPreviousYear()
    {
        return $this->previousYear;
    }

    /**
     * Set prefix.
     *
     * @param string|null $prefix
     *
     * @return AcademicYear
     */
    public function setPrefix($prefix = null)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix.
     *
     * @return string|null
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set entranceFees.
     *
     * @param string|null $entranceFees
     *
     * @return AcademicYear
     */
    public function setEntranceFees($entranceFees = null)
    {
        $this->entranceFees = $entranceFees;

        return $this;
    }

    /**
     * Get entranceFees.
     *
     * @return string|null
     */
    public function getEntranceFees()
    {
        return $this->entranceFees;
    }

    /**
     * Set registrationFees.
     *
     * @param string|null $registrationFees
     *
     * @return AcademicYear
     */
    public function setRegistrationFees($registrationFees = null)
    {
        $this->registrationFees = $registrationFees;

        return $this;
    }

    /**
     * Get registrationFees.
     *
     * @return string|null
     */
    public function getRegistrationFees()
    {
        return $this->registrationFees;
    }

    /**
     * Set totalsmssent.
     *
     * @param int|null $totalsmssent
     *
     * @return AcademicYear
     */
    public function setTotalsmssent($totalsmssent = null)
    {
        $this->totalsmssent = $totalsmssent;

        return $this;
    }

    /**
     * Get totalsmssent.
     *
     * @return int|null
     */
    public function getTotalsmssent()
    {
        return $this->totalsmssent;
    }

    /**
     * Add classOfStudy.
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return AcademicYear
     */
    public function addClassOfStudy(\ClassOfStudy $classOfStudy)
    {
        $this->classOfStudy[] = $classOfStudy;

        return $this;
    }

    /**
     * Remove classOfStudy.
     *
     * @param \ClassOfStudy $classOfStudy
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeClassOfStudy(\ClassOfStudy $classOfStudy)
    {
        return $this->classOfStudy->removeElement($classOfStudy);
    }

    /**
     * Get classOfStudy.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClassOfStudy()
    {
        return $this->classOfStudy;
    }
}
