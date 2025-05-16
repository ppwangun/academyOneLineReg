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
use Application\Entity\AllYearsRegisteredPaymentsView;
use Application\Entity\AllYearsRegisteredStudentView;
use Application\Entity\Payment;
use Application\Entity\AdminRegistration;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;

use g105b\phpcsv;

class PaymentController extends AbstractRestfulController
{
    private $entityManager;
    private $paymentManager;
    private $sessionContainer;
    private $crtAcadYr ;
    
    public function __construct($entityManager,$paymentManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->paymentManager = $paymentManager;
        $this->sessionContainer = $sessionContainer;
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            //Find student based on Matricule
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredPaymentsView::class)->findOneBy(["id"=>$id,"acadYrId"=>$this->crtAcadYr->getId()]);
            $data = $registeredStd;
            
            //Calculate actual payments
            if($data)
            {
               // $this->paymentManager->feesSumOfPayments($data->getRegistrationId());
                //Refresh and take into account payments calculation
               // $this->entityManager->refresh($registeredStd);
            }
    
            //Converting objet to array
            if($registeredStd)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
               // $data['dateNaissance']=$data['dateNaissance']->format('Y-m-d');
                //$data['dateInscription']=$data['dateInscription']->format('Y-m-d');
                
                //Convert all text from NOM and PRENOM field to UTF-8
                //$data['nom']= utf8_encode($data['nom']);
                //$data['prenom']= utf8_encode($data['prenom']);
            }



            $this->entityManager->getConnection()->commit();
            
            
            $output = new JsonModel([
                    $data
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
                   $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredPaymentsView::class)->findBy(["acadYrId"=>$this->crtAcadYr->getId()]);
            else{
                $registeredStd = [];
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        $registeredStd_1 = $this->entityManager->getRepository(AllYearsRegisteredPaymentsView::class)->findBy(array("class"=>$classe->getClassOfStudy()->getCode(),"acadYrId"=>$this->crtAcadYr->getId()),array("nom"=>"ASC"));
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
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->find($data['studentid']);
            
            if(!$registeredStd)
            {  
                
                return $output;
            }            
            $std = $this->entityManager->getRepository(AdminRegistration::class)->find($registeredStd->getRegistrationId());
            //print_r($std); exit;

            
            $payment = new Payment();
            $payment->setAmount($data['amount']);
            date_default_timezone_set('Africa/Douala');
            $payment->setDateTransaction(new \DateTime(date("Y-m-d H:i:s")));
            $payment->setMobilePaymentId($data['paymentid']);
            $payment->setAcademicYear($this->paymentManager->getCurrentYear());
            $payment->setAdminRegistration($std);
 
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
            
                //Calculate and update  total done

                $this->paymentManager->feesSumOfPayments($registeredStd->getRegistrationId());
                //Refresh and take into account payments calculation
                //$this->entityManager->refresh($registeredStd);
           
            
            $this->entityManager->getConnection()->commit();
            $msge = true;

            $output = new JsonModel([
                    $msge
            ]);
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
   }
    

}
