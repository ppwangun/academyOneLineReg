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
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\RegisteredStudentView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\Student;
use Application\Entity\TeachingUnit;
use Application\Entity\UnitRegistration;
use Application\Entity\Semester;
use Application\Entity\StudentExamRegistrationView;


class StdRegisteredToExamController extends AbstractRestfulController
{
    private $entityManager;
    private $crtAcadyr;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->crtAcadyr = $sessionContainer->currentAcadYr;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            //$data = json_decode($id);
            // retrieve the sutdent ID based on the student ID 
            
            $std = $this->entityManager->getRepository(StudentExamRegistrationView::class)->findBy(array("codeExam"=>$id,"acadYrId"=> $this->crtAcadyr->getId()),array("nom"=>"ASC","classe"=>"ASC")); 
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());

            foreach($std as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std[$key] = $data;

            }
            for($i=0;$i<sizeof($std);$i++)
            {
                //$std[$i]['nom']= utf8_encode($std[$i]['nom']);
                //$registeredStd[$i]['prenom']= mb_convert_encoding($registeredStd[$i]['prenom'], 'UTF-8', 'UTF-8');
                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $std
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

            for($i=0;$i<sizeof($registeredStd);$i++)
            {
               // $registeredStd[$i]['nom']= utf8_encode($registeredStd[$i]['nom']);
                //$registeredStd[$i]['prenom']= utf8_encode($registeredStd[$i]['prenom']);
                
            }

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $output
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
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->findOneByCode($value['codeUe']);
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
