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
use Laminas\Http\Request;
use Laminas\Http\Client;
use Laminas\Hydrator\ReflectionHydrator;
use Violet\StreamingJsonEncoder\StreamJsonEncoder; 
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

use Application\Entity\AdmittedStudentForActiveRegistrationYearView;
use Application\Entity\AdmittedStudentView;
use Application\Entity\Admission;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\AllYearsAdmittedStdView;

class StdAdmissionController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    private $studentManager;
    private $crtAcadYr;
    
    public function __construct($entityManager,$studentManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
        $this->studentManager = $studentManager;
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
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
       $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $userId = $this->sessionContainer->userId; 
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {

                   $registeredStd = $this->entityManager->getRepository(AllYearsAdmittedStdView::class)->findBy(array("acadYrId"=>$this->crtAcadYr->getId()),array("nom"=>"ASC"));
            }
            
            else{
                $registeredStd = [];
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        $registeredStd_1 = $this->entityManager->getRepository(AdmittedStudentForActiveRegistrationYearView::class)->findBy(array("classe"=>$classe->getClassOfStudy()->getCode()),array("nom"=>"ASC"));
                        $registeredStd = array_merge($registeredStd,$registeredStd_1);
                        
                    }
                }                
            }
         
            foreach($registeredStd as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $registeredStd[$key] = $data;
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
            $this->entityManager->getConnection()->beginTransaction();
            //generate random number of 6 digits and check if the number already exist in the database befor assigning
            $numDossier= mt_rand(100000, 199999);
            while($this->entityManager->getRepository(AdmittedStudentView::Class)->findOneBynumDossier($numDossier))
            {
                $numDossier = mt_rand(100000, 199999);
            } 
            $admission = $this->entityManager->getRepository(Admission::class)->find($data["id"]);
            $admission->setNom($data["nom"]);
            $admission->setPrenom($data["prenom"]);
            $admission->setPhoneNumber($data["phoneNumber"]);
            $admission->setFeesPaid($data["feesPaid"]);
            //$admission->setFileNumber($numDossier);
            //$admission->setStatus(1);
            $data["numDossier"] = $admission->getFileNumber();
            $data["status"]=1;
                                    
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
            //Sending sms
            

            $msge=  urlencode( "Bonjour et bienvenue à l'UdM.Votre code d'admission est ".$numDossier.".Connectez vous sur le lien http://UdMAcademy.aed-cm.org pour finaliser votre inscription.");
            $phoneNumber = "+237".$data["phoneNumber"];
           
            //$data["msgeStatus"]=$this->studentManager->sendSMS($phoneNumber,$msge);

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
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

}
