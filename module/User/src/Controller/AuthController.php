<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\Result;
use Laminas\Uri\Uri;
use User\Form\NewStudentLoginForm;
use User\Form\LoginForm;
use User\Form\StudentSpaceLoginForm;
use User\Form\LecturerSpaceLoginForm;
use Laminas\Permissions\Rbac\Rbac;
use Application\Entity\User;
use Application\Entity\Student;
use Application\Entity\Admission;
use Application\Entity\ProspectiveStudent;
use Application\Entity\Teacher;



/**
 * This controller is responsible for letting the user to log in and log out.
 */
class AuthController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Auth manager.
     * @var User\Service\AuthManager 
     */
    private $authManager;
    
    /**
     * Auth service.
     * @var \Laminas\Authentication\AuthenticationService
     */
    private $authService;
    
    /**
     * User manager.
     * @var User\Service\UserManager
     */
    private $userManager;
    
    private $sessionContainer;
    
    private $studentManager;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager, $authManager, $authService, $userManager,$sessionContainer,$studentManager)
    {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->sessionContainer = $sessionContainer;
        $this->studentManager = $studentManager;
    }
    
   /* public function indexAction() {
       //redirect to the login action of authController
        return  $this->redirect()->toRoute('accueil');
    }*/
    public function indexAction()
    {
        return new ViewModel();
    }    
    
    
    public function registerAction()
    {
        $this->layout()->setTemplate('layout/layout');
        return new ViewModel();
    }     
    /**
     * Authenticates user given email address and password credentials.     
     */
    public function loginAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            //$this->authService->clearIdentity();
            //redirect to home page if session is still active
            /*if ($this->authService->getIdentity()!=null) {
                return $this->redirect()->toRoute('home');

            }*/ 
            // Retrieve the redirect URL (if passed). We will redirect the user to this
            // URL after successfull login.
            $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
            if (strlen($redirectUrl)>2048) {
                throw new \Exception("Too long redirectUrl argument passed");
            }

            // Check if we do not have users in database at all. If so, create 
            // the 'Admin' user.
            $this->userManager->createAdminUserIfNotExists();


            // Create login form
            $form = new LoginForm(); 

            $form->get('redirect_url')->setValue($redirectUrl);

            // Store login status.
            $isLoginError = false;
            $isPvNotAvailable = false;

            // Check if user has submitted the form
            if ($this->getRequest()->isPost()) {

                // Fill in the form with POST data
                $data = $this->params()->fromPost();            

                $form->setData($data);

                // Validate form
                if($form->isValid()) {

                    // Get filtered and validated data
                    $data = $form->getData();

                    // Perform login attempt.
                    $result = $this->authManager->login($data['matricule'], 
                            $data['birthdate'], $data['remember_me']);

                    // Check result.
                    if ($result->getCode()==Result::SUCCESS) {
                        // Get redirect URL.
                        $redirectUrl = $this->params()->fromPost('redirect_url', '');
                        $student= $this->entityManager->getRepository(Student::class)->findOneByMatricule($data['matricule']);

                         $this->sessionContainer->userName = $student->getNom()." ".$student->getPrenom();
                         $this->sessionContainer->userId = $student->getMatricule();
                         
                        //Redirect if the student already registered
                        $this->studentManager->redirect($this->sessionContainer,$student->getMatricule(),3,"endRegistration");
                        $this->studentManager->redirect($this->sessionContainer,$student->getMatricule(),2,"insPedagogique");
                        $this->studentManager->redirect($this->sessionContainer,$student->getMatricule(),1,"endRegistration");

                        if (!empty($redirectUrl)) {
                            // The below check is to prevent possible redirect attack 
                            // (if someone tries to redirect user to another domain).
                            $uri = new Uri($redirectUrl);
                            if (!$uri->isValid() || $uri->getHost()!=null)
                                throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }

                        // If redirect URL is provided, redirect the user to that URL;
                        // otherwise redirect to Home page.
                        if(empty($redirectUrl)) {
                            return $this->redirect()->toRoute('chkPayment');
                        } else {
                            $this->redirect()->toUrl($redirectUrl);
                        }
                    } elseif ($result->getCode()==Result::PV_NOT_AVAILABLE) {
                        $isPvNotAvailable = true;
                    } else {
                    $isLoginError = true;}
                } else {
                    $isLoginError = true;
                }           
            } 

            $view = new ViewModel([
                'form' => $form,
                'isLoginError' => $isLoginError,
                'isPvNotAvailable'=>$isPvNotAvailable,
                'redirectUrl' => $redirectUrl,
                'userName' => $this->sessionContainer->userName
            ]);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    /**
     * Authenticates admitted studing using file number(numéro de dossier).     
     */
    public function newStudentLoginAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            //$this->authService->clearIdentity();
            //redirect to home page if session is still active
           /* if ($this->authService->getIdentity()!=null) {
                return $this->redirect()->toRoute('home');

            } */
            // Retrieve the redirect URL (if passed). We will redirect the user to this
            // URL after successfull login.
            $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
            if (strlen($redirectUrl)>2048) {
                throw new \Exception("Too long redirectUrl argument passed");
            }

            // Check if we do not have users in database at all. If so, create 
            // the 'Admin' user.
            //$this->userManager->createAdminUserIfNotExists();


            // Create login form
            $form = new NewStudentLoginForm(); 
            //$form->get('redirect_url')->setValue($redirectUrl);

            // Store login status.
            $isLoginError = false;

            // Check if user has submitted the form
            if ($this->getRequest()->isPost()) {

                // Fill in the form with POST data
                $data = $this->params()->fromPost();            

                $form->setData($data);

                // Validate form
                if($form->isValid()) {

                    // Get filtered and validated data
                    $data = $form->getData();

                    // Perform login attempt.
                    $result = $this->authManager->newStudentLogin($data['numDossier']);

                    // Check result.
                    if ($result->getCode()==Result::SUCCESS) {
                        // Get redirect URL.
                        $redirectUrl = $this->params()->fromPost('redirect_url', '');
                        $student= $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($data['numDossier']);

                        $this->sessionContainer->newUserName = $student->getNom()." ".$student->getPrenom();
                        $this->sessionContainer->newUserId = $student->getFileNumber();
                         
                        //Check if the student is already registered with this number and save the matricule into session ID
                        $admission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($data['numDossier']);
                        $std = $this->entityManager->getRepository(Student::class)->findOneByAdmission($admission);
                        ($std)?$this->sessionContainer->userId = $std->getMatricule():$this->sessionContainer->userId=null;
                        

                         
                        //Redirect if the student already registered
                        $this->studentManager->redirect($this->sessionContainer,$student->getFileNumber(),2,"endRegistration");


                        if (!empty($redirectUrl)) {
                            // The below check is to prevent possible redirect attack 
                            // (if someone tries to redirect user to another domain).
                            $uri = new Uri($redirectUrl);
                            if (!$uri->isValid() || $uri->getHost()!=null)
                                throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                        }

                        // If redirect URL is provided, redirect the user to that URL;
                        // otherwise redirect to Home page.
                        if(empty($redirectUrl)) {
                            return $this->redirect()->toRoute('chkNewStudentPayment');
                        } else {
                            $this->redirect()->toUrl($redirectUrl);
                        }
                    } else {
                        $isLoginError = true;
                    }                
                } else {
                    $isLoginError = true;
                }           
            } 

            
            $view = new ViewModel([
                'form' => $form,
                'isLoginError' => $isLoginError,
                'redirectUrl' => $redirectUrl,
                'userName' => $this->sessionContainer->newUserName
            ]);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    } 
    
    
    public function myAcademySpaceAction()
    {
          
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            //$this->authService->clearIdentity();
            //redirect to home page if session is still active
            /*if ($this->authService->getIdentity()!=null) {
                return $this->redirect()->toRoute('home');

            }*/ 
            // Retrieve the redirect URL (if passed). We will redirect the user to this
            // URL after successfull login.
            $redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
            if (strlen($redirectUrl)>2048) {
                throw new \Exception("Too long redirectUrl argument passed");
            }

            // Check if we do not have users in database at all. If so, create 
            // the 'Admin' user.
            //$this->userManager->createAdminUserIfNotExists();


            // Create login form
            $studentSpaceLoginForm = new StudentSpaceLoginForm();
            $lecturerSpaceLoginForm = new LecturerSpaceLoginForm();
            $studentSpaceLoginForm->get('redirect_url')->setValue($redirectUrl);
            $lecturerSpaceLoginForm->get('redirect_url')->setValue($redirectUrl);
            // Store login status.
            $isLoginError = false;


            // Check if user has submitted the form
            if ($this->getRequest()->isPost()) {

                // Fill in the form with POST data
                $data = $this->params()->fromPost();  
                if($data["loginFrom"] =="studentSpace")
                {

                    $studentSpaceLoginForm->setData($data);

                    // Validate form
                    if($studentSpaceLoginForm->isValid()) {

                        // Get filtered and validated data
                        $data = $studentSpaceLoginForm->getData();

                        // Perform login attempt.
                        $result = $this->authManager->login($data['matricule'], 
                                $data['birthdate'], $data['remember_me']);

                        // Check result.
                        if ($result->getCode()==Result::SUCCESS) {
                            // Get redirect URL.
                            $redirectUrl = $this->params()->fromPost('redirect_url', '');
                            $student= $this->entityManager->getRepository(Student::class)->findOneByMatricule($data['matricule']);

                             $this->sessionContainer->userName = $student->getNom()." ".$student->getPrenom();
                             $this->sessionContainer->userId = $student->getMatricule();



                            if (!empty($redirectUrl)) {
                                // The below check is to prevent possible redirect attack 
                                // (if someone tries to redirect user to another domain).
                                $uri = new Uri($redirectUrl);
                                if (!$uri->isValid() || $uri->getHost()!=null)
                                    throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                            }

                            // If redirect URL is provided, redirect the user to that URL;
                            // otherwise redirect to Home page.
                            if(empty($redirectUrl)) {
                                return $this->redirect()->toRoute('goStudent');
                            } else {
                                $this->redirect()->toUrl($redirectUrl);
                            }
                        
                        } else {
                        $isLoginError = true;}
                    } else {
                        $isLoginError = true;
                    } 
                }
                elseif($data["loginFrom"] =="lecturerSpace")
                {
                    $lecturerSpaceLoginForm->setData($data);

                    // Validate form
                    if($lecturerSpaceLoginForm->isValid()) {

                        // Get filtered and validated data
                        $data = $lecturerSpaceLoginForm->getData();

                        // Perform login attempt.
                        $result = $this->authManager->lecturerLogin($data['phoneNumber'], 
                                $data['password'], $data['remember_me']);

                        // Check result.
                        if ($result->getCode()==Result::SUCCESS) {
                            // Get redirect URL.
                            $redirectUrl = $this->params()->fromPost('redirect_url', '');
                            $teacher= $this->entityManager->getRepository(Teacher::class)->findOneByPhoneNumber($data['phoneNumber']);
                            if($teacher == null)
                                $teacher= $this->entityManager->getRepository(Teacher::class)->findOneByEmail($data['phoneNumber']);

                             $name = $teacher->getName()." ".$teacher->getSurname();
                             $this->sessionContainer->LoggedInUser = ["id"=>$teacher->getId(), "phoneNumber"=>$teacher->getPhoneNumber(),"name"=>$teacher->getName()." ".$teacher->getSurname()];



                            if (!empty($redirectUrl)) {
                                // The below check is to prevent possible redirect attack 
                                // (if someone tries to redirect user to another domain).
                                $uri = new Uri($redirectUrl);
                                if (!$uri->isValid() || $uri->getHost()!=null)
                                    throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
                            }

                            // If redirect URL is provided, redirect the user to that URL;
                            // otherwise redirect to Home page.
                            if(empty($redirectUrl)) {
                                return $this->redirect()->toRoute('goLecturer');
                            } else {
                                $this->redirect()->toUrl($redirectUrl);
                            }
                        } else {
                        $isLoginError = true;}
                    } else {
                        $isLoginError = true;
                    }                     
                }
            } 

            $view = new ViewModel([
                'sForm' => $studentSpaceLoginForm,
                'lForm' => $lecturerSpaceLoginForm,
                'isLoginError' => $isLoginError,
               
                'redirectUrl' => $redirectUrl,
                'userName' => $this->sessionContainer->userName
            ]);
           // $view->setTerminal(true);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }           

    }    
    /**
     * The "logout" action performs logout operation.
     */
    public function logoutAction() 
    {        
        $this->authManager->logout();
        
        return $this->redirect()->toRoute('home');
    }
    
    public function notAuthorizedAction()
    {
            $this->getResponse()->setStatusCode(403);
    
    return new ViewModel();
    }
}