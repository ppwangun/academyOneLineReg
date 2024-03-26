<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator as ReflectionHydrator;
use Violet\StreamingJsonEncoder\StreamJsonEncoder; 
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

use Application\Entity\AdmittedStudentView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\Admission;
use Application\Entity\ProspectiveStudent;
use Application\Entity\CursusAcademique;
use Application\Entity\ProspetiveRegistration;
use Application\Entity\StudentParent;

class PreRegistrationController extends AbstractActionController
{
    private $entityManager;
    private $studentManager;
    private $sessionContainer;
    private $examManager;
    public function __construct($entityManager,$studentManager,$sessionContainer) {
        $this->entityManager = $entityManager;
        $this->studentManager = $studentManager;
        $this->sessionContainer = $sessionContainer;
      
    }
    
    public function getPreRegisteredStdAction()
    {
        $numDossier = $this->sessionContainer->newUserId;
        try
        {
            
            $admission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($numDossier);
            
            $student =[];
            
            $prosStd = $admission->getProspectiveStudent();
            //echo $prosStd->getNom(); exit;
            $hydrator = new ReflectionHydrator();
            $student = $hydrator->extract($prosStd);
            
            $student["nom"] = $prosStd->getNom();
            
            

           // $academicYear = $prosReg->getAcademicYear()->getCode(); 
           // $stdParent = $this->entityManager->getRepository(StudentParent::class)->findByProspectiveStudent($pros);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
 
        }         
        
        
        return new JsonModel([
         $student   
        ]);
        
    }
    
    public function preRegistrationAction()
    {
        return new ViewModel([
           
            //'userName' => $this->sessionContainer->userName
        ]);
    }
  
    public function savePreRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            if ($this->getRequest()->isPost()){
            //generate random number of 6 digits and check if the number already exist in the database befor assigning
            $data = $this->params()->fromPost();
            $prefixe = date('y');
            $numDossier= $prefixe.'-'.mt_rand(100000, 199999);
            while($this->entityManager->getRepository(Admission::Class)->findOneByFileNumber($numDossier))
            {
                $numDossier = $prefixe.'-'.mt_rand(10000, 19999);
            }  
            $admission = $this->entityManager->getRepository(Admission::class)->find($data["id"]);
            $admission->setNom($data["nom"]);
            $admission->setPrenom($data["prenom"]);
            $admission->setPhoneNumber($data["phoneNumber"]);
            
            $admission->setFileNumber($numDossier);
            $admission->setStatus(1);
                                    

            
            
            

            $msge=  "Bienvenue à l'UdM.Votre code d'admission est ".$numDossier.".Connectez vous sur le lien http://udmacademy.aed-cm.org pour finaliser votre inscription.";
            $phoneNumber = "+237".$data["phoneNumber"];
            //Sending sms
            $data["msgeStatus"]=$this->studentManager->sendSMS($phoneNumber,$msge);

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            //$output = json_encode($output,$depth=1000000); 
            $output = new ViewModel([
                    "numDossier"=>$numDossier
            ]);
            return $output;
            }
            //var_dump($output); //exit();
            return $this->redirect()->toRoute("preRegistration");       
            
            }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }          

    }
}
