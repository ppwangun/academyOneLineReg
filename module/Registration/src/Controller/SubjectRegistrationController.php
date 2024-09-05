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
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\SubjectRegistrationOnlineRegistrationYearView;
use Application\Entity\Student;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\UnitRegistration;
use Application\Entity\Semester;
use Application\Entity\ClassOfStudyHasSemester;


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
            $std = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->find($id); 
            $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationOnlineRegistrationYearView::class)->findBy(array("matricule"=>$id,"subjectId"=>[NULL," "]));
            

            foreach($std_registered_subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std_registered_subjects[$key] = $data;

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

            if(isset($data["ueId"])&&!isset($data["subjectId"]))
            {
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data['ueId']);
                $semester = $this->entityManager->getRepository(Semester::class)->find($data['semId']);
                foreach ($data["students"] as $key=>$value)
                {  
                    $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($value["matricule"]);
                    $subjects = $this->entityManager->getRepository(Subject::class)->findBy(array("teachingUnit"=>$teachingUnit));
                    //check if the subject is already registered for the student
                    $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"subject"=>[NULL," "],"semester"=>$semester));

                    if(!$isRegistered  )
                    {

                        $unitRegistration = new UnitRegistration();

                        $unitRegistration->setStudent($student);
                        $unitRegistration->setTeachingUnit($teachingUnit);
                        $unitRegistration->setSemester($semester);

                        if($value["status"]== 1)
                        {
                            $this->entityManager->persist($unitRegistration);
                            foreach($subjects as $sub)
                            { 
                                $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"subject"=>$sub,"semester"=>$semester));
                                if(!$isRegistered)
                                {
                                    $unitRegistration = new UnitRegistration();
                                    $unitRegistration->setStudent($student);
                                    $unitRegistration->setTeachingUnit($teachingUnit);
                                    $unitRegistration->setSubject($sub);
                                    $unitRegistration->setSemester($semester);

                                    $this->entityManager->persist($unitRegistration);


                                }
                            }                            
                        }
                       
                        
                        
                    } 
                    else if($value["status"]== -1)
                    {
                        $unitReg = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"semester"=>$semester));
                        foreach($unitReg as $unit)
                            $this->entityManager->remove($unit);
                    }
                  
                    
                    
                    $this->entityManager->flush();
                }
                $msge = true;
                $this->entityManager->getConnection()->commit();
                return new JsonModel([$msge]);                
            }
            if(isset($data["subjectId"]))
            {               
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data['ueId']);
                $subject = $this->entityManager->getRepository(Subject::class)->find($data['subjectId']);
                $semester = $this->entityManager->getRepository(Semester::class)->find($data['semId']);
                foreach ($data["students"] as $key=>$value)
                {
                    $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($value["matricule"]);
                    //check if the subject is already registered for the student
                    $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"subject"=>$subject,"semester"=>$semester));

                    if(!$isRegistered && $value["status"]!= -1 )
                    {

                        $unitRegistration = new UnitRegistration();

                        $unitRegistration->setStudent($student);
                        $unitRegistration->setTeachingUnit($teachingUnit);
                        $unitRegistration->setSubject($subject);
                        $unitRegistration->setSemester($semester);

                        $this->entityManager->persist($unitRegistration);
                        
                    }
                    else if($isRegistered  && $value["status"]== -1) $this->entityManager->remove($isRegistered[0]);
                    $this->entityManager->flush();
                }
                $msge = true; 
                $this->entityManager->getConnection()->commit();
                return new JsonModel([$msge]);                
            }
            
            
            $std_mat = $data['id'];
            $subjects = $data['subjects'];
            $msge = false;
           
            //retrive student_id from matricule
            $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($std_mat);
            foreach ($subjects as $key=>$value)
            {
               
                //retrive the subject based on subject code
                $cosh = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($value['id']);
                $teachingUnit = $cosh->getTeachingUnit();
                $semester = $this->entityManager->getRepository(Semester::class)->find($value['semId']);
               
                //check if the subject is already registered for the student
                $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"subject"=>[NULL,""],"semester"=>$semester));
             
                if(!$isRegistered )
                {
                  
                    $unitRegistration = new UnitRegistration();
                    
                    $unitRegistration->setStudent($student);
                    $unitRegistration->setTeachingUnit($teachingUnit);
                    $unitRegistration->setSemester($semester);
                    
                    
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
