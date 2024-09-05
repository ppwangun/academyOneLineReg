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

use Application\Entity\AdminRegistration;
use Application\Entity\RegisteredStudentView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\Student;
use Application\Entity\TeachingUnit;
use Application\Entity\UnitRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Semester;
use Application\Entity\AcademicYear;


class StdRegisteredToSubjectController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
   
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
           
            $data =json_decode($id,true);    

            if(isset($data["classId"]))
            {
              
                if(!isset($data["subjectId"])) $data["subjectId"] = [null," "];

                 $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$data["ueId"],"idSubject"=>$data["subjectId"]),array("nom"=>"ASC")); 
          
            
                
                $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classId"]); 
                
                $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
                $status =-1;
                $crtClassStud = $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("class"=>$classOfStudy->getCode()),array("nom"=>"ASC"));
                $i = 0;
                foreach($crtClassStud as $stud)
                {
                    $std1 = $this->entityManager->getRepository(SubjectRegistrationView::class)->findOneBy(array("idUe"=>$data["ueId"],"idSubject"=>$data["subjectId"],"matricule"=>$stud->getMatricule())); 
                    
                    if(!$std1)
                    { 
                        $regStud = new SubjectRegistrationView();
                        $regStud->setNom($stud->getNom());
                        $regStud->setMatricule($stud->getMatricule());
                        $regStud->setStudentCurrentClasse($classOfStudy->getCode());
                        $regStud->setStatus($status);
                        //$std[$i] = $regStud; 
                        array_splice($std,$i,0,array($regStud) );
                    }
                     
                    $i++;
                }
                
                
                foreach($std as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $std[$key] = $data;

                } 
                
                //Sorting the $std array according to the key "nom"
                $tmp = Array();
                foreach($std as &$ma)
                    $tmp[] = &$ma["nom"];
                array_multisort($tmp, $std);

                return new JsonModel([ $std ]);                
            }           
            // retrieve the sutdent based on the UE code 
            //$sem = $this->entityManager->getRepository(Semester::class)->find($data["sem_id"]);
            //Only student that have completed registration process with status equal to 1

            if(!isset($data["subjectId"])) 
             $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$data,"idSubject"=>[null," "]),array("nom"=>"ASC")); 
            else
                $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$data["ueId"],"idSubject"=>$data["subjectId"]),array("nom"=>"ASC"));
            //$std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());

            foreach($std as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std[$key] = $data;

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
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->find();
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

