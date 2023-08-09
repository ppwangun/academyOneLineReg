<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Student;
use Application\Entity\RegistrationPerClassView;
use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredPaymentView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\AdmittedStudentView;
use Application\Entity\AdmittedStudentForActiveRegistrationYearView;
use Application\Entity\UnitRegistration;
use Application\Entity\AcademicYear;
use Application\Entity\Semester;
use Application\Entity\StudentSemRegistration;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\ClassOfStudy;
use Application\Entity\Degree;
use Application\Entity\FieldOfStudy;
use Application\Entity\Faculty;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\AdminRegistration;
use Application\Entity\Admission;
use Application\Entity\TeachingUnit;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\Countries;

use Student\Service\StudentManager;


use Laminas\Hydrator\Reflection as ReflectionHydrator;

class PaymentController extends AbstractActionController
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
  /** 
   * We override the parent class' onDispatch() method to
   * set an alternative layout for all actions in this controller.
   */
  public function onDispatch(MvcEvent $e) 
  {
      
    // Call the base class' onDispatch() first and grab the response
    $response = parent::onDispatch($e);        
	
    // Set alternative layout
    $this->layout()->setTemplate('layout/layout2');   
    
  
    // Return the response
    return $response;
  }

    public function indexAction()
    {
        return [];
    }
    
    public function chkPaymentAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            //return  $this->redirect()->toRoute('index'); 
             $paymentError = false;
             $debtError = false;
             $debt = 0;
            
             //Redirect the user
            $studentMat= $this->sessionContainer->userId;

            $this->studentManager->redirect($this->sessionContainer,$studentMat,3,"endRegistration");
            $this->studentManager->redirect($this->sessionContainer,$studentMat,2,"insPedagogique");
            //$this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");
                             
             
            // Check if user has submitted the form
             if ($this->getRequest()->isPost()) {

 
                 $student = $this->entityManager->getRepository(RegisteredPaymentView::class)->findOneByMatricule($studentMat);
                 if($student)
                 {
                    $debt = $student->getFeesPaid()-$student->getFeesDebt(); 
                    if($debt>=115000)
                    {
                        return $this->redirect()->toRoute('studentRegistration');
						
                    }
                    //Check whether or not  previous year fees is totaly paid
                    if($debt<0)
                    {
                        $debtError = true;
                        
                    }                    
                    else $paymentError = true;
                 }
             }
             
             return new ViewModel([
                 "paymentError"=>$paymentError,
                 "debtError"=>$debtError,
                 "debt"=>$debt
                 //'userName' => $this->sessionContainer->userName
             ]);
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

    } 
    public function chkNewStudentPaymentAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            //return  $this->redirect()->toRoute('index'); 
             $paymentError = false;
             
            $studentId= $this->sessionContainer->newUserId;
            //Redirect if the student already created 
            $studentMat = $this->sessionContainer->userId; 
            $this->studentManager->redirect($this->sessionContainer,$studentId,2,"endRegistration");
            
            if(!$this->sessionContainer->userId)
            {
                $this->studentManager->redirect($this->sessionContainer,$studentMat,3,"endRegistration");
                $this->studentManager->redirect($this->sessionContainer,$studentMat,2,"insPedagogique");
                $this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");
            }             
             // Check if user has submitted the form
             if ($this->getRequest()->isPost()) {

                


                 $student = $this->entityManager->getRepository(AdmittedStudentForActiveRegistrationYearView::class)->findOneByNumDossier($studentId);
                 if($student)
                 {
                    if($student->getFeesPaid()>=150000)
                    { 
                        return $this->redirect()->toRoute('newStudentRegistration');
                    }
                    else $paymentError = true;
                 }
             }
   
             return new ViewModel([
                 "paymentError"=>$paymentError
                 //'userName' => $this->sessionContainer->userName
             ]);
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

    }    
  
}
