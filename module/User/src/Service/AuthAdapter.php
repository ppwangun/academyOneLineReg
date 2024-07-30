<?php
namespace User\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Laminas\Authentication\Result;
use Laminas\Crypt\Password\Bcrypt;
use Application\Entity\Admission;
use Application\Entity\Student;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\RegisteredStudentView;
use Application\Entity\Teacher;

/**
 * Adapter used for authenticating user. It takes login and password on input
 * and checks the database if there is a user with such login (email) and password.
 * If such user exists, the service returns his identity (email). The identity
 * is saved to session and can be retrieved later with Identity view helper provided
 * by ZF3.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * Student matricule.
     * @var string 
     */
    private $matricule;
    
    /**
     * Student phoneNumber.
     * @var string 
     */
    private $phoneNumber; 
    
    /**
     * Student password.
     * @var string 
     */
    private $password;    
    
    /**
     * Admission fileNumber.
     * @var string 
     */
    private $fileNumber;
    
    /**
     * Birthdate
     * @var string 
     */
    private $birthdate;
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Sets user email.     
     */
    public function setMatricule($matricule) 
    {
        $this->matricule = $matricule;        
    }

    /**
     * Sets user email.     
     */
    public function setPhoneNumber($phoneNumber) 
    {
        $this->phoneNumber = $phoneNumber;        
    }  
    
    /**
     * Sets user email.     
     */
    public function setPassword($password) 
    {
        $this->password = $password;        
    }    
    /**
     * Sets user email.     
     */
    public function setFileNumber($fileNumber) 
    {
        $this->fileNumber = $fileNumber;        
    }
    
    /**
     * Sets password.     
     */
    public function setBirthdate($birthdate) 
    {
        $this->birthdate = (string)$birthdate;        
    }
    
    /**
     * Performs an authentication attempt.
     */
    public function authenticate()
    {   
        // Check if the student is amont current year student.
        $currentYrStudent = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)
                ->findOneByMatricule($this->matricule);
        // Check the database if there is a user with such matricule.
        $student = $this->entityManager->getRepository(Student::class)
                ->findOneBy(array("matricule"=>$this->matricule,"status"=>[1,7]));
        
        // If student not registered for the current year, return 'pv not available ' status.
        if ($currentYrStudent==null && $student!=null) {
            return new Result(
                Result::PV_NOT_AVAILABLE, 
                null, 
                ['Student not yet registered for the current year.']);        
        }         
        
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
       
        if(date_format($student->getDateOfBirth(),'jmY')==ltrim($this->birthdate,'0'))
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->matricule, 
                    ['Authenticated successfully.']);        
        
       /* if ($bcrypt->verify($this->password, $passwordHash)) {
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->email, 
                    ['Authenticated successfully.']);        
        }   */          
        
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Invalid credentials.']);        
    }
    
    /**
     * Performs an authentication attempt.
     */
    public function lecturerAuthenticate()
    {  
        // Check if the student is amont current year student.
        $teacher = $this->entityManager->getRepository(Teacher::class)
                ->findOneByPhoneNumber($this->phoneNumber);
        
        if($teacher==null) $teacher = $this->entityManager->getRepository(Teacher::class)
                ->findOneByEmail($this->phoneNumber);

     

        // If there is no such user, return 'Identity Not Found' status.
        if ($teacher==null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['Invalid credentials.']);        
        }   
        
        // If the user with such email exists, we need to check if it is active or retired.
        // Do not allow retired users to log in.
        if ($teacher->getStatus()==Teacher::STATUS_ACTIVE) {
            return new Result(
                Result::FAILURE, 
                null, 
                ['Student is retired.']);        
        }
        
        // Now we need to calculate hash based on user-entered password and compare
        // it with the password hash stored in database.
        //$bcrypt = new Bcrypt();
        //$passwordHash = $user->getPassword();
       
       /* if(date_format($student->getDateOfBirth(),'jmY')==ltrim($this->birthdate,'0'))
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->matricule, 
                    ['Authenticated successfully.']);     */   
        $bcrypt = new Bcrypt();
        $passwordHash = $teacher->getPassword();    
        //if ($bcrypt->verify($this->password, $passwordHash)) {
        if ($teacher) {
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->phoneNumber, 
                    ['Authenticated successfully.']);        
        }             
        
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Invalid credentials.']);        
    }
    
    /**
     * Performs an authentication attempt for a new student.
     */
    public function newStudentAuthenticate()
    {                
        // Check the database if there is a user with such matricule.
        $student = $this->entityManager->getRepository(Admission::class)
                ->findOneByFileNumber($this->fileNumber);
        
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
       
        //if(date_format($student->getDateOfBirth(),'jmY')==ltrim($this->birthdate,'0'))
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->fileNumber, 
                    ['Authenticated successfully.']);        
        
       /* if ($bcrypt->verify($this->password, $passwordHash)) {
            // Great! The password hash matches. Return user identity (email) to be
            // saved in session for later use.
            return new Result(
                    Result::SUCCESS, 
                    $this->email, 
                    ['Authenticated successfully.']);        
        }   */          
        
        // If password check didn't pass return 'Invalid Credential' failure status.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Invalid credentials.']);        
    }
}