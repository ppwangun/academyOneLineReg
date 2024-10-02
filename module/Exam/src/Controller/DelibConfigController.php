<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Exam;
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\TrainingCurriculum;
use Application\Entity\Deliberation;
use Application\Entity\DelibrationCondition;


class DelibCOnfigController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }

    public function get($id)
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {     
            $delibDetails = $this->entityManager->getRepository(Deliberation::class)->find($id);

                $hydrator = new ReflectionHydrator();
                $delibDetails = $hydrator->extract($delibDetails);


            $output = new JsonModel([
                    $delibDetails
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $deliberations = $this->entityManager->getRepository(Deliberation::class)->findAll();

            foreach($deliberations as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $deliberations[$key] = $data;
            }

            $this->entityManager->getConnection()->commit();
 
            $output = new JsonModel([
                    $deliberations
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function create($data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $msge =false;
            $delib = new Deliberation();
            $delib->setLabel($data["delibName"]);
            $delib->setDelibCondition($data["condition"]);
            $this->entityManager->persist($delib);
            $this->entityManager->flush();
            
            if(isset($data["cycle"])&&!is_null($data["cycle"]))
            {
                $cycles = $this->entityManager->getRepository(TrainingCurriculum::class)->findByCycleLevel($data["cycle"]);
                foreach($cycles as $cycle)
                {
                    $classes= $this->entityManager->getRepository(ClassOfStudy::class)->findByCycle($cycle);
                    foreach($classes as $classe)
                    {

                       $classe->setDeliberation($delib); 
                       $this->entityManager->flush();

                    }
                }
            }
            elseif(isset($data["classes"])&&!is_null($data["classes"])){
                foreach($data["classes"] as $classe_id)
                {
                   $class= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_id);
                   $class->setDeliberation($delib); 
                   $this->entityManager->flush();
                   
                   
                }                

                
            }
            $msge=true;
            
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   $msge
            ]);        
    }
    catch(Exception $e)
    {
        $this->entityManager->getConnection()->rollBack();
        throw $e;

    }
}
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           $msge =false;

            $delib = $this->entityManager->getRepository(Deliberation::class)->find($data['id']);
            $delib->setLabel($data["delibName"]);
            $delib->setDelibCondition($data["condition"]);
            $this->entityManager->flush();
            

            if(isset($data["classes"])&&!is_null($data["classes"])){
                $classes = $this->entityManager->getRepository(ClassOfStudy::class)->findByDeliberation($delib);
                foreach($classes as $classe)
                    $classe->setDeliberation(NULL);
                $this->entityManager->flush();
                    
                foreach($data["classes"] as $classe_id)
                {
                  
                   $classes = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByDeliberation($delib);
                   $class= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_id);
                   $class->setDeliberation($delib); 
                   $this->entityManager->flush();
                   
                   
                }                

                
            }
            $msge=true;
            
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   $msge
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
            $delib = $this->entityManager->getRepository(Deliberation::class)->findOneById($id); 
            $classes = $this->entityManager->getRepository(ClassOfStudy::class)->findByDeliberation($delib);
            foreach($classes as $classe)
                $classe->setDeliberation(NULL);
            $this->entityManager->flush(); 
            
             
            $this->entityManager->remove($delib);
            
            $this->entityManager->flush();
            
           $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   
            ]); 
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }        
    }
    

}
