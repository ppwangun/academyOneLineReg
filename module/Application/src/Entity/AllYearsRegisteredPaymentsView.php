<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="all_years_registered_payments_view", uniqueConstraints={@ORM\UniqueConstraint(name="matricule_UNIQUE", columns={"matricule"})})
 * @ORM\Entity
 */
class AllYearsRegisteredPaymentsView
{
    /**
    * @var string
    *
    * @ORM\Column(name="id", type="string", nullable=false)
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    private $id;
    
    /**
    * @var integer
    *
    * @ORM\Column(name="acadYrId", type="integer", nullable=false)
    */
    private $acadYrId;    

    /**
    * @var integer
    *
    * @ORM\Column(name="registration_id", type="integer", nullable=false)
    */
    private $registrationId;
    /**
    * @var string
    *
    * @ORM\Column(name="matricule", type="string", length=45, nullable=true)
    */
    private $matricule;
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom", type="string", length=255, nullable=true)
    */
    private $nom;
    
    /**
    * @var string
    *
    * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
    */
    private $prenom;

    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", nullable=true)
    */
    private $class;

    /**
    * @var string
    *
    * @ORM\Column(name="degree_name", type="string", nullable=true)
    */
    private $degreeName;
        
    /**
    * @var string
    *
    * @ORM\Column(name="filiere_name", type="string", nullable=true)
    */
    private $filiereName; 
    
    /**
    * @var string
    *
    * @ORM\Column(name="faculty_name", type="string", nullable=true)
    */
    private $facultyName;
    
    /**
    * @var decimal
    *
    * @ORM\Column(name="fees_dotation", type="decimal", nullable=true)
    */
    private $feesDotation;

    /**
    * @var decimal
    *
    * @ORM\Column(name="fees_paid", type="decimal", nullable=true)
    */
    private $feesPaid;  
    
    /**
    * @var decimal
    *
    * @ORM\Column(name="fees_debt", type="decimal", nullable=true)
    */
    private $feesDebt;  

    /**
    * @var decimal
    *
    * @ORM\Column(name="fees_balance", type="decimal", nullable=true)
    */
    private $feesBalance; 

    /**
    * @var string
    *
    * @ORM\Column(name="moratorium_autorization", type="string", nullable=true)
    */
    private $moratoriumAutorization;
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
     * Get registrationId
     *
     * @return integer
     */
    public function getRegistrationId()
    {
        return $this->registrationId;
    }

     /**
     * Get feesPaid
     *
     * @return decimal
     */
    public function getFeesPaid()
    {
        return $this->feesPaid;
    }
    
     /**
     * Get feesBalance
     *
     * @return decimal
     */
    public function getFeesBalance()
    {
        return $this->feesBalance;
    }

     /**
     * Get feesDebt
     *
     * @return decimal
     */
    public function getFeesDebt()
    {
        return $this->feesDebt;
    }     
}

