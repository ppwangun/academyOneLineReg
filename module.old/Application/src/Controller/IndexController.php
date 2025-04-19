<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Application\Form\LoginForm;
use Laminas\Crypt\Password\Bcrypt;

use Application\Entity\User;

class IndexController extends AbstractActionController
{
    private $sessionContainer;
    
    /**
     * Constructor.
     */    
    public function __construct($sessionContainer, $entityManager )
    {
        $this->entityManager = $entityManager;

        $this->sessionContainer = $sessionContainer;
    }
    public function indexAction()
    {
       
        //redirect to the login action of authController
        return  $this->redirect()->toRoute('login');

    }
    
    public function homeAction()
    {
      // $this->layout()->setTemplate('layout/layout');
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        
       // $this->layout()->setTemplate('layout/layout'); 
            $email = $this->sessionContainer->userEmail;
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);
            

            //Reset password if first connection
             if($user->getFirstConnection())
             {
                
                $this->layout()->setTemplate('layout/layout'); 
             
             
                return $this->redirect()->toRoute('passwordReset'); 
             }
             
             $user->setConnectedStatus(1);
             $user->setLastConnectedDate(new \DateTime(date('Y-m-d h:i:s')));
             $this->entityManager->flush();
             $this->entityManager->getConnection()->commit();
        
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            
        
        $view = new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
              
        return $view;

    }
    
    public function passwordResetAction()
    {
       $this->layout()->setTemplate('layout/layout');
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
        
            $data = $this->params()->fromPost();        
            $error=0;
            $msge = "";
           // $this->layout()->setTemplate('layout/layout'); 
                $email = $this->sessionContainer->userEmail;
                $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);  

            // Encrypt password and store the password in encrypted state.
            $bcrypt = new Bcrypt();
            $passwordHash = $bcrypt->create("Secur1ty");


            if(sizeof($data)>0)
            {
                $valid = $bcrypt->verify($data["current_password"],$user->getPassword());
                if($valid)
                {
                    if($data["new_password"]==$data["confirmed_password"]){
                        if(strlen($data["new_password"])<6)
                        {
                            $error = 1;
                            $msge = "Le mot de passe doit conenir au moins 6 caractères";
                        }
                        elseif(!preg_match('/[^A-Za-z0-9]/',$data["new_password"]))
                        {
                            $error = 1;
                            $msge = "Le mot de passe doit conenir au moins un caractère spécial";

                        }
                        else
                        {
                            $passwordHash = $bcrypt->create($data["new_password"]);
                            $user->setPassword($passwordHash);
                            $user->setFirstConnection(0);
                            
                            $this->entityManager->flush();
                            $this->entityManager->getConnection()->commit();
                            return $this->redirect()->toRoute('home');                            
                        }


                    }
                    else
                    {
                        $error = 1;
                        $msge = "les deux nouveaux mots de passe  ne correspondent pas"; 
                    }
                }
                else
                {
                    $error = 1;
                    $msge = "Mot de passe actuel non valide";
                }
            }

        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            

        $view =  new ViewModel([

            'msge' => $msge,
            'error'=>$error
        ]);
        return $view;

    }     

}
