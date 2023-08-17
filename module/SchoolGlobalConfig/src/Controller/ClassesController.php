<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Hydrator\Reflection as ReflectionHydrator;
use Application\Entity\Degree;
use Application\Entity\TrainingCurriculum;
use Application\Entity\User;
use Application\Entity\ClassOfStudy;
use Application\Entity\ClassListView;
use Application\Entity\ClassListByUserView;
use Zend\Hydrator\Aggregate\AggregateHydrator;

class ClassesController extends AbstractRestfulController
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
            $classe = $this->entityManager->getRepository(ClassListView::class)->find($id);

                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($classe);
                $classe = $data;

            return new JsonModel([
                    $classe
            ]);
        }
        //Showing only classes managed by the current logged in user
        
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
         
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) {
                
                $query = $this->entityManager->createQuery('SELECT c.id,c.code,c.name,c.studyLevel,c.isEndCycle,c.isEndDegreeTraining FROM Application\Entity\ClassListView c'
                        .' WHERE c.code LIKE :code');
                $query->setParameter('code', '%'.$id.'%');
                //$query->setParameter('userId', $userId);
                $classe = $query->getResult();  
                

            }
            else{
                $query = $this->entityManager->createQuery('SELECT c.id,c.userId,c.code,c.name,c.studyLevel,c.isEndCycle,c.isEndDegreeTraining FROM Application\Entity\ClassListByUserView c'
                        .' WHERE c.code LIKE :code AND c.userId = :userId');
                $query->setParameter('code', '%'.$id.'%');
                $query->setParameter('userId', $userId);
                $classe = $query->getResult();               
            }
            
            
            return new JsonModel([
                    $classe
            ]);
        
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
       
            $classe= new ClassOfStudy();
            $classe->setName($data['name']);
            $classe->setCode($data['code']);
            $classe->setIsEndCycle($data['isEndCycle']);
            $classe->setIsEndDegreeTraining($data['isEndDegreeTraining']);
            $classe->setStudyLevel($data['studyLevel']);
            
            if(!is_null($data['cycleId']))
            {
                $cycle = $this->entityManager->getRepository(TrainingCurriculum::class)->findOneById($data['cycleId']);
                $classe->setCycle($cycle);
            }
            $degree =$this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']);
            
            
            $classe->setDegree($degree);  
            $this->entityManager->persist($classe);
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
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($id);
            if($classe )
            {
                
                $this->entityManager->remove($classe );
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
            $data= $data['data'];
            
            $classe =$this->entityManager->getRepository(ClassOfStudy::class)->findOneById($id);
            $classe->setName($data['name']);
            $classe->setCode($data['code']);
            $classe->setStudyLevel($data['studyLevel']);
            $classe->setIsEndCycle($data['isEndCycle']);
            $classe->setIsEndDegreeTraining($data['isEndDegreeTraining']);
            $classe->setDegree($this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']));
            $classe->setCycle($this->entityManager->getRepository(TrainingCurriculum::class)->findOneById($data['cycleId']));
            
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
