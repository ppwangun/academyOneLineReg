<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Payment\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Payment\Service\PaymentManager;

use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredPaymentView;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;

class AdmissionPaymentController extends AbstractRestfulController
{
    private $entityManager;
    private $paymentManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$paymentManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->paymentManager = $paymentManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            //Find student based on Matricule
            $admittedStd = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($id);
            $data = $admittedStd;
            
        
            //Converting objet to array
            if($admittedStd)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($admittedStd);

            }
            
            $student["nom"] = $data["nom"]." ".$data["prenom"];
            $student["classe"]= $admittedStd->getClassOfStudy()->getCode();
            $student["admission_id"] = $data["fileNumber"];



            $this->entityManager->getConnection()->commit();
            
            
            $output = new JsonModel([
                    $student
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
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
                   $registeredStd = $this->entityManager->getRepository(RegisteredPaymentView::class)->findBy(array(),array("nom"=>"ASC"));
            else{
                $registeredStd = [];
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        $registeredStd_1 = $this->entityManager->getRepository(RegisteredPaymentView::class)->findBy(array("class"=>$classe->getClassOfStudy()->getCode()),array("nom"=>"ASC"));
                        $registeredStd = array_merge($registeredStd,$registeredStd_1);
                        
                    }
                }                
            }
            $i = 0;
            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $registeredStd[$key] = $data;
            }            
            
           //COnvert all text from NOM and PRENOM field to UTF-8
           /* for($i=0;$i<sizeof($registeredStd);$i++)
            {
                $registeredStd[$i]['nom']= utf8_encode($registeredStd[$i]['nom']);
                $registeredStd[$i]['prenom']= utf8_encode($registeredStd[$i]['prenom']);
                
            }*/
         
            $output = $registeredStd;

            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    $output
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
            $msge= false;
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $msge
            ]); 
            $admittedStd = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($data['admission_id']);
            
            if(!$admittedStd)
            {  
                
                return $output;
            }            

            
           
            $admittedStd->setFeesPaid($data['amount']);
            date_default_timezone_set('Africa/Douala');
            $admittedStd->setPaymentDate(new \DateTime(date("Y-m-d H:i:s")));
            $admittedStd->setStatus(1);

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
            $msge = true;

            $output = new JsonModel([
                    $msge
            ]);
            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
   }
    

}
