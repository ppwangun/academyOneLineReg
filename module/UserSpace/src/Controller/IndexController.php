<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace UserSpace\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\AllContractsFollowUpView;
use Application\Entity\AllContractsView;
use Application\Entity\Teacher;
use Application\Entity\FileDocument;
use Application\Entity\countries;
use Application\Entity\Cities;

class IndexController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
    }


    public function indexAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    

    
    public function goLecturerAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout5');

        return $view;            

    } 
    
    public function lSubjectsAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout6');

        return $view;            

    }     
    public function lAssignedSubjectsFollowUpTplAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout6');

        return $view;            

    }    
    public function lProfileAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $this->layout()->setTemplate('layout/layout6');

        return $view;            

    }      
    public function newgrouptplAction()
    {
          
        
        $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function lAssignedSubjectsAction()
    {
        $id = $this->sessionContainer->LoggedInUser["id"];
          
        $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs ,c.teacher  FROM Application\Entity\AllContractsView c '
                .'WHERE c.teacher = :teacher' );
        $query->setParameter('teacher',$id);

        $subjects = $query->getResult();        
        $view = new JsonModel([
           $subjects  
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }  
    public function lAssignedSubjectsFollowUpAction()
    {
        $id = $this->sessionContainer->LoggedInUser["id"];
          
        $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.totalHrsDone ,c.teacher  FROM Application\Entity\AllContractsFollowUpView c '
                .'WHERE c.teacher = :teacher' );
        $query->setParameter('teacher',$id);

        $subjects = $query->getResult();        
        $view = new JsonModel([
           $subjects  
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    public function getTeacherAction()
    {
        $id = $this->sessionContainer->LoggedInUser["id"];
          
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($id);
            $documents = $this->entityManager->getRepository(FileDocument::class)->findOneByTeacher($id);
            if($documents)
                foreach($documents as $key=>$value)
                {

                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $documents[$key] = $data;
                }   
            else $documents = [];

                $hydrator = new ReflectionHydrator();
                $academic_rank_id = $teacher->getAcademicRanck()->getId();
                $requested_establishment_id = $teacher->getFaculty()->getId();
                $data = $hydrator->extract($teacher);
                $teacher = $data;
                $teacher["names"]=$data["name"];
              
                $country = $this->entityManager->getRepository(Countries::class)->findOneByName($data["livingCountry"]);
                $nationality = $this->entityManager->getRepository(Countries::class)->findOneByName($data["nationality"]);
                $city = $this->entityManager->getRepository(Cities::class)->findOneByName($data["livingCity"]);
                if($country)
                    $teacher["living_country"]=$hydrator->extract($country);
                if($city)
                $teacher["living_city"]=$hydrator->extract($city);
                if($nationality)
                $teacher["nationality"]= $nationality->getName() ; 
                $teacher["marital_status"]= $data["maritalStatus"] ;
                $teacher["phone"]= $data["phoneNumber"] ; 
                $teacher["highest_degree"]= $data["highDegree"] ;
                $teacher["grade_id"]= $academic_rank_id ;
                $teacher["actual_employer"]= $teacher["currentEmployer"] ;
                $teacher["requested_establishment_id"] = $requested_establishment_id;
              
                if($data["birthDate"])
                    $teacher["birthdate"]=$data["birthDate"]->format('Y-m-d');
                $teacher["documents"] = $documents;
                //$query = $this->entityManager->createQuery('SELECT c.coshs as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,con.volumeHrs  as totalHrs  FROM Application\Entity\ClassOfStudyHasSemster c'
                $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.teacher   FROM Application\Entity\AllContractsView c '
                        .'WHERE c.teacher = :teacher' );
                $query->setParameter('teacher',$id);

                $subjects_1 = $query->getResult();
                $teacher["teaching_units"] = $subjects_1;
            return new JsonModel([
                "date"=>$data["birthDate"]->format('Y-m-d'),
                $teacher
            ]);           

    }    
}
