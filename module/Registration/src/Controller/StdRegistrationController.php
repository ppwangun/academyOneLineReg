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
use Violet\StreamingJsonEncoder\StreamJsonEncoder; 
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

use Application\Entity\RegisteredStudentView;
use Application\Entity\AllYearsRegisteredStudentView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\Student;
use Application\Entity\ClassOfStudy;

class StdRegistrationController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {    
            
               $registeredStd = $this->entityManager->getRepository(Student::class)->findOneByMatricule($id);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
                $data['dateOfBirth']=$data['dateOfBirth']->format('Y-m-d');
                if($registeredStd->getPhoto())
                    $data["photo"]=stream_get_contents($registeredStd->getPhoto());
                $data["phone_number"] = trim($data['phoneNumber']);
                
                $data["fatherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["motherPhoneNumber"] = trim($data['fatherPhoneNumber']);
                $data["sponsorPhoneNumber"] = trim($data['sponsorPhoneNumber']);
                
                $currentAcadYr = $this->sessionContainer->currentAcadYr;
                $student = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findOneBy(["acadYrId"=>$currentAcadYr->getId(),"matricule"=>$id]);                
                
                
                
            
                $hydrator = new ReflectionHydrator();
                $student = $hydrator->extract($student);
                
                $studentClasse = $student["class"];
                
                $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($studentClasse);
                $studentFilere = $classe->getDegree()->getFieldStudy();
                $studentFaculty = $studentFilere->getFaculty();
       
                $data["filiere"]= $studentFilere->getName();
                $data['faculty']= $studentFaculty->getName();
                $data['training'] = $classe->getDegree()->getName();
                $data['classe'] = $classe->getCode();
                $data['isRepeating'] = $student["isStudentRepeating"];
                //$data['dateInscription']=$data['dateInscription']->format('Y-m-d');11
              // var_dump($data); exit; 
                
                

            $this->entityManager->getConnection()->commit();
            
            
           
            $output = new JsonModel([
                    $data
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

    }
  private function char($text) { $text = htmlentities($text, ENT_NOQUOTES, "UTF-8"); $text = htmlspecialchars_decode($text); return $text; }

}
