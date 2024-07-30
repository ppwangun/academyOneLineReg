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
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\RegisteredPaymentView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\SubjectRegistrationOnlineRegistrationYearView;
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
use Application\Entity\Payment;
use Application\Entity\Subject;


use Student\Service\StudentManager;
use ICanBoogie\DateTime;


use Laminas\Hydrator\ReflectionHydrator as ReflectionHydrator;

class IndexController extends AbstractActionController
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
    
    //Check if the payment is done before routing the request
    //Avoid any body to move forward if the payment is not done be fore
    //impossible to directly open links to studentRegistration, newStudentRegistration and insPedagogique
    //if payement is not done
    
    $studentMat = $this->sessionContainer->userId;
    $studentId = $this->sessionContainer->newUserId;
   
    //Checking payment for already students
	if($this->sessionContainer->userId)
	{
		$student = $this->entityManager->getRepository(RegisteredPaymentView::class)->findOneByMatricule($studentMat);
		if($student)
		{
		   $debt = $student->getFeesPaid()-$student->getFeesDebt(); 
		   if($debt<115000)
		   {
			   return $this->redirect()->toRoute('chkPayment');
		   }
		}
	}
    
    //checking payment for new students
	if($this->sessionContainer->newUserId)
	{
		$student = $this->entityManager->getRepository(AdmittedStudentView::class)->findOneByNumDossier($studentId);
		 if($student)
		 {
			if($student->getFeesPaid()<150000)
				return $this->redirect()->toRoute('chkNewStudentPayment');

		 }  
	}	 
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
            $this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");
                             
            
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
            
            
            if($studentMat)
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
                    if($student->getFeesPaid()>=135000)
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
    public function studentRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
            $studentMat= $this->sessionContainer->userId;
            
            //Redirect if the student already created
            $this->studentManager->redirect($this->sessionContainer,$studentMat,3,"endRegistration");
            $this->studentManager->redirect($this->sessionContainer,$studentMat,2,"insPedagogique");
            $this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");
      
            $student = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->find($studentMat);
            
            $hydrator = new ReflectionHydrator();
            $student = $hydrator->extract($student);
           
            $studentName = $student["nom"]." ".$student["prenom"];
            $studentClasse = $student["classe"]; 
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($studentClasse);
            $studentTraining = $classe->getDegree()->getName();
            $studentFilere = $classe->getDegree()->getFieldStudy();
            $studentFaculty = $studentFilere->getFaculty();
       
            $studentFilere= $studentFilere->getName();
            $studentFaculty = $studentFaculty->getName();
            
                $view = new ViewModel([
                    "studentName"=>$studentName,
                    "studentClasse"=>$studentClasse ,
                    "studentTraining"=>$studentTraining,
                    "studentFiliere"=>$studentFilere,
                    "studentFaculty"=>$studentFaculty,
                    "studentMat"=>$studentMat
                    
                    

               ]);
              // Disable layouts; `MvcEvent` will use this View Model instead
              //$view->setTerminal(true);

              return $view;  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

    }
    
    
    public function newStudentRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $studentId= $this->sessionContainer->newUserId;
            
            //Redirect if the student already created 
            $this->studentManager->redirect($this->sessionContainer,$studentId,2,"endRegistration");
            $studentMat= $this->sessionContainer->userId;
           
            if(!is_null($studentMat))
            {
                $this->studentManager->redirect($this->sessionContainer,$studentMat,3,"endRegistration");
                $this->studentManager->redirect($this->sessionContainer,$studentMat,2,"insPedagogique");
                $this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");
               
            }            
 
            $student = $this->entityManager->getRepository(AdmittedStudentForActiveRegistrationYearView::class)->findOneByNumDossier($studentId);
            $hydrator = new ReflectionHydrator();
            $student = $hydrator->extract($student);
      
            $studentName = $student["nom"]." ".$student["prenom"];
            $studentClasse = $student["classe"];
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($studentClasse);
            $studentTraining = $classe->getDegree()->getName();
            $studentFilere = $classe->getDegree()->getFieldStudy();
            $studentFaculty = $studentFilere->getFaculty();
       
            $studentFilere= $studentFilere->getName();
            $studentFaculty = $studentFaculty->getName();
            
                $view = new ViewModel([
                    "studentName"=>$studentName,
                    "studentClasse"=>$studentClasse ,
                    "studentTraining"=>$studentTraining,
                    "studentFiliere"=>$studentFilere,
                    "studentFaculty"=>$studentFaculty
                    
                    

               ]);
              // Disable layouts; `MvcEvent` will use this View Model instead
              //$view->setTerminal(true);

              return $view;  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

    }
    
    public function saveRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            if ($this->getRequest()->isPost()){

            $studentDetails= $this->params()->fromPost();



            $studentMat= $this->sessionContainer->userId;
            $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($studentMat);
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findByOnlineRegistrationDefaultYear(1);
            $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("academicYear"=>$academicYear,"student"=>$student));

            //$file = addcslashes(file_get_contents($_FILES["image"]["tmp_name"]));
            //$file = addslashes(file_get_contents($studentDetails["imageSrc"]));
            // Check file size not exceed 65ko
            //$image_base64 = base64_encode(file_get_contents($_FILES['imageSrc']['tmp_name']));
            // Check file size not exceed 65ko
            if (isset($_FILES['imageSrc']['tmp_name'])&&($_FILES["imageSrc"]["size"] > 100000)) {
                
                $view = new ViewModel([
                    "studentName"=>$studentDetails["nom"]." ".$studentDetails["prenom"],
                    "studentClasse"=>$studentDetails["classe"] ,
                    "studentTraining"=>$studentDetails["training"],
                    //"studentFiliere"=>$studentDetails["filiere"],
                    "studentFaculty"=>$studentDetails["faculty"],
                    "student"=>json_encode($studentDetails)
                ]);
                return $view;
 
            }
           //redirect form if non valid 
           if(!$this->validateFormData($studentDetails))
           {
               $this->redirect()->toRoute("newStudentRegistration");
               return;
           }   
            if (!empty($_FILES['imageSrc']['tmp_name'])&&($_FILES["imageSrc"]["size"] < 100000))
            {
                $image_base64 = base64_encode(file_get_contents($_FILES['imageSrc']['tmp_name']));
                $student->setPhoto($image_base64);
            }

            

            $student->setNom($studentDetails["nom"]);
            $student->setPrenom($studentDetails["prenom"]);
            //$date_naissance = date("Y-m-d",$studentDetails["birthdate"]);
            // $date = new \DateTime($studentDetails["dateOfBirth"]);
            //$student->setDateOfBirth($date);
            $student->setBornAt($studentDetails["bornAt"]);
            $student->setPhoneNumber($studentDetails["phoneNumber"]);
            $student->setGender($studentDetails["gender"]);
            $student->setEmail($studentDetails["email"]);
            $student->setRegionOfOrigin($studentDetails["regionOfOrigin"]);
            $student->setNationality($studentDetails["nationality"]);
            $student->setReligion($studentDetails["religion"]);
            $student->setLanguage($studentDetails["language"]);
            $student->setMaritalStatus($studentDetails["maritalStatus"]);
            $student->setWorkingStatus($studentDetails["workingStatus"]);
            $student->setHandicap((isset($studentDetails["handicap1"])&&!empty($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));

            $student->setfatherName($studentDetails["fatherName"]);
            $student->setFatherProfession($studentDetails["fatherProfession"]);
            $student->setFatherPhoneNumber($studentDetails["fatherPhoneNumber"]);
            $student->setFatherEmail($studentDetails["fatherEmail"]);
            $student->setFatherCountry($studentDetails["fatherCountry"]);
            $student->setFatherCity($studentDetails["fatherCity"]);

            $student->setMotherName($studentDetails["motherName"]);
            $student->setMotherProfession($studentDetails["motherProfession"]);
            $student->setMotherPhoneNumber($studentDetails["motherPhoneNumber"]);
            $student->setMotherEmail($studentDetails["motherEmail"]);
            $student->setMotherCountry($studentDetails["motherCountry"]);
            $student->setMotherCity($studentDetails["motherCity"]);

            $student->setSponsorName($studentDetails["sponsorName"]);
            $student->setSponsorProfession($studentDetails["sponsorProfession"]);
            $student->setSponsorPhoneNumber($studentDetails["sponsorPhoneNumber"]);
            $student->setSponsorEmail($studentDetails["sponsorEmail"]);
            $student->setSponsorCountry($studentDetails["sponsorCountry"]);
            $student->setSponsorCity($studentDetails["sponsorCity"]);

            $student->setLastSchool($studentDetails["lastSchool"]);
            $student->setEnteringDegree($studentDetails["enteringDegree"]);
            $student->setDegreeId($studentDetails["degreeId"]);

            $student->setDegreeOption($studentDetails["degreeOption"]);
            $student->setDegreeExamCenter($studentDetails["degreeExamCenter"]);
            $student->setDegreeSession($studentDetails["degreeSession"]);
            $student->setDegreeJuryNumber($studentDetails["degreeJuryNumber"]);
            $student->setDegreeReferenceId($studentDetails["degreeReferenceId"]);


            $student->setSportiveInformation($studentDetails["sport"]);
            $student->setCulturalInformation($studentDetails["cultural"]);
            $student->setAssociativeInformation($studentDetails["association"]);
            $student->setItKnowledge($studentDetails["computer"]);


            //perform student administrative registration and update registration status to 2
            $data["matricule"]=$studentMat;
            $data["classe"]=$studentDetails["classe"];

            //Set status to 1
            $status = 2;

            $adminRegistration = $this->studentManager->stdAdminRegistration($data,$status); 
            $currentDate = date_create(date('Y-m-d H:i:s'));
            $adminRegistration->setRegisteringDate($currentDate);
            //Sending class code to session variable
            $this->sessionContainer->classeCode =  $studentDetails["classe"];


            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            $this->redirect()->toRoute('insPedagogique');
        }
        $this->redirect()->toRoute('studentRegistration');
            $view = new ViewModel([

           ]);
            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);

                    

    }  
    public function saveNewStudentRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
         //Check if the form is submitted
        if ($this->getRequest()->isPost()){
            
        
            $studentDetails= $this->params()->fromPost();
            $studentId= $this->sessionContainer->newUserId;

            //Redirect to 
            if($this->studentManager->getStatus($studentId)==2)
            {
                $admission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($studentId);
                $student = $this->entityManager->getRepository(Student::class)->findOneByAdmission($admission);
                if($student) $this->redirect()->toRoute("endRegistration");
            }


            $stdAdmission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($studentId);
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);


            //check Matricule unicity

            $cpt=1;
            $matricule = $this->studentManager->studentIdGeneration($studentDetails["classe"],$cpt); 
            
            while($this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findOneByMatricule($matricule))
            {
                $cpt++;
                $matricule = $this->studentManager->studentIdGeneration($studentDetails["classe"],$cpt);
            }


            /*$img = str_replace('data:image/jpeg;base64,', '', $studentDetails["imageSrc"]);
            $img = str_replace(' ', '+', $img);*/
            
            $image_base64 = base64_encode(file_get_contents($_FILES['imageSrc']['tmp_name']) );
            // Check file size not exceed 65ko
            if (!isset($image_base64)||empty($image_base64)||($_FILES["imageSrc"]["size"] > 100000)) {
                
                $view = new ViewModel([
                    "studentName"=>$studentDetails["nom"]." ".$studentDetails["prenom"],
                    "studentClasse"=>$studentDetails["classe"] ,
                    "studentTraining"=>$studentDetails["training"],
                    //"studentFiliere"=>$studentDetails["filiere"],
                    "studentFaculty"=>$studentDetails["faculty"],
                    "student"=>json_encode($studentDetails)
                ]);
                return $view;
                return;
                
            }
           //redirect form if non valid 
           if(!$this->validateFormData($studentDetails))
           {
               return $this->redirect()->toRoute("newStudentRegistration");
               
           }            
      
            

            $student = new Student();
            $student->setMatricule($matricule);
            $student->setPhoto($image_base64);

            $student->setNom($studentDetails["nom"]);
            $student->setPrenom($studentDetails["prenom"]);
            //$date_naissance = date("Y-m-d",$studentDetails["dateOfBirth"]);
            $studentDetails["dateOfBirth"] = trim(str_replace('"','',$studentDetails["dateOfBirth"]));
            //echo "je suis dedans".$studentDetails["dateOfBirth"]; 
            //var_dump($studentDetails);exit;
            $date = new DateTime($studentDetails["dateOfBirth"]);
            $student->setDateOfBirth($date);
            $student->setBornAt($studentDetails["bornAt"]);
            $student->setPhoneNumber($studentDetails["phoneNumber"]);
            $student->setGender($studentDetails["gender"]);
            $student->setEmail($studentDetails["email"]);
            $student->setRegionOfOrigin($studentDetails["regionOfOrigin"]);
            $student->setNationality($studentDetails["nationality"]);
            $student->setReligion($studentDetails["religion"]);
            $student->setLanguage($studentDetails["language"]);
            $student->setMaritalStatus($studentDetails["maritalStatus"]);
            $student->setWorkingStatus($studentDetails["workingStatus"]);
            $student->setHandicap((isset($studentDetails["handicap1"])&&!empty($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));

            $student->setfatherName($studentDetails["fatherName"]);
            $student->setFatherProfession($studentDetails["fatherProfession"]);
            $student->setFatherPhoneNumber($studentDetails["fatherPhoneNumber"]);
            $student->setFatherEmail($studentDetails["fatherEmail"]);
            $student->setFatherCountry($studentDetails["fatherCountry"]);
            $student->setFatherCity($studentDetails["fatherCity"]);

            $student->setMotherName($studentDetails["motherName"]);
            $student->setMotherProfession($studentDetails["motherProfession"]);
            $student->setMotherPhoneNumber($studentDetails["motherPhoneNumber"]);
            $student->setMotherEmail($studentDetails["motherEmail"]);
            $student->setMotherCountry($studentDetails["motherCountry"]);
            $student->setMotherCity($studentDetails["motherCity"]);

            $student->setSponsorName($studentDetails["sponsorName"]);
            $student->setSponsorProfession($studentDetails["sponsorProfession"]);
            $student->setSponsorPhoneNumber($studentDetails["sponsorPhoneNumber"]);
            $student->setSponsorEmail($studentDetails["sponsorEmail"]);
            $student->setSponsorCountry($studentDetails["sponsorCountry"]);
            $student->setSponsorCity($studentDetails["sponsorCity"]);

            $student->setLastSchool($studentDetails["lastSchool"]);
            $student->setEnteringDegree($studentDetails["enteringDegree"]);
            $student->setDegreeId($studentDetails["degreeId"]);

            $student->setDegreeOption($studentDetails["degreeOption"]);
            $student->setDegreeExamCenter($studentDetails["degreeExamCenter"]);
            $student->setDegreeSession($studentDetails["degreeSession"]);
            $student->setDegreeJuryNumber($studentDetails["degreeJuryNumber"]);
            $student->setDegreeReferenceId($studentDetails["degreeReferenceId"]);


            $student->setSportiveInformation($studentDetails["sport"]);
            $student->setCulturalInformation($studentDetails["cultural"]);
            $student->setAssociativeInformation($studentDetails["association"]);
            $student->setItKnowledge($studentDetails["computer"]);

            //Set student as active 
            $student->setStatus(1);

            $student->setAdmission($stdAdmission);

            $this->entityManager->persist($student);
            $this->entityManager->flush();

            //sending new created matricule to the userId session varaible
            $this->sessionContainer->userId = $student->getMatricule();
             //Sending class code to session variable

            $this->sessionContainer->classeCode =  $studentDetails["classe"];



            //perform student administrative registration and update registration status to 2
            $status = 2;
            $data["classe"]=$studentDetails["classe"];
            $data['matricule'] = $student->getMatricule();
            
            $adminRegistration = $this->studentManager->stdAdminRegistration($data,$status);
            $currentDate = date_create(date('Y-m-d H:i:s'));
            $adminRegistration->setRegisteringDate($currentDate);
            
            //update payment
            $adminRegistration->setFeesPaid($stdAdmission->getFeesPaid());
            
            //adding payment in payment table
            $payment = new Payment();
            $payment->setAmount($stdAdmission->getFeesPaid());
            $currentDate = date_create(date('Y-m-d H:i:s'));
            date_default_timezone_set('Africa/Douala');
            $payment->setDateTransaction($currentDate);
            $payment->setAcademicYear($this->studentManager->getCurrentYear());
            $payment->setAdminRegistration($adminRegistration);              
            $this->entityManager->persist($payment);
            
            //register student for smesters related to class
            $this->studentManager->stdSemesterRegistration($studentDetails["classe"],$student,0);

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();

            return $this->redirect()->toRoute("insPedagogique");
        }
       
        return $this->redirect()->toRoute("newStudentRegistration");
            $view = new ViewModel([

           ]);
            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 

        // Disable layouts; `MvcEvent` will use this View Model instead
        //$view->setTerminal(true);

                    

    }     
    public function insPedagogiqueAction()
    {
 
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
            $studentMat = $this->sessionContainer->userId;
            
            $classeCode = $this->sessionContainer->classeCode;
            
            //Redirect if the student already created 
            $this->studentManager->redirect($this->sessionContainer,$studentMat,3,"endRegistration");
           
            $this->studentManager->redirect($this->sessionContainer,$studentMat,1,"endRegistration");

            $this->entityManager->getConnection()->commit();
            
          
            $output = new ViewModel([

                "classe"=>$classeCode,
                "matricule"=>$studentMat
            ]);
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function saveInsPedagogiqueAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {     
            $studentMat = $this->sessionContainer->userId;
            
            $classeCode = $this->sessionContainer->classeCode;
            

            $data = $this->params()->fromQuery();
            
      
            
            $subjects = [];
            if(isset($data['subjects']))
            {
                $data = json_decode($data["subjects"],true );
                $subjects = $data['subjects'];
            }
            //retrive student_id from matricule
            $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($studentMat);

            
            
            foreach ($subjects as $key=>$value)
            {
              
                //retrive the subject based on subject code
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($value["idUe"]);
               
                if(isset($value['semID']))
                {
                    $semester = $this->entityManager->getRepository(Semester::class)->find($value['semID']);
                    //check if the subject is already registered for the student
                    $subjects = $this->entityManager->getRepository(Subject::class)->findBy(array("teachingUnit"=>$teachingUnit));
                    $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"semester"=>$semester));

                    if(!$isRegistered )
                    {

                        $unitRegistration = new UnitRegistration();

                        $unitRegistration->setStudent($student);
                        $unitRegistration->setTeachingUnit($teachingUnit);
                        $unitRegistration->setSemester($semester);


                        $this->entityManager->persist($unitRegistration);
                   
						
                    }
                    
                    foreach($subjects as $sub)
                    {
                        $isRegistered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"teachingUnit"=>$teachingUnit,"subject"=>$sub,"semester"=>$semester));
                        if(!$isRegistered)
                        {
                            $unitRegistration = new UnitRegistration();

                            $unitRegistration->setStudent($student);
                            $unitRegistration->setTeachingUnit($teachingUnit);
                            $unitRegistration->setSubject($sub);
                            $unitRegistration->setSemester($semester);

                            $this->entityManager->persist($unitRegistration);
                                                  
                        }
                    }

                    $msge = true; 
                }
            }
            
          
        //perform student academic registration and update registration status to 3
        $status = 1;
       
        $data["classe"]= $classeCode;
        $data['matricule'] = $student->getMatricule();

        $adminRegistration = $this->studentManager->stdAdminRegistration($data,$status); 
        $currentDate = date_create(date('Y-m-d H:i:s'));
        $adminRegistration->setRegisteringDate($currentDate);
        
        //update student admission status to completed
        $studentId = $this->sessionContainer->newUserId;
        if(isset($studentId))
        {
            $stdAdmission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($studentId);
            $stdAdmission->setStatus(2);
        }
        $this->entityManager->flush();
        $this->entityManager->getConnection()->commit();

        return $this->redirect()->toRoute("endRegistration");    
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([

                "classe"=>$classeCode
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    
    public function endRegistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $studentMat = $this->sessionContainer->userId;
            $studentId = $this->sessionContainer->newUserId;
            
            $classeCode = $this->sessionContainer->classeCode;
            //perform student academic registration and update registration status to 1
            //End of registration proces
            
            $status = 1;
            $data["classe"]= $classeCode;
            $data['matricule'] = $studentMat;
            
            //update admin registration status to 1 (active)
           /* $adminRegistration = $this->studentManager->stdAdminRegistration($data,$status);
         
            //update admission to 2 (completed)
           
            if(isset($this->sessionContainer->newUserId))
            {
                $admission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($studentId);
                $admission->setStatus(2);
            }
     
            
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
             $this->entityManager->getConnection()->beginTransaction();*/
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("onlineRegistrationDefaultYear"=>1)); 
            $classe =  $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("code"=>$classeCode));
            $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr) );
            $i=0;
            $sem=[];
            foreach($semesters as $key=>$value)
            {
                //$hydrator = new ReflectionHydrator();
                //$data = $hydrator->extract($value->getSemester());

                $sem[$i] = array("code"=>$value->getSemester()->getCode(),"ranking"=>$value->getSemester()->getRanking());
                $i++;
            }             
      
            
             
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            $acadYr  = $acadYr->getCode();
            
            
            $student = $this->entityManager->getRepository(Student::class)->findOneBy(array("matricule"=>$studentMat));
                $hydrator = new ReflectionHydrator();
                $student = $hydrator->extract($student);
                

            $ue = $this->entityManager->getRepository(SubjectRegistrationOnlineRegistrationYearView::class)->findBy(array("matricule"=>$studentMat,"subjectId"=>[NULL," "]));
         
            if($ue)
                    {
                        foreach($ue as $key=>$value)
                        {
                            $hydrator = new ReflectionHydrator();
                            $data = $hydrator->extract($value);
                            $ue[$key] = $data;

                        }

                    }
                    else $ue=[];            
            
         
            
            $classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();            
            
            $this->entityManager->getConnection()->commit();
            
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new ViewModel([
                'acadYr'=>$acadYr,
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'student'=>$student,
                'subjects'=>$ue,
                'semesters'=>$sem,
                'studentMat'=>$studentMat
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    } 
    

    public function importstudentsAction()
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

            /*$step = new FilterStep();
            $filter = new OffsetFilter(1, 3);
            $step->add($filter);
            $workflow->addStep($step);*/

            //$workflow->process();




          //  $writer = new DoctrineWriter($this->entityManager,'Application:Student');

          //  $writer->prepare();

            // Iterate over the reader and write each row to the database
        foreach ($reader as $row) {
            //$id=$this->studentManager->getRegistrationID(10);
           
            $row_1 = array_slice($row, 0, 4);
            $data = array("matricule"=>$row["matricule"],"classe"=>$row["classe"],"fees"=>$row["fees"],"debt"=>$row["debt"]);
            $std = $this->studentManager->addStudent($row);
           
            
            $this->studentManager->stdAdminRegistration($data);
            $this->studentManager->stdPedagogicRegistration($row["classe"],$std);
            $this->studentManager->stdSemesterRegistration($row["classe"],$std,$row["mpc"]);
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
    
    public function furthersubjectregistrationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
           // if ($this->getRequest()->isPost()){
           
           // Retrieve form data from POST variables
	  $data = $this->params()->fromQuery(); 
          var_dump($data);          exit();
           // }
            
          $view = new JsonModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            
            
        } 
        catch (Exception $ex) {
        $this->entityManager->getConnection()->rollBack();
        throw $ex;

        }       
    }
    
    public function subjectsByClasseAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
       try
        {  
           $data = $this->params()->fromQuery(); 

           $currentClasseSubject = $this->studentManager->getSubjectsByClasse($data["classe"]);

            return new JsonModel([
               $currentClasseSubject
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
                
    }
    //this functiion returns all students backlogs
    public function registeredSubjectsByStudentAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
       try
        { 
           $data = $this->params()->fromRoute('matricule',-1); 
           $studentMat = $this->sessionContainer->userId;
           
           $studentBacklogs = $this->studentManager->getRegisteredSubjectsByStudent($studentMat);

            return new JsonModel([
               $studentBacklogs
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
                
    } 
    public function validateFormData($data)
    {
        if(!isset($data["nom"])||empty($data["nom"]))
            return false;
        if(!isset($data["bornAt"])||empty($data["bornAt"]))
            return false; 
        if(!isset($data["phoneNumber"])||empty($data["phoneNumber"]))
            return false;
        if(!isset($data["gender"])||empty($data["gender"]))
            return false;
        if(!isset($data["email"])||empty($data["email"]))
            return false;        
        if(!isset($data["nationality"])||empty($data["nationality"]))
            return false;        
        if(!isset($data["regionOfOrigin"])||empty($data["regionOfOrigin"]))
            return false;  
        if(!isset($data["religion"])||empty($data["religion"]))
            return false;    
        if(!isset($data["language"])||empty($data["language"]))
            return false; 
        if(!isset($data["maritalStatus"])||empty($data["maritalStatus"]))
            return false;  
        if(!isset($data["workingStatus"])||empty($data["workingStatus"]))
            return false;    
        if(!isset($data["lastSchool"])||empty($data["lastSchool"]))
            return false;
        if(!isset($data["enteringDegree"])||empty($data["enteringDegree"]))
            return false;        
       /* if(!isset($data["degreeId"])||empty($data["degreeId"]))
            return false;        
        if(!isset($data["degreeExamCenter"])||empty($data["degreeExamCenter"]))
            return false;  
        if(!isset($data["degreeOption"])||empty($data["degreeOption"]))
            return false;    
        if(!isset($data["degreeReferenceId"])||empty($data["degreeReferenceId"]))
            return false; 
        if(!isset($data["degreeJuryNumber"])||empty($data["degreeJuryNumber"]))
            return false;  
        if(!isset($data["degreeSession"])||empty($data["degreeSession"]))
            return false;  */  

           
        return true;        
    }	
}
