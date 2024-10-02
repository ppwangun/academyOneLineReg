<?php

namespace User\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\Result;
use Laminas\Uri\Uri;
use User\Form\LoginForm;
use Laminas\Permissions\Rbac\Rbac;
use Application\Entity\User;



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
    
    /**
     * Constructor.
     */
    public function __construct($entityManager, $authManager, $authService, $userManager,$sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->authManager = $authManager;
        $this->authService = $authService;
        $this->userManager = $userManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    public function indexAction() {
       //redirect to the login action of authController
        return  $this->redirect()->toRoute('home');
    }
    
    /**
     * Authenticates user given email address and password credentials.     
     */
    public function loginAction()
    {
		$this->layout()->setTemplate('layout/layout');
        $this->entityManager->getConnection()->beginTransaction();
        try
        {		

			// Retrieve the redirect URL (if passed). We will redirect the user to this
			// URL after successfull login.
			$redirectUrl = (string)$this->params()->fromQuery('redirectUrl', '');
			if (strlen($redirectUrl)>2048) {
				throw new \Exception("Too long redirectUrl argument passed");
			}
			
			// Check if we do not have users in database at all. If so, create 
			// the 'Admin' user.
			//$this->userManager->createAdminUserIfNotExists();
                       // $this->userManager->updateUser();
			
                        $this->entityManager->getConnection()->commit();
			// Create login form
			$form = new LoginForm(); 
			$form->get('redirect_url')->setValue($redirectUrl);
			
			// Store login status.
			$isLoginError = false;
			
			// Check if user has submitted the form
			if ($this->getRequest()->isPost()) {
			   
				// Fill in the form with POST data
				$data = $this->params()->fromPost();            
				
				$form->setData($data);
			   
				// Validate form
				if($form->isValid()) {
				  // $this->userManager->updateUser(null,$data);
					// Get filtered and validated data
					$data = $form->getData();

                                      
					
					// Perform login attempt.
					$result = $this->authManager->login($data['email'], 
							$data['password'], $data['remember_me']);
                                        

                                                 
                                        // Check result.
					if ($result->getCode()==Result::SUCCESS) {
						// Get redirect URL.
						$redirectUrl = $this->params()->fromPost('redirect_url', '');
						$user = $this->entityManager->getRepository(User::class)->findOneByEmail($data['email']);
                                                $this->sessionContainer->userName = $user->getNom();
                                                $this->sessionContainer->userEmail = $user->getEmail();
                                                $this->sessionContainer->userId = $user->getId();

                                                						
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
							return $this->redirect()->toRoute('home');
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
     * The "logout" action performs logout operation.
     */
    public function logoutAction() 
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {

            $email = $this->sessionContainer->userEmail;            
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);
            if($user)
                $user->setConnectedStatus(0);
            
           
            $this->authManager->logout();
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

            return $this->redirect()->toRoute('login');
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    
    public function notAuthorizedAction()
    {
            $this->getResponse()->setStatusCode(403);
    
    return new ViewModel();
    }
}