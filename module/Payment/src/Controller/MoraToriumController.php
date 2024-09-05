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
use Application\Entity\Payment;
use Application\Entity\AdminRegistration;

class MoratoriumController extends AbstractRestfulController
{
    private $entityManager;
    private $paymentManager;
    
    public function __construct($entityManager,$paymentManager) {
        
        $this->entityManager = $entityManager; 
        $this->paymentManager = $paymentManager;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            //Find student based on Matricule
            $registeredStd = $this->entityManager->getRepository(RegisteredPaymentView::class)->find($id);
            $data = $registeredStd;
            //Calculate actual payments
            if($data)
            {
                $this->paymentManager->feesSumOfPayments($data->getRegistrationId());
                //Refresh and take into account payments calculation
                $this->entityManager->refresh($registeredStd);
            }
    
            //Converting objet to array
            if($registeredStd)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
               // $data['dateNaissance']=$data['dateNaissance']->format('Y-m-d');
                //$data['dateInscription']=$data['dateInscription']->format('Y-m-d');
                
                //Convert all text from NOM and PRENOM field to UTF-8
                $data['nom']= mb_convert_encoding($data['nom'], 'UTF-8', 'UTF-8');
                $data['prenom']= mb_convert_encoding($data['prenom'], 'UTF-8', 'UTF-8');
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
            $registeredStd = $this->entityManager->getRepository(RegisteredPaymentView::class)->findBy(array("moratoriumAutorization"=>!NULL));
            //Calculate the payment for each row and convert the array of array
            foreach($registeredStd as $key=>$value)
            {
                //Update feespaid
                $id = $value->getRegistrationId();
                
                $this->paymentManager->feesSumOfPayments($id);
                //refresshing each entity after calculation is done
                $this->entityManager->refresh($value);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $registeredStd[$key] = $data;
            }
            
            
            //COnvert all text from NOM and PRENOM field to UTF-8
            for($i=0;$i<sizeof($registeredStd);$i++)
            {
                $registeredStd[$i]['nom']= mb_convert_encoding($registeredStd[$i]['nom'], 'UTF-8', 'UTF-8');
                $registeredStd[$i]['prenom']= mb_convert_encoding($registeredStd[$i]['prenom'], 'UTF-8', 'UTF-8');
                
            }
         
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
            $std = $this->entityManager->getRepository(RegisteredStudentView::class)->find($data['studentid']);
            
            $std = $this->entityManager->getRepository(AdminRegistration::class)->find($std->getRegistrationId());
            //print_r($std); exit;
            if(!$std)
            {
                return $output;
            }
            
            $payment = new Payment();
            $payment->setAmount($data['amount']);
            date_default_timezone_set('Africa/Douala');
            $payment->setDateTransaction(new \DateTime(date("Y-m-d H:i:s")));
            $payment->setAdminRegistration($std);
 
            $this->entityManager->persist($payment);
            $this->entityManager->flush();
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
