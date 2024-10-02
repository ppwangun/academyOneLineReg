<?php


namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Application\Entity\AcademicYear;

/**
 * AcademicYear
 *
 * @ORM\Table(name="academic_year")
 * @ORM\Entity
 */
class AcademicYear
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
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="starting_date", type="datetime", nullable=true)
     */
    private $startingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ending_date", type="datetime", nullable=true)
     */
    private $endingDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_default", type="integer", nullable=true)
     */
    private $isDefault;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="online_registration_default_year", type="integer", nullable=true)
     */
    private $onlineRegistrationDefaultYear;    

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="totalSmsSent", type="integer", nullable=true)
     */
    private $totalSmsSent;    

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="admission_starting_date", type="datetime", nullable=true)
     */
    private $admissionStartingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="admission_ending_date", type="datetime", nullable=true)
     */
    private $admissionEndingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="admin_registration_starting_date", type="datetime", nullable=true)
     */
    private $adminRegistrationStartingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="admin_registration_ending_date", type="datetime", nullable=true)
     */
    private $adminRegistrationEndingDate;
    
    /**
     * @var string
     *
     * @ORM\Column(name="prefix", type="string", length=255, nullable=true)
     */
    private $prefix; 
    
     /**
     * @var float
     *
     * @ORM\Column(name="entrance_fees", type="float", precision=10, scale=0, nullable=true)
     */
    private $entranceFees; 
    
     /**
     * @var float
     *
     * @ORM\Column(name="registration_fees", type="float", precision=10, scale=0, nullable=true)
     */
    private $registrationFees;    

    /**
     * @var \AcademicYear
     *
     * @ORM\ManyToOne(targetEntity="AcademicYear")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="previous_year", referencedColumnName="id")
     * })
     */
    private $previousYear;

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
     * Set code
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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AcademicYear
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startingDate
     *
     * @param \DateTime $startingDate
     *
     * @return AcademicYear
     */
    public function setStartingDate($startingDate)
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    /**
     * Get startingDate
     *
     * @return \DateTime
     */
    public function getStartingDate()
    {
        return $this->startingDate;
    }

    /**
     * Set endingDate
     *
     * @param \DateTime $endingDate
     *
     * @return AcademicYear
     */
    public function setEndingDate($endingDate)
    {
        $this->endingDate = $endingDate;

        return $this;
    }

    /**
     * Get endingDate
     *
     * @return \DateTime
     */
    public function getEndingDate()
    {
        return $this->endingDate;
    }

    /**
     * Set isDefault
     *
     * @param integer $isDefault
     *
     * @return AcademicYear
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault
     *
     * @return integer
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Get onlineRegistrationDefaultYear
     *
     * @return integer
     */    
    public function getOnlineRegistrationDefaultYear() {
        return $this->onlineRegistrationDefaultYear;
    }
    
    
    /**
     * Set onlineRegistrationDefaultYear
     *
     * @param integer $onlineRegistrationDefaultYear
     *
     * @return AcademicYear
     */    

    public function setOnlineRegistrationDefaultYear($onlineRegistrationDefaultYear) {
        $this->onlineRegistrationDefaultYear = $onlineRegistrationDefaultYear;
    }    
    

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AcademicYear
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
     * Set totalSmsSent
     *
     * @param integer $totalSmsSent
     *
     * @return AcademicYear
     */
    public function setTotalSmsSent($totalSmsSent)
    {
        $this->totalSmsSent = $totalSmsSent;

        return $this;
    }

    /**
     * Get totalSmsSent
     *
     * @return integer
     */
    public function getTotalSmsSent()
    {
        return $this->totalSmsSent;
    }    

    /**
     * Set admissionStartingDate
     *
     * @param \DateTime $admissionStartingDate
     *
     * @return AcademicYear
     */
    public function setAdmissionStartingDate($admissionStartingDate)
    {
        $this->admissionStartingDate = $admissionStartingDate;

        return $this;
    }

    /**
     * Get admissionStartingDate
     *
     * @return \DateTime
     */
    public function getAdmissionStartingDate()
    {
        return $this->admissionStartingDate;
    }

    /**
     * Set admissionEndingDate
     *
     * @param \DateTime $admissionEndingDate
     *
     * @return AcademicYear
     */
    public function setAdmissionEndingDate($admissionEndingDate)
    {
        $this->admissionEndingDate = $admissionEndingDate;

        return $this;
    }

    /**
     * Get admissionEndingDate
     *
     * @return \DateTime
     */
    public function getAdmissionEndingDate()
    {
        return $this->admissionEndingDate;
    }

    /**
     * Set adminRegistrationStartingDate
     *
     * @param \DateTime $adminRegistrationStartingDate
     *
     * @return AcademicYear
     */
    public function setAdminRegistrationStartingDate($adminRegistrationStartingDate)
    {
        $this->adminRegistrationStartingDate = $adminRegistrationStartingDate;

        return $this;
    }

    /**
     * Get adminRegistrationStartingDate
     *
     * @return \DateTime
     */
    public function getAdminRegistrationStartingDate()
    {
        return $this->adminRegistrationStartingDate;
    }

    /**
     * Set adminRegistrationEndingDate
     *
     * @param \DateTime $adminRegistrationEndingDate
     *
     * @return AcademicYear
     */
    public function setAdminRegistrationEndingDate($adminRegistrationEndingDate)
    {
        $this->adminRegistrationEndingDate = $adminRegistrationEndingDate;

        return $this;
    }

    /**
     * Get adminRegistrationEndingDate
     *
     * @return \DateTime
     */
    public function getAdminRegistrationEndingDate()
    {
        return $this->adminRegistrationEndingDate;
    }
 /**
     * Set prefix
     *
     * @param string $prefix
     *
     * @return AcademicYear
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }    
    /**
     * Set entranceFees
     *
     * @param float $entranceFees
     *
     * @return AcademicYear
     */
    public function setFeesPaid($entranceFees)
    {
        $this->entranceFees = $entranceFees;

        return $this;
    }
    
    /**
     * Get entranceFees
     *
     * @return float
     */
    public function getEntranceFees()
    {
        return $this->EntranceFees;
    } 
    
    /**
     * Set registrationFees
     *
     * @param float $registrationFees
     *
     * @return AdminRegistration
     */
    public function setRegistrationFees($registrationFees)
    {
        $this->registrationFees = $registrationFees;

        return $this;
    }
    
    /**
     * Get registrationFees
     *
     * @return float
     */
    public function getRegistrationFees()
    {
        return $this->registrationFees;
    }    

    /**
     * Set previousYear
     *
     * @param AcademicYear $previousYear
     *
     * @return AcademicYear
     */
    public function setPreviousYear(AcademicYear $previousYear = null)
    {
        $this->previousYear = $previousYear;

        return $this;
    }

    /**
     * Get previousYear
     *
     * @return AcademicYear
     */
    public function getPreviousYear()
    {
        return $this->previousYear;
    }    
}
