<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Teacher\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Hydrator\ReflectionHydrator;
use Application\Entity\AcademicRanck;


class GradeController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager;  
        $this->sessionContainer = $sessionContainer;
    }
    
    
 
    
    public function get($id)
    {        
        
        if(is_numeric($id))
        {
            $rank = $this->entityManager->getRepository(AcademicRanck::class)->find($id);

                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($rank);
                $rank = $data;

            return new JsonModel([
                    $rank
            ]);
        }


        
        //return $faculties;
    }
    public function getList()
    {       
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
        $classes = $this->entityManager->getRepository(ClassListView::class)->findAll();
                
            foreach($classes as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $classes[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $classes  
                
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

    }    
    public function getFaculty($school)
    {
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBySchool($school);
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        return $faculties;
        
    }
    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
            $message = false;

            $rank= new AcademicRanck();
            $rank->setName($data['name']);
            $rank->setCode($data['code']);
            $rank->setPaymentRate($data["paymentRate"]);

            $this->entityManager->persist($rank);


            //$degree =$this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']);
            
            
            //$classe->setDegree($degree);  
            
            $this->entityManager->flush();
            $message = true;
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                 $message
            ]);  
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        
        
        
    }
  
    public function delete($id)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $rank = $this->entityManager->getRepository(AcademicRanck::class)->findOneById($id);
            if($rank )
            {
                
                $this->entityManager->remove($rank );
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }


        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);
    }
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
          
            $rank =$this->entityManager->getRepository(AcademicRanck::class)->findOneById($id);
            $rank->setName($data['name']);
            $rank->setCode($data['code']);
            $rank->setPaymentRate($data['paymentRate']);

            
            $this->entityManager->flush();
            
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);

        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
}
