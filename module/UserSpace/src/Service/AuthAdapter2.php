<?php
namespace User\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Application\Entity\AdmittedStudentView;
use Application\Entity\AdmittedStudentForActiveRegistrationYearView;
use Application\Entity\Student;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login (email) and password.
 * If such user exists, the service returns his identity (email). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */
class AuthAdapter2 implements AdapterInterface
{

    /**
     * AdmittedStudentView numDossier.
     * @var string 
     */
    private $numDossier;
    

    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * Constructor.
     */
    public function __construct($entityManager,$numDossier)
    {
        $this->entityManager = $entityManager;
        $this->numDossier = $numDossier;
    }
    


    /**
     * Sets user email.     
     */
    public function setNumDossier($numDossier) 
    {
        $this->numDossierr = $numDossier;        
    }
    

    
    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {                
        // Check the database if there is a user with such matricule.
        $student = $this->entityManager->getRepository(AdmittedStudentForActiveRegistrationYearView::class)
                ->findOneByNumDossier($this->numDossier);
        
        // If there is no such user, return 'Identity Not Found' status.
        if ($student==null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['Invalid credentials.']);        
        }   
        
        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        if ($student->getStatus()==Student::STATUS_RETIRED) {
            return new Result(
                Result::FAILURE, 
                null, 
                ['Student is retired.']);        
        }
        
        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        //$bcrypt = new Bcrypt();
        //$passwordHash = $user->getPassword();
       
 
            return new Result(
                    Result::SUCCESS, 
                    $this->numDossier, 
                    ['Authenticated successfully.']);        
        
      
        
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Invalid credentials.']);        
    }

}