<?php
namespace Application\Entity;



use Doctrine\ORM\Mapping as ORM;


/**
 * ClassListByUserView
 *
 * @ORM\Table(name="class_list_by_user_view")
 * @ORM\Entity
 */
class ClassListByUserView
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
     * @ORM\Column(name="user_id", type="integer",  nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="option", type="string", length=255, nullable=true)
     */
    private $option;
    
     /**
     * @var integer
     *
     * @ORM\Column(name="study_level", type="integer", nullable=true)
     */
    private $studyLevel = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_end_cycle", type="integer", nullable=true)
     */
    private $isEndCycle = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="is_end_degree_training", type="integer", nullable=true)
     */
    private $isEndDegreeTraining = '0';


    /**
     * @var integer
     *
     * @ORM\Column(name="degree_id", type="integer", nullable=true)
     */
    private $degreeId;

    /**
     * @var string
     *
     * @ORM\Column(name="degree_code", type="string", length=45, nullable=true)
     */
    private $degreeCode;


    /**
     * @var string
     *
     * @ORM\Column(name="degree_name", type="string", length=255, nullable=true)
     */
    private $degreeName;
    /**
     * @var integer
     *
     * @ORM\Column(name="cycle_id", type="integer", nullable=true)
     */
    private $cycleId;    
    /**
     * @var string
     *
     * @ORM\Column(name="cycle_code", type="string", length=45, nullable=true)
     */
   // private $cycleCode;


    /**
     * @var string
     *
     * @ORM\Column(name="cycle_name", type="string", length=255, nullable=true)
     */
    //private $cycleName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="filiere_code", type="string", length=45, nullable=true)
     */
    private $filiereCode;


    /**
     * @var string
     *
     * @ORM\Column(name="filiere_name", type="string", length=255, nullable=true)
     */
    private $filiereName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="faculty_code", type="string", length=45, nullable=true)
     */
    private $facultyCode;


    /**
     * @var string
     *
     * @ORM\Column(name="faculty_name", type="string", length=255, nullable=true)
     */
    private $facultyName;
    
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
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
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
     * Get option
     *
     * @return string
     */
    public function getOptioni()
    {
        return $this->option;
    } 
    
     /**
     * Get isEndCycle
     *
     * @return integer
     */
    public function getIsEndCycle()
    {
        return $this->isEndCycle;
    }
    
     /**
     * Get isEndDegreeTraining
     *
     * @return integer
     */
    public function getIsEndDegreeTraining()
    {
        return $this->isEndDegreeTraining;
    }
    
     /**
     * Get degreeCode
     *
     * @return string
     */
    public function getDegreeCode()
    {
        return $this->degreCode;
    }
    
     /**
     * Get degreeName
     *
     * @return string
     */
    public function getDegreeName()
    {
        return $this->degreeName;
    }
    
     /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
     /**
     * Get cycleName
     *
     * @return string
     */
    /*public function getCycleName()
    {
        return $this->cycleName;
    }*/
    
     /**
     * Get filiereCode
     *
     * @return string
     */
    public function getFiliereCode()
    {
        return $this->filiereCode;
    }
    
     /**
     * Get filiereName
     *
     * @return string
     */
    public function getFiliereName()
    {
        return $this->filiereName;
    }
    
     /**
     * Get facultyCode
     *
     * @return string
     */
    public function getFacultyCode()
    {
        return $this->facultyCode;
    }
    
     /**
     * Get facultyName
     *
     * @return string
     */
    public function getFacultyName()
    {
        return $this->facultyName;
    }
  
}
