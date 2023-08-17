<?php

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator as ReflectionHydrator;

use Application\Entity\Faculty;
use Application\Entity\FieldOfStudy;
use Application\Entity\Degree;
use Application\Entity\DegreeHasCourseCategory;
use Application\Entity\CourseCategory;

class OnlineRegController extends AbstractActionController
{
    private $entityManager;
    
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    public function searchFilByFacultyAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();            
            $fac= $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findBy(array('faculty'=>$fac),array("name"=>"ASC"));
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $data['fac_id'] = $value->getFaculty()->getId();
               ($value->getDepartment())? $data['dpt_id'] = $value->getDepartment()->getId():$data['dpt_id']=-1;
                $filieres[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $filieres
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }
    
    public function searchDegreeAction()
    {
        $data = $this->params()->fromQuery();
               
        $cycleFormation = $this->entityManager->getRepository(CourseCategory::class)->find($data["cycle_id"]);
        $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->find($data["fil_id"]);
        $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findBy(array("fieldOfStudy"=>$filiere,"courseCategory"=>$cycleFormation));
        
        foreach ($degreeCategory as $key=>$value)
        { 
            $degree = $value->getDegree();
            
            if($degree)
            {
                
                $data = $this->entityManager->getRepository(Degree::class)->find($degree->getId());
                $hydrator = new ReflectionHydrator();
                $data1 = $hydrator->extract($data); 
                
               $degreeCategory[$key] =  array("id"=>$data->getId(),"name"=>$data->getName(),"code"=>$data->getCode());
                
            }

        }

        return new JsonModel([
                $degreeCategory
        ]);
        
        //return $faculties;
    }   
    
    
   public function searchCycleFormationAction()
    {
        $cycles = $this->entityManager->getRepository(CourseCategory::class)->findAll([],["name"=>"ASC"]);
        
     
        foreach($cycles as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $cycles[$key] = $data;
        }
        
        return new JsonModel([
                $cycles
        ]);
        
        //return $faculties;
    }
}
