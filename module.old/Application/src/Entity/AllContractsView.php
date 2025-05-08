<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AllContractsView
 *
 * @ORM\Table(name="all_contracts_view")
 * @ORM\Entity
 */
class AllContractsView
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
    * @ORM\Column(name="coshs", type="integer", nullable=false)
    */
    private $coshs;
    
    /**
    * @var string
    *
    * @ORM\Column(name="code_ue", type="string", length=45, nullable=true)
    */
    private $codeUe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="degree_code", type="string", length=45, nullable=true)
    */
    private $degreeCode;    
    
    
    /**
    * @var string
    *
    * @ORM\Column(name="nom_ue", type="string", length=255, nullable=true)
    */
    private $nomUe;
    
        /**
    * @var float
    *
    * @ORM\Column(name="credits", type="float",  nullable=true)
    */
    private $credits;
    /**
    * @var integer
    *
    * @ORM\Column(name="total_hrs", type="float",  nullable=true)
    */
    private $totalHrs;  
    
    /**
    * @var integer
    *
    * @ORM\Column(name="cm_hrs", type="float",  nullable=true)
    */
    private $cmHrs;  
    
    /**
    * @var integer
    *
    * @ORM\Column(name="td_hrs", type="float",  nullable=true)
    */
    private $tdHrs; 

    /**
    * @var integer
    *
    * @ORM\Column(name="tp_hrs", type="float",  nullable=true)
    */
    private $tpHrs;     
     
    /**
    * @var string
    *
    * @ORM\Column(name="classe", type="string", length=255, nullable=true)
    */
    private $classe;
    
    /**
    * @var string
    *
    * @ORM\Column(name="teacher_name", type="string", length=255, nullable=true)
    */
    private $teacherName;   
    
       /**
    * @var integer
    *
    * @ORM\Column(name="study_level", type="integer", nullable=true)
    */
    private $studyLevel;
    
    /**
    * @var string
    *
    * @ORM\Column(name="semester", type="string", nullable=true)
    */
    private $semester;
 
    /**
    * @var integer
    *
    * @ORM\Column(name="semID", type="integer", nullable=true)
    */
    private $semId;
    /**
    * @var integer
    *
    * @ORM\Column(name="sem_ranking", type="integer", nullable=true)
    */
    private $semRanking;
    /**
    * @var integer
    *
    * @ORM\Column(name="teacher", type="integer", nullable=true)
    */
    private $teacher;   
    
    
    
    
}

