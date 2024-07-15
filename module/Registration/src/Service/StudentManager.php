<?php
namespace Registration\Service;

use Laminas\Http\Request;
use Laminas\Http\Client;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\Faculty;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\AdmittedStudentView;
use Application\Entity\AdmittedStudentForActiveRegistrationYearView;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\OnlineRegistrationYearTeachingUnitView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\SubjectRegistrationOnlineRegistrationYearView;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\RegistrationPerClassView;
use Laminas\Hydrator\Reflection as ReflectionHydrator;
use Patrickmaken\AvlyText\Client as AVTClient; 


use Laminas\Http\Header\Date;




class StudentManager {
    
    private $entityManager1;
    private $dossier_number;




    public function __construct($entityManager1)
    {
        $this->entityManager1 = $entityManager1;
       
    }
    
    
   public function getCurrentYearCode()
   {
       $acadyr = $this->entityManager1->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
       return $acadyr.getCode(); 
       
   }
   
   public function getCurrentYearID()
   {
       $acadyr = $this->entityManager1->getRepository(AcademicYear::class).findOneByOnlineRegistrationDefaultYear(1);
       return $acadyr->getId();
       
   }
   
   
   public function getCurrentYear()
   {
       $acadyr = $this->entityManager1->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
       return $acadyr;
       
   } 
   
   public function getCurrentYearSem($classe)
   {
       $i=0;
       $classe = $this->entityManager1->getRepository(ClassOfStudy::class)->findByCode($classe);
       $acadyr = $this->entityManager1->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
       $semsters = $this->entityManager1->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadyr) );
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
       
            $date_naissance = strtotime($data['date_naissance']);
            $date_naissance = date('Y-m-d',$date_naissance);

            //check first if the student already exists
            $std = $this->entityManager1->getRepository(Student::class)->findOneByMatricule($data["matricule"]);

            //$student->setAdmission();
            if($std)
            {

                 $std->setMatricule($data["matricule"]);
                 $std->setNom($data["nom"]);
                 $std->setPrenom($data["prenom"]);
                 $std->setDateOfBirth(new \DateTime($date_naissance));
                //Update student
               // $this->entityManager1->persist();   
                 $this->entityManager1->flush();

                 //$this->stdPedagogicRegistration($data['classe'],$std);

            }
            else
            {
                //create new student
                $student = new Student();
                $student->setMatricule($data["matricule"]);
                $student->setNom($data["nom"]);
                $student->setPrenom($data["prenom"]);
                $student->setDateOfBirth(new \DateTime($date_naissance));
                $this->entityManager1->persist($student);
                $this->entityManager1->flush();
                $std = $student;
                //$this->stdPedagogicRegistration($data['classe'],$student);

            }

            return $std;
      
   }
   
   //adding student à a current academic year student
   public function stdAdminRegistration($data,$status)
   {
   
            $std = $this->entityManager1->getRepository(Student::class)->findOneByMatricule($data["matricule"]);

            //Finding student registered for the current academic year       
            $isRegistered = $this->entityManager1->getRepository(AdminRegistration::class)->findOneBy(array('student'=>$std,'academicYear'=>$this->getCurrentYear()));

            //Checking if classe provided is available
            $class = $this->entityManager1->getRepository(ClassOfStudy::class)->findOneByCode($data["classe"]); 
           // $admission = $this->entityManager1->getRepository(Admission::class)->findOneByCode($class_code);

             //generate random number of 8 digits and check if the number already exist in the database befor assigning
            //Each new admission implies the creation of a new crontact between student and the university
             $current_yr = date('y');
             $this->contrat_id = mt_rand(100,999);
             $this->contrat_id = $current_yr.$data["classe"].$this->contrat_id;
             while($this->entityManager1->getRepository(AdminRegistration::Class)->findOneByContratId($this->contrat_id))
             {
                 $current_yr = date('y');
                 $this->contrat_id = mt_rand(100,999);
                 $this->contrat_id = $current_yr.$data["classe"].$this->contrat_id;
             }
            $currentDate = date_create(date('Y-m-d H:i:s'));
            $adminRegistration = new AdminRegistration();

            $adminRegistration->setAcademicYear($this->getCurrentYear());
            $adminRegistration->setClassOfStudy($class);
            $adminRegistration->setStudent($std);
            $adminRegistration->setContratId($this->contrat_id);
           // $adminRegistration->setDecision(NULL);
            $adminRegistration->setRegistrationStatus(1);
            $adminRegistration->setStatus($status);

            //check if student is already registered for the current year
            if($isRegistered)
            {

                 $isRegistered->setAcademicYear($this->getCurrentYear());
                 $isRegistered->setClassOfStudy($class);
                 //$isRegistered->setStudent($std);
                 
                 $isRegistered->setRegisteringDate($currentDate);
                 //$isRegistered->setDecision(NULL);
                 $isRegistered->setRegistrationStatus(1);
                 $isRegistered->setStatus($status);
                 $this->entityManager1->flush();
                 return $isRegistered;

            }
            else{
                $this->entityManager1->persist($adminRegistration);
                $this->entityManager1->flush();
                return $adminRegistration;

            }
            
            
    
   }
   
  
   //register student to subjects of a given class
   public function stdPedagogicRegistration($classe,$student)
   {
 
                  //collecting all teaching unit beloging to the classe entered as parameter
            $classe_ue = $this->entityManager1->getRepository(CurrentYearTeachingUnitView::class)->findByClasse($classe);

            //Get semester of the current year


             foreach ($classe_ue as $key)
             {

                 $ue = $this->entityManager1->getRepository(TeachingUnit::class)->findOneById($key->getId());
                 $semester = $this->entityManager1->getRepository(Semester::class)->findOneByCode($key->getSemester());
                 $unit_registered = $this->entityManager1->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,
                         "teachingUnit"=>$ue,"semester"=>$semester));

                 //check whether the student is rgistered or not to subject
                 if($unit_registered)
                 {

                     $unit_registered->setStudent($student);
                     $unit_registered->setTeachingUnit($ue);
                     $unit_registered->setSemester($semester);

                     $this->entityManager1->flush();                
                 }
                 else{
                 $unit_registration = new UnitRegistration();
                 $unit_registration->setStudent($student);
                 $unit_registration->setTeachingUnit($ue);
                 $unit_registration->setSemester($semester);
                 $this->entityManager1->persist($unit_registration);
                 $this->entityManager1->flush();
                 }
             }
   
   }
   
   //this function report backlogs subjects to the current year
   //if a student has failed a given subjects th previous year, that subject
   //should be report to the nex year  for that student
   public function reportBacklogSubject($student,$currentClasse)
   {
        $this->entityManager1->getConnection()->beginTransaction();
        try
        { 
            
        }
        catch(Exception $e)
        {
           $this->entityManager1->getConnection()->rollBack();
            throw $e;
            
        }      
   }
   
   // register student to semesters
   public function stdSemesterRegistration($classe,$student,$mpc)
   {
      
            $classe = $this->entityManager1->getRepository(ClassOfStudy::class)->findByCode($classe);
            $acadyr = $this->entityManager1->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            //$student = $this->entityManager1->getRepository(Student::class)->findByClasse($student);
            $semester= $this->entityManager1->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe,"academicYear"=>$acadyr));
            foreach($semester as $sem)
            {
               $stdSemRegistration = $this->entityManager1->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,
                        "semester"=>$sem));

               if($stdSemRegistration)
               {
                $stdSemRegistration->setSemester($sem->getSemester());
                $stdSemRegistration->setMpcPrevious($mpc);
                $stdSemRegistration->setStudent($student); 
                $stdSemRegistration->setNbCredtisCapitalizedPrevious(0);
                //$stdSemRegistration->setNbCredtisCapitalizedPrevious(0);
                //$stdSemRegistration->setCountingSemRegistration(1);                
                $this->entityManager1->flush();
               }
               else
               {
                $stdSemRegistration = new StudentSemRegistration();
                $stdSemRegistration->setSemester($sem->getSemester());
                $stdSemRegistration->setMpcPrevious($mpc);
                $stdSemRegistration->setNbCredtisCapitalizedPrevious(0);
                $stdSemRegistration->setCountingSemRegistration(1);
                $stdSemRegistration->setStudent($student);
                //if($sem->getSemester()->getRanking()=1) $stdSemRegistration->sett

                $this->entityManager1->persist($stdSemRegistration);
                $this->entityManager1->flush();
               }
            }

        
       
   }
   
   public function stdFurtherSubjectsRegistration($stdid,$subjects)
   {
       
       
   }
   
   // This function automatically genrates student ID (matricule)
   public function studentIdGeneration($classe,$cpt)
   {
 
            //list of alla faculty
   
            $classe= $this->entityManager1->getRepository(ClassOfStudy::class)->findOneByCode($classe);
            $fac = $classe->getDegree()->getFieldStudy()->getFaculty();
            $registration = $this->entityManager1->getRepository(RegistrationPerClassView::class)->findBy(array("faculty"=>$fac->getCode(),"niveau"=>1));
            $count = sizeof($registration); 
            /*$acadYr = $this->entityManager->getRepository(AcademicYear::Class)->findOneBy(array("onlineRegistrationDefaultYear"=>1));
            $prefixe = substr($acadYr->getCode(),-4);*/
            $prefixe = 22;
            $matricule = $prefixe;
            
                if($fac->getCode() == "ISSS"||$fac->getCode() == "FSS")
                {
   
                    $count=$count+$cpt;
                    switch ($count)
                    {
                        case $count<10:  $matricule.= "B"."00".$count; break;
                        case $count<100:  $matricule.= "B"."0".$count; break;
                        case $count<1000:  $matricule.= "B".$count; break;
                    }
                    return $matricule;

                }
                if($fac->getCode() == "ISST"||$fac->getCode() == "FST")
                {
                    $count=$count+$cpt;
                    switch ($count)
                    {
                        case $count<10:  $matricule.= "C"."00".$count; break;
                        case $count<100:  $matricule.= "C"."0".$count; break;
                        case $count<1000:  $matricule.= "C".$count; break;
                    }
                    return $matricule;

                }
                if($fac->getCode() == "IEA"||$fac->getCode() == "IEASSM")
                {

                    $count=$count+$cpt;
                    switch ($count)
                    {
                        case $count<10:  $matricule.= "D"."00".$count; break;
                        case $count<100:  $matricule.= "D"."0".$count; break;
                        case $count<1000:  $matricule.= "D".$count; break;
                    }
                    return $matricule;

                }     
                /* if($fac->getCode() == "FSAV")
                {

                    $count=$count+$cpt;
                    switch ($count)
                    {
                        case $count<10:  $matricule.= "E"."00".$count; break;
                        case $count<100:  $matricule.= "E"."0".$count; break;
                        case $count<1000:  $matricule.= "E".$count; break;
                    }
                    return $matricule;

                }   
                 if($fac->getCode() == "FTIC")
                {

                    $count=$count+$cpt;
                    switch ($count)
                    {
                        case $count<10:  $matricule.= "F"."00".$count; break;
                        case $count<100:  $matricule.= "F"."0".$count; break;
                        case $count<1000:  $matricule.= "F".$count; break;
                    }
                    return $matricule;

                } */               

 
         
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
            $host_name = 'www.google.com';
            $port_no = '80';

            $st = (bool)@fsockopen($host_name, $port_no, $err_no, $err_str, 10);
            if ($st)
            {

                $senderID = 'Agenla';
                $api_key = "cnZTHTWhO0HHsivMJMWqIXSqdKt8ifH8kP5IRHbqYTquHqjux5ehSLxpWY4lWkkwNlw8";
                $response = AVTClient::sendSMS($phoneNumber, $msge, $senderID, $api_key);
                          
                if ($response["status"]=="delivered") {
                    // the POST was successful
                    $msgeStatus=1;
                }
            } 
            
            return $msgeStatus;
   }
   
   //This function takes as parameter calss code and return all subjects affiliated tho the class
   public function getSubjectsByClasse($classCode)
   {

        $subjects = $this->entityManager1->getRepository(OnlineRegistrationYearTeachingUnitView::class)->findBy(array("classe"=>$classCode,"isPreviousYearSubject"=>0));
        foreach($subjects as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);
            $subjects[$key] = $data;
        }            

        return $subjects;
   
   }
   
   //This function takes as parameter student ID (Matricule an returns all subjects to wiwch student is registered)
   public function getRegisteredSubjectsByStudent($matricule)
   {


        $subjects = $this->entityManager1->getRepository(SubjectRegistrationOnlineRegistrationYearView::class)->findByMatricule($matricule);
        foreach($subjects as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $subjects[$key] = $data;
        }            

        return $subjects;            
      
   }
   
   public function getStatus($studentMat)
   {   
       $student = $this->entityManager1->getRepository(AdmittedStudentForActiveRegistrationYearView::class)->findOneByNumDossier($studentMat);
       if($student)  return $student->getStatus() ;
       else $student = $this->entityManager1->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findOneByMatricule($studentMat);
       return $student->getStatus();
   }
   
   public function redirect($sessionContainer,$studentId,$status,$route)
   {
       $admission = $this->entityManager1->getRepository(AdmittedStudentForActiveRegistrationYearView::class)->findOneByNumDossier($studentId);
       $student = $this->entityManager1->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findOneByMatricule($studentId);
       
       if($admission)
       { 
            if($this->getStatus($studentId)==$status)
            {
                //Check if a student with the given admissioin ID already exist
                
                $stud = $this->entityManager1->getRepository(Student::class)->findOneByAdmission($admission);
                $sessionContainer->userId = $stud->getMatricule();
                $sessionContainer->classeCode =  $admission->getClasse();
                if($stud) { header('Location: endRegistration'); exit();}
            } 
       }
       
       if($student)
       { 
            if($this->getStatus($studentId)==$status)
            {
                $sessionContainer->userId = $student->getMatricule();
                $sessionContainer->classeCode= $student->getClasse();
                header("Location: $route"); exit();
            }           
       }
   }
}


                   
