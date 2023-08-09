<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\Reflection as ReflectionHydrator;

use Application\Entity\RegisteredStudentView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\Student;
use Application\Entity\TeachingUnit;
use Application\Entity\UnitRegistration;
use Application\Entity\Semester;


class SubjectRegistrationController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    //this function takes as paramer the student ID and 
    //returns the list of of subjects to which student is registered
    public function get($id) { 
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            // retrieve the sutdent  based on the student ID 
            $std = $this->entityManager->getRepository(RegisteredStudentView::class)->find($id); 
            $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByMatricule($id);
            

            foreach($std_registered_subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std_registered_subjects[$key] = $data;

            }
            
            for($i=0;$i<sizeof($std_registered_subjects);$i++)
            {
              //  $std_registered_subjects[$i]['nomUe']= utf8_encode($std_registered_subjects[$i]['nomUe']);
               // $std_registered_subjects[$i]['nom']= utf8_encode($std_registered_subjects[$i]['nom']);
 
            }
           
            $this->entityManager->getConnection()->commit();
            
          
            $output = new JsonModel([
                    $std_registered_subjects
            ]);

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
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findAll();
            $i= 0;
            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $registeredStd[$key] = $data;
            }

            //$output = json_encode($registeredStd,$depth=10000000);

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $registeredStd
            ]);
           
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
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
           
            
            $std_mat = $data['id'];
            $subjects = $data['subjects'];
            $msge = false;
            
            //retrive student_id from matricule
            $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($std_mat);
            foreach ($subjects as $key=>$value)
            {
                //retrive the subject based on subject code
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($value['id']);
                $semester = $this->entityManager->getRepository(Semester::class)->findOneByCode($value['semester']);
                
                //check if the subject is already registered for the student
                $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit));
                
                if(!$isRegistered )
                {
                   
                    $unitRegistration = new UnitRegistration();
                    
                    $unitRegistration->setStudent($student);
                    $unitRegistration->setTeachingUnit($teachingUnit);
                    $unitRegistration->setSemester($semester);
                    //echo 'je suis dedans';                    exit();
                    
                    $this->entityManager->persist($unitRegistration);
                    $this->entityManager->flush();
                }
                
                $msge = true; 
            }
	    $this->entityManager->getConnection()->commit();
           
         
           // }
            
          $view = new JsonModel([
             $msge
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }       
    }  
    
    public function delete($id)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $unit_registration = $this->entityManager->getRepository(UnitRegistration::class)->findOneById($id);
            $msge = false;
            if($unit_registration)
            {
                
                $this->entityManager->remove($unit_registration );
                //$this->entityManager->remove($ue );
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
                $msge= true;
            }


        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
           $msge   
        ]);
    }
    

}
