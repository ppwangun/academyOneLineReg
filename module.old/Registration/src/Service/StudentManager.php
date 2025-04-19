<?php
namespace Registration\Service;

use Laminas\Http\Request;
use Laminas\Http\Client;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\RegisteredStudentView;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\AdmittedStudentView;
use Application\Entity\Subject;
use Laminas\Hydrator\ReflectionHydrator;


use Laminas\Http\Header\Date;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




class StudentManager {
    
    private $entityManager;


    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
       
    }
   public function getCurrentYearCode()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr.getCode(); 
       
   }
   
   public function getCurrentYearID()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class).findOneByID(1);
       return $acadyr->getId();
       
   }
   public function getCurrentRegistrationYear()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
       return $acadyr;
       
   }   
   public function getCurrentYear()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr;
       
   } 
   
   public function getCurrentYearSem($crtClasse)
   {
       $i=0;
       $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findByCode($crtClasse);
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       $semsters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadyr) );
        foreach($semsters as $key=>$value)
        {
            //$hydrator = new ReflectionHydrator();
            //$data = $hydrator->extract($value->getSemester());
            
            $sem[$i] = array("code"=>$value->getSemester()->getCode());
            $i++;
        }        
       return $sem;
       
   }    
   public function getRegistrationID()
   {

            return uniqid();

   }
   //adding student from import
   public function addStudent($data)
   {

        try
        {     
            $date_naissance = strtotime($data['date_naissance']);
            $date_naissance = date('Y-m-d',$date_naissance);

            //check first if the student already exists
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);

            //$student->setAdmission();
            if($std)
            {

                 $std->setMatricule($data["matricule"]);
                 $std->setNom($data["nom"]);
                 $std->setPrenom($data["prenom"]);
                 $std->setGender($data["sexe"]); 
                 $std->setDateOfBirth(new \DateTime($date_naissance));
                 $std->setBornAt($data["lieu_naissance"]);
                 $std->setPhoneNumber($data["std_num_tel"]);
                 $std->setFatherPhoneNumber($data["father_num_tel"]);
                 $std->setMotherPhoneNumber($data["mother_num_tel"]);
                 $std->setSponsorPhoneNumber($data["sponsor_num_tel"]);
                                
                //Update student
               // $this->entityManager->persist();   
                 $this->entityManager->flush();

                 //$this->stdPedagogicRegistration($data['classe'],$std);

            }
            else
            {
                //create new student
                 $std = new Student();
                 $std->setMatricule($data["matricule"]);
                 $std->setNom($data["nom"]);
                 $std->setPrenom($data["prenom"]);
                 $std->setGender($data["sexe"]); 
                 $std->setDateOfBirth(new \DateTime($date_naissance));
                 $std->setBornAt($data["lieu_naissance"]);
                 $std->setPhoneNumber($data["std_num_tel"]);
                 $std->setFatherPhoneNumber($data["father_num_tel"]);
                 $std->setMotherPhoneNumber($data["mother_num_tel"]);
                 $std->setSponsorPhoneNumber($data["sponsor_num_tel"]);
                $this->entityManager->persist($std);
                $this->entityManager->flush();
                
                //$this->stdPedagogicRegistration($data['classe'],$student);

            }

            return $std;

         }
        catch(Exception $e)
        {
           
            throw $e;
            
        }      
   }
 //adding student from import
   public function addAdmittedStudent($data)
   {
      
                $date_admission = strtotime($data['date_admission']);
                $date_admission = date('Y-m-d',$date_admission);
                //$date_admission= \DateTime::createFromFormat('!d/m/Y H:i', $date_admission);
                
                $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
                $classeCode = $data["classe"]; 
                $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("code"=>strval($classeCode)));
                
                if($classe)
                {    
                    $degree = $classe->getDegree();

                    //check first if the student already exists
                    $std = $this->entityManager->getRepository(Admission::class)->findOneBy(array('nom'=>$data["nom"],'classOfStudy'=>$classe,'phoneNumber'=>$data["telephone"]));

                    //$student->setAdmission();
                    if($std)
                    {

                         $std->setNom($data["nom"]);
                         $std->setPrenom($data["prenom"]);
                         $std->setPhoneNumber($data["telephone"]);
                         $std->setClassOfStudy($classe);
                         $std->setDegree($degree);
                         $std->setFeesPaid($data["frais_admission"]);
                         $std->setEntranceType($data["type_concours"]);
                         $std->setAcademicYear($acadyr);
                         $std->setDateAdmission(new \DateTime($date_admission));

                         $this->entityManager->flush();
                         $student = $std;
                         //$this->stdPedagogicRegistration($data['classe'],$std);

                    }
                    else
                    {

                        //create new student
                        $student = new Admission();
                        $student->setNom($data["nom"]);
                        $student->setPrenom($data["prenom"]);
                        $student->setPhoneNumber($data["telephone"]);
                        $student->setFeesPaid($data["frais_admission"]);
                        $student->setEntranceType($data["type_concours"]);
                        $student->setClassOfStudy($classe);
                        $student->setAcademicYear($acadyr);
                        $student->setDegree($degree);                    
                        $student->setDateAdmission(new \DateTime($date_admission));
                        $student->setStatus(0);

                        $this->entityManager->persist($student);
                        $this->entityManager->flush();

                        //$this->stdPedagogicRegistration($data['classe'],$student);

                    }
                }

 
   }      
   //adding student as a current academic year student
   public function stdAdminRegistration($data,$status,$isRepeating)
   {
     
        try
        {      
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule(array($data["matricule"],"status"=>1));

            //Finding student registered for the current academic year       
            $isRegistered = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array('student'=>$std,'academicYear'=>$this->getCurrentRegistrationYear()));

            //Checking if classe provided is available
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["classe"]); 
           // if(!$class) "je suis dedans "; exit; throw new Exception("Classz".$data["classe"]." not found"); 
           // $admission = $this->entityManager->getRepository(Admission::class)->findOneByCode($class_code);

             //generate random number of 8 digits and check if the number already exist in the database befor assigning
            //Each new admission implies the creation of a new crontact between student and the university
             $current_yr = date('y');
             $this->contrat_id = mt_rand(100,999);
             $this->contrat_id = $current_yr.$data["classe"].$this->contrat_id;
             while($this->entityManager->getRepository(AdminRegistration::Class)->findOneByContratId($this->contrat_id))
             {
                 $current_yr = date('y');
                 $this->contrat_id = mt_rand(100,999);
                 $this->contrat_id = $current_yr.$data["classe"].$this->contrat_id;
             }
            $currentDate = date_create(date('Y-m-d H:i:s'));
            if($std&&$class&&!$isRegistered)
            {
                $adminRegistration = new AdminRegistration();

                $adminRegistration->setAcademicYear($this->getCurrentRegistrationYear());
                $adminRegistration->setClassOfStudy($class);
                $adminRegistration->setStudent($std);
                $adminRegistration->setContratId($this->contrat_id);
                $adminRegistration->setRegisteringDate($currentDate);
                $adminRegistration->setIsStudentRepeating($isRepeating);
                $adminRegistration->setDecision(NULL);
                $adminRegistration->setRegistrationStatus(0);
                //$adminRegistration->setFeesDotation($data["dotation"]);
                //$adminRegistration->setFeesBalanceFromPreviousYear($data["dette"]);
                $adminRegistration->setStatus($status);
                $this->entityManager->flush();
                $this->entityManager->persist($adminRegistration);
            }
            //check if student is already registered for the current year
            elseif($isRegistered)
            {
                $isRegistered->setAcademicYear($this->getCurrentRegistrationYear());
                $isRegistered->setClassOfStudy($class);
                $isRegistered->setStudent($std);
                $isRegistered->setRegisteringDate($currentDate);
                $isRegistered->setIsStudentRepeating($isRepeating);
                $isRegistered->setDecision(NULL);
                $isRegistered->setRegistrationStatus(1);
                 //$isRegistered->setFeesDotation($data["dotation"]);
                 //$isRegistered->setFeesBalanceFromPreviousYear($data["dette"]);

                 $this->entityManager->flush();

            }
         
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }          
   }
 
   public function updateStdFinancialInfos($data)
   {
 
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);

            //Finding student registered for the current academic year       
            $isRegistered = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array('student'=>$std,'academicYear'=>$this->getCurrentRegistrationYear()));

            //Checking if classe provided is available
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["classe"]); 
           // $admission = $this->entityManager->getRepository(Admission::class)->findOneByCode($class_code);


            //check if student is already registered for the current year
            if($isRegistered)
                $isRegistered->setFeesPaid($data["montant"]);

            $this->entityManager->flush();

           
                
   }
  
   //register student to subjects of a given class
   public function stdPedagogicRegistration($classe,$student)
   {
 
                  //collecting all teaching unit beloging to the classe entered as parameter
            $classe_ue = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findByClasse($classe);

            //Get semester of the current year


             foreach ($classe_ue as $key)
             {

                 $ue = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($key->getId());
                 $semester = $this->entityManager->getRepository(Semester::class)->findOneByCode($key->getSemester());
                 $unit_registered = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,
                         "teachingUnit"=>$ue,"semester"=>$semester));
                 if(sizeof($unit_registered)>0)
                 foreach( $unit_registered as $unitR)
                 {
                    $subject = $this->entityManager->getRepository(Subject::class)->findBy(["teachingUnit"=>$ue]);
                    foreach($subject as $sub)
                    {
                       //check whether the student is rgistered or not to subject
                       $unitR =  $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,
                         "teachingUnit"=>$ue,"subject"=>$sub,"semester"=>$semester));
                       if($unitR)
                       {
                            $unitR->setStudent($student);
                            $unitR->setTeachingUnit($ue);
                            $unitR->setSubject($sub);
                            $unitR->setSemester($semester);
                            $this->entityManager->flush();                
                       }
                       else{
                            $unit_registration = new UnitRegistration();
                            $unit_registration->setStudent($student);
                            $unit_registration->setTeachingUnit($ue);
                            $unit_registration->setSubject($sub);
                            $unit_registration->setSemester($semester);
                            $this->entityManager->persist($unit_registration);
                            $this->entityManager->flush();
                            }
                    }
                 }
                 else{
                        $unit_registration = new UnitRegistration();
                        $unit_registration->setStudent($student);
                        $unit_registration->setTeachingUnit($ue);
                        
                        $unit_registration->setSemester($semester);
                        $this->entityManager->persist($unit_registration);
                        $this->entityManager->flush();                     
                 }
             }

       
   }
   
   //this function report backlogs subjects to the current year
   //if a student has failed a given subjects th previous year, that subject
   //should be report to the nex year  for that student
   public function reportBacklogSubject($student,$currentClasse)
   {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }      
   }
   
   // register student to semesters
   public function stdSemesterRegistration($crtClasse,$student,$mpc,$nbCreditsCapitalized,$totalRegistered,$totalCredits,$registrationTimes)
   {
      
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($crtClasse);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            //$student = $this->entityManager->getRepository(Student::class)->findByClasse($student);
            $semester= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadYr));
            foreach($semester as $sem)
            {
               
               if($sem->getSemester()->getRanking()%2==1)
               {
                    $stdSemRegis = $this->entityManager->getRepository(StudentSemRegistration::class)->findBy(array("student"=>$student,"semester"=>$sem->getSemester()));
                    if (sizeof($stdSemRegis)>0)
                    { 
                    
                        $stdSemRegistration = $stdSemRegis[0];

                        $stdSemRegistration->setSemester($sem->getSemester());
                        $stdSemRegistration->setMpcPrevious($mpc);
                        $stdSemRegistration->setNbCredtisCapitalizedPrevious($nbCreditsCapitalized);
                        $stdSemRegistration->setStudent($student); 
                        $stdSemRegistration->setTotalCreditRegisteredPreviousCycle($totalRegistered);
                        $stdSemRegistration->setTotalCreditsCyclePreviousYear($totalCredits);
                        $stdSemRegistration->setCountingSemRegistration($registrationTimes);
                        
                        for($i=1;$i<sizeof($stdSemRegis);$i++)
                        {
                            $this->entityManager->remove($stdSemRegis[$i]);
                        }
                        $this->entityManager->flush();
                    }
                    else
                    { 
                        $stdSemRegistration = new StudentSemRegistration();
                        $stdSemRegistration->setSemester($sem->getSemester());
                        $stdSemRegistration->setMpcPrevious($mpc);
                        $stdSemRegistration->setNbCredtisCapitalizedPrevious($nbCreditsCapitalized);
                        $stdSemRegistration->setStudent($student);
                        $stdSemRegistration->setTotalCreditRegisteredPreviousCycle($totalRegistered);
                        $stdSemRegistration->setTotalCreditsCyclePreviousYear($totalCredits);
                        $stdSemRegistration->setCountingSemRegistration($registrationTimes);
                     //if($sem->getSemester()->getRanking()=1) $stdSemRegistration->sett

                     $this->entityManager->persist($stdSemRegistration);
                     $this->entityManager->flush();
                    }
               }
            }
        
       
   }
   

   
   // This function automatically genrates student ID (matricule)
   public function studentIdGeneration()
   {
         
            //generate random number of 6 digits and check if the number already exist in the database befor assigning
            $threeDigitNumber = mt_rand(000, 999); 
            $threeDigitNumber = (string)$threeDigitNumber;
            if(strlen($threeDigitNumber)==2)
                $threeDigitNumber = "0".$threeDigitNumber;
            if(strlen($threeDigitNumber)==1)
                $threeDigitNumber = "00".$threeDigitNumber;        
            $date = date('y');
            $date = "23";
            $matricule = $date.'B'.$threeDigitNumber;
            while($this->entityManager->getRepository(RegisteredStudentView::Class)->findOneByMatricule($matricule))
            {
                $threeDigitNumber = (string)mt_rand(100, 199);
                if(sizeof($threeDigitNumber)==2)
                    $threeDigitNumber = "0".$threeDigitNumber;
                if(sizeof($threeDigitNumber)==1)
                    $threeDigitNumber = "00".$threeDigitNumber;            
                    $matricule = $date.'B'.$threeDigitNumber;
            }

            return $matricule;
         
   }
   
   public function sendSMS($phoneNumber,$msge)
   {
            $msgeStatus=0;
            $config = array(
                'adapter'      => 'Laminas\Http\Client\Adapter\Socket',
                'ssltransport' => 'tls',
                // 'sslcapath' => $currentDirectory.'\data\ssl\certs',
                //'sslcafile'=> $currentDirectory.'\data\ssl\certs',
                'sslverifypeer' => false,
            );
            //check firs if internet connextion is active or not
            $host_name = 'api.1s2u.io';
            $port_no = '443';

            $st = (bool)@fsockopen($host_name, $port_no, $err_no, $err_str, 10);
            if ($st)
            {
                
                $client = new Client( 'https://api.1s2u.io/bulksms?username=ppwangun&password=perfect&mt=0&fl=0&sid=UdM&mno='.$phoneNumber.'&msg='.$msge,$config);

                $client->setMethod(Request::METHOD_GET);

                $response = $client->send();               
                if ($response->isSuccess()) {
                    // the POST was successful
                    
                    $msgeStatus=1;
                }
            } 
            
            return $msgeStatus;
   }
   
   //This function takes as parameter calss code and return all subjects affiliated tho the class
   public function getSubjectsByClasse($classCode)
   {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $subjects = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findByClasse($classCode);
            foreach($subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $subjects[$key] = $data;
            }            
          
            return $subjects;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }     
   }
   
   //This function takes as parameter student ID (Matricule an returns all subjects to wiwch student is registered)
   public function getRegisteredSubjectsByStudent($matricule)
   {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 

            $subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByMatricule($matricule);
            foreach($subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                
                $subjects[$key] = $data;
            }            
            
            return $subjects;            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }      
   }
   
   
   //adding student from import
   public function addAllStudent($data)
   {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        
            //$date_naissance = strtotime($data['date_naissance']);
            //$date_naissance = date('Y-m-d',$date_naissance);
            $studentDetails = $data;
                
            //check first if the student already exists
            $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($studentDetails["matricule"]);

            //$student->setAdmission();
            if($student)
            {

                $student->setMatricule(isset($studentDetails["matricule"])?$studentDetails["matricule"]:"");
                $student->setNom(isset($studentDetails["nom"])?$studentDetails["nom"]:"");
                $student->setPrenom(isset($studentDetails["prenom"])?$studentDetails["prenom"]:"");

                $date = new \DateTime((isset($studentDetails["birthDate"])?$studentDetails["birthDate"]:""));
                $student->setDateOfBirth($date);
                $student->setBornAt((isset($studentDetails["birthPlace"])?$studentDetails["birthPlace"]:""));
                $student->setPhoneNumber((isset($studentDetails["phoneNumber"])?$studentDetails["phoneNumber"]:""));
                $student->setGender((isset($studentDetails["gender"])?$studentDetails["gender"]:""));
                $student->setEmail((isset($studentDetails["email"])?$studentDetails["email"]:""));
                $student->setRegionOfOrigin((isset($studentDetails["region"])?$studentDetails["region"]:""));
                $student->setNationality((isset($studentDetails["country"])?$studentDetails["country"]:""));        

                $student->setHandicap((isset($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));
                $student->setReligion((isset($studentDetails["religion"])?$studentDetails["religion"]:""));
                $student->setLanguage((isset($studentDetails["language"])?$studentDetails["language"]:""));
                $student->setMaritalStatus((isset($studentDetails["matrimonialStatus"])?$studentDetails["matrimonialStatus"]:""));
                $student->setWorkingStatus((isset($studentDetails["employmentStatus"])?$studentDetails["employmentStatus"]:""));
                $student->setStatus((isset($studentDetails["status"])?$studentDetails["status"]:""));
                
                $student->setfatherName((isset($studentDetails["fatherName"])?$studentDetails["fatherName"]:""));
                $student->setFatherProfession((isset($studentDetails["fatherProfession"])?$studentDetails["fatherProfession"]:""));
                $student->setFatherPhoneNumber((isset($studentDetails["fatherPhoneNumber"])?$studentDetails["fatherPhoneNumber"]:""));
                $student->setFatherEmail(isset($studentDetails["fatherEmail"])? $studentDetails["fatherEmail"]:"");
    
                $student->setFatherCity((isset($studentDetails["fatherCity"]["name"])?$studentDetails["fatherCity"]["name"]:""));

                $student->setMotherName((isset($studentDetails["motherName"])?$studentDetails["motherName"]:""));
                $student->setMotherProfession((isset($studentDetails["motherProfession"])?$studentDetails["motherProfession"]:""));
                $student->setMotherPhoneNumber((isset($studentDetails["motherPhoneNumber"])?$studentDetails["motherPhoneNumber"]:""));
                $student->setMotherEmail(isset($studentDetails["motherEmail"])? $studentDetails["motherEmail"]:"");

                $student->setMotherCity((isset($studentDetails["motherCity"]["name"])?$studentDetails["motherCity"]["name"]:""));

                $student->setSponsorName((isset($studentDetails["sponsorName"])?$studentDetails["sponsorName"]:""));
                $student->setSponsorProfession((isset($studentDetails["sponsorProfession"])?$studentDetails["sponsorProfession"]:""));
                $student->setSponsorPhoneNumber((isset($studentDetails["sponsorPhoneNumber"])?$studentDetails["sponsorPhoneNumber"]:""));
                $student->setSponsorEmail(isset($studentDetails["sponsorEmail"])? $studentDetails["sponsorEmail"]:"");

                $student->setSponsorCity((isset($studentDetails["sponsorCity"]["name"])?$studentDetails["sponsorCity"]["name"]:""));

                $student->setLastSchool((isset($studentDetails["lastSchool"])?$studentDetails["lastSchool"]:""));
                $student->setEnteringDegree((isset($studentDetails["enteringDegree"])?$studentDetails["entranceCertificate"]:""));
                $student->setDegreeId((isset($studentDetails["degreeId"])?$studentDetails["certificateId"]:""));

                $student->setDegreeOption(isset($studentDetails["degreeOption"])?$studentDetails["certificateOption"] :"");
                $student->setDegreeExamCenter(isset($studentDetails["degreeExamCenter"])?$studentDetails["examCenter"] :"");
                $student->setDegreeSession(isset($studentDetails["degreeSession"])?$studentDetails["examSession"] :"");
                $student->setDegreeJuryNumber(isset($studentDetails["degreeJuryNumber"])?$studentDetails["examJury"] :"");
                $student->setDegreeReferenceId(isset($studentDetails["degreeReferenceId"])?$studentDetails["examReference"] :"");


                $student->setSportiveInformation(isset($studentDetails["sport"])?$studentDetails["sport"] :"");
                $student->setCulturalInformation(isset($studentDetails["cultural"])?$studentDetails["cultural"] :"");
                $student->setAssociativeInformation(isset($studentDetails["association"])?$studentDetails["association"] :"");
                $student->setItKnowledge(isset($studentDetails["computer"])?$studentDetails["computer"] :"");

                
                 //$this->stdPedagogicRegistration($data['classe'],$std);

            }
            else
            {
                //create new student
                $student = new Student();
                $student->setMatricule(isset($studentDetails["matricule"])?$studentDetails["matricule"]:"");
                $student->setNom(isset($studentDetails["nom"])?$studentDetails["nom"]:"");
                $student->setPrenom(isset($studentDetails["prenom"])?$studentDetails["prenom"]:"");

                $date = new \DateTime((isset($studentDetails["birthDate"])?$studentDetails["birthDate"]:""));
                $student->setDateOfBirth($date);
                $student->setBornAt((isset($studentDetails["birthPlace"])?$studentDetails["birthPlace"]:""));
                $student->setPhoneNumber((isset($studentDetails["phoneNumber"])?$studentDetails["phoneNumber"]:""));
                $student->setGender((isset($studentDetails["gender"])?$studentDetails["gender"]:""));
                $student->setEmail((isset($studentDetails["email"])?$studentDetails["email"]:""));
                $student->setRegionOfOrigin((isset($studentDetails["region"])?$studentDetails["region"]:""));
                $student->setNationality((isset($studentDetails["country"])?$studentDetails["country"]:""));        

                $student->setHandicap((isset($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));
                $student->setReligion((isset($studentDetails["religion"])?$studentDetails["religion"]:""));
                $student->setLanguage((isset($studentDetails["language"])?$studentDetails["language"]:""));
                $student->setMaritalStatus((isset($studentDetails["matrimonialStatus"])?$studentDetails["matrimonialStatus"]:""));
                $student->setWorkingStatus((isset($studentDetails["employmentStatus"])?$studentDetails["employmentStatus"]:""));
                $student->setStatus((isset($studentDetails["status"])?$studentDetails["status"]:""));

                $student->setfatherName((isset($studentDetails["fatherName"])?$studentDetails["fatherName"]:""));
                $student->setFatherProfession((isset($studentDetails["fatherProfession"])?$studentDetails["fatherProfession"]:""));
                $student->setFatherPhoneNumber((isset($studentDetails["fatherPhoneNumber"])?$studentDetails["fatherPhoneNumber"]:""));
                $student->setFatherEmail(isset($studentDetails["fatherEmail"])? $studentDetails["fatherEmail"]:"");
                
                $student->setFatherCity((isset($studentDetails["fatherCity"]["name"])?$studentDetails["fatherCity"]["name"]:""));

                $student->setMotherName((isset($studentDetails["motherName"])?$studentDetails["motherName"]:""));
                $student->setMotherProfession((isset($studentDetails["motherProfession"])?$studentDetails["motherProfession"]:""));
                $student->setMotherPhoneNumber((isset($studentDetails["motherPhoneNumber"])?$studentDetails["motherPhoneNumber"]:""));
                $student->setMotherEmail(isset($studentDetails["motherEmail"])? $studentDetails["motherEmail"]:"");

                $student->setMotherCity((isset($studentDetails["motherCity"]["name"])?$studentDetails["motherCity"]["name"]:""));

                $student->setSponsorName((isset($studentDetails["sponsorName"])?$studentDetails["sponsorName"]:""));
                $student->setSponsorProfession((isset($studentDetails["sponsorProfession"])?$studentDetails["sponsorProfession"]:""));
                $student->setSponsorPhoneNumber((isset($studentDetails["sponsorPhoneNumber"])?$studentDetails["sponsorPhoneNumber"]:""));
                $student->setSponsorEmail(isset($studentDetails["sponsorEmail"])? $studentDetails["sponsorEmail"]:"");

                $student->setSponsorCity((isset($studentDetails["sponsorCity"]["name"])?$studentDetails["sponsorCity"]["name"]:""));

                $student->setLastSchool((isset($studentDetails["lastSchool"])?$studentDetails["lastSchool"]:""));
                $student->setEnteringDegree((isset($studentDetails["enteringDegree"])?$studentDetails["entranceCertificate"]:""));
                $student->setDegreeId((isset($studentDetails["degreeId"])?$studentDetails["certificateId"]:""));

                $student->setDegreeOption(isset($studentDetails["degreeOption"])?$studentDetails["certificateOption"] :"");
                $student->setDegreeExamCenter(isset($studentDetails["degreeExamCenter"])?$studentDetails["examCenter"] :"");
                $student->setDegreeSession(isset($studentDetails["degreeSession"])?$studentDetails["examSession"] :"");
                $student->setDegreeJuryNumber(isset($studentDetails["degreeJuryNumber"])?$studentDetails["examJury"] :"");
                $student->setDegreeReferenceId(isset($studentDetails["degreeReferenceId"])?$studentDetails["examReference"] :"");


                $student->setSportiveInformation(isset($studentDetails["sport"])?$studentDetails["sport"] :"");
                $student->setCulturalInformation(isset($studentDetails["cultural"])?$studentDetails["cultural"] :"");
                $student->setAssociativeInformation(isset($studentDetails["association"])?$studentDetails["association"] :"");
                $student->setItKnowledge(isset($studentDetails["computer"])?$studentDetails["computer"] :"");


                $this->entityManager->persist($student);
                
                //$this->stdPedagogicRegistration($data['classe'],$student);

            }
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            //return $std;

         }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }      
   }   
}


                   