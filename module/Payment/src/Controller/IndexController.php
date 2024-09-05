<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Payment\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Student;

use Payment\Service\PayementManager;

use Port\Csv\CsvReader;
use Port\Csv\CsvWriter;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\StepAggregator as Workflow;
use Port\Steps\Step\FilterStep;
use Port\Filter\OffsetFilter;
use Port\Reader\ArrayReader;
use Port\Writer\ArrayWriter;
use Port\Steps\Step\ConverterStep;

class IndexController extends AbstractActionController
{
    private $entityManager;
    private $paymentManager;
    public function __construct($entityManager,$paymentManager) {
        $this->entityManager = $entityManager;
        $this->paymentManager = $paymentManager;
    }

    public function indexAction()
    {
        return [];
    }
    
    public function paymentsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function paymentdetailstplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    public function moratoriumsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    //Managing new student registration fees
    
    public function newStdRegistrationFeesMgtAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }  

    public function savePymtTransactionAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $data = $this->params()->fromQuery();
            $data = json_decode($data["std"],true);            
            ($data["amountPaid"])?$data["montant"]=$data["amountPaid"]:$data["montant"]=null;
            $this->paymentManager->importPayments($data);
            //$message = $this->paymentManager->updatePymtAPI($data);


            $this->entityManager->getConnection()->commit();
            $view = new JsonModel([
               "session"=>true  
             ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);

            return $view;            
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }       

    }    
    public function importBalanceAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     

                /* Getting file name */
               $filename = $_FILES['file']['name'];
               /* Location */
               $location = './public/upload/';

               $csv_mimetypes = array(
                   'text/csv',
                   'application/csv',
                   'text/comma-separated-values',
                   'application/excel',
                   'application/vnd.ms-excel',
                   'application/vnd.msexcel',
                );
            // Check if fill type is allowed  
              if(!in_array($_FILES['file']['type'],$csv_mimetypes))
              {
                 $result = false;

                  $view = new JsonModel([
                    $result
                  ]);
                  return $view; 
              }

                /* Upload file */
                move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


                $delimiter = ';';
                $file = new \SplFileObject($location.$filename);
                $reader = new CsvReader($file,$delimiter);
                // Tell the reader that the first row in the CSV file contains column headers
                $reader->setHeaderRowNumber(0);
                $workflow = new Workflow($reader);

                // Create a writer: you need Doctrine’s EntityManager.
                $doctrineWriter = new DoctrineWriter($this->entityManager, Student::class);
                $doctrineWriter->disableTruncate();
                $workflow->addWriter($doctrineWriter,['matricule']);

            //set status to 0
            //Student is currently in draft mode
            $status = 0;
            foreach ($reader as $row) {

                $this->paymentManager->importBalance($row);

            }


            $this->entityManager->getConnection()->commit();


            $arr = array("name"=>$filename);
            $result = true;

              $view = new JsonModel([
                  $result
             ]);

    // Disable layouts; `MvcEvent` will use this View Model instead
           // $view->setTerminal(true);

            return $view;      
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
    
    public function importPaymentsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     

                /* Getting file name */
               $filename = $_FILES['file']['name'];
               /* Location */
               $location = './public/upload/';

               $csv_mimetypes = array(
                   'text/csv',
                   'application/csv',
                   'text/comma-separated-values',
                   'application/excel',
                   'application/vnd.ms-excel',
                   'application/vnd.msexcel',
                );
            // Check if fill type is allowed  
              if(!in_array($_FILES['file']['type'],$csv_mimetypes))
              {
                 $result = false;

                  $view = new JsonModel([
                    $result
                  ]);
                  return $view; 
              }

                /* Upload file */
                move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


                $delimiter = ';';
                $file = new \SplFileObject($location.$filename);
                $reader = new CsvReader($file,$delimiter);
                // Tell the reader that the first row in the CSV file contains column headers
                $reader->setHeaderRowNumber(0);
                $workflow = new Workflow($reader);

                // Create a writer: you need Doctrine’s EntityManager.
                $doctrineWriter = new DoctrineWriter($this->entityManager, Student::class);
                $doctrineWriter->disableTruncate();
                $workflow->addWriter($doctrineWriter,['matricule']);

            //set status to 0
            //Student is currently in draft mode
            $status = 0;
            foreach ($reader as $row) {

                $this->paymentManager->importPayments($row);

            }


            $this->entityManager->getConnection()->commit();


            $arr = array("name"=>$filename);
            $result = true;

              $view = new JsonModel([
                  $result
             ]);

    // Disable layouts; `MvcEvent` will use this View Model instead
           // $view->setTerminal(true);

            return $view;      
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }    
    public function importDotationsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     

                /* Getting file name */
               $filename = $_FILES['file']['name'];
               /* Location */
               $location = './public/upload/';

               $csv_mimetypes = array(
                   'text/csv',
                   'application/csv',
                   'text/comma-separated-values',
                   'application/excel',
                   'application/vnd.ms-excel',
                   'application/vnd.msexcel',
                );
            // Check if fill type is allowed  
              if(!in_array($_FILES['file']['type'],$csv_mimetypes))
              {
                 $result = false;

                  $view = new JsonModel([
                    $result
                  ]);
                  return $view; 
              }

                /* Upload file */
                move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


                $delimiter = ';';
                $file = new \SplFileObject($location.$filename);
                $reader = new CsvReader($file,$delimiter);
                // Tell the reader that the first row in the CSV file contains column headers
                $reader->setHeaderRowNumber(0);
                $workflow = new Workflow($reader);

                // Create a writer: you need Doctrine’s EntityManager.
                $doctrineWriter = new DoctrineWriter($this->entityManager, Student::class);
                $doctrineWriter->disableTruncate();
                $workflow->addWriter($doctrineWriter,['matricule']);

            //set status to 0
            //Student is currently in draft mode
            $status = 0;
            foreach ($reader as $row) {

                $this->paymentManager->importDotations($row);

            }


            $this->entityManager->getConnection()->commit();


            $arr = array("name"=>$filename);
            $result = true;

              $view = new JsonModel([
                  $result
             ]);

    // Disable layouts; `MvcEvent` will use this View Model instead
           // $view->setTerminal(true);

            return $view;      
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }        
}