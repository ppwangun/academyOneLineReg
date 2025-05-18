<?php
namespace Exam\Service;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\RegisteredStudentView;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\Degree;
use Application\Entity\Faculty;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\GradeValueRange;

use Patrickmaken\Web2Sms\Client as W2SClient;
use Patrickmaken\AvlyText\Client as AVTClient;

use Laminas\Http\Request;
use Laminas\Http\Client;

use Laminas\Hydrator\ReflectionHydrator;


use Laminas\Http\Header\Date;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




class ExamManager {
    
    private $entityManager;
    private $dossier_number;




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
   public function getCurrentYear()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr;
       
   }   

   //This function checks if the mark is registered, validated or confirmed
   //in case mark is confirm, and not validated neither confirmed it return registered mark
   //incase mark is validated it returns validated marks
   //in case mark is confirmed it returns confirmed mark
   //the function takes as parameters the exam and the exam registration
   public function getMark($exam,$examR)
   {
       if($exam->getIsMarkRegistered()==1 && $exam->getIsMarkValidated()==0 && $exam->getIsMarkConfirmed()==0)
           return $examR->getRegisteredMark();
       if($exam->getIsMarkValidated()==1 && $exam->getIsMarkConfirmed()==0 )
           return $examR->getValidatedMark();
       if($exam->getIsMarkConfirmed()==1)
           return $examR->getConfirmedMark();       
   }
   
   //This function takes as paramter the studentId and returns for the given student an array
   //The array contains student performane for each semester he is registered to
   //array()
   public function showStudentPath($studentId)
   {
       $student= $this->entityManager->getRepository(Student::class)->find($studentId);
       $semRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findByStudent($student);
       foreach($semRegistration as $key=>$values)
       {
           
            $i++;
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);
            $semRegistration = $data;
       }
       
       return $semregistration;
   }

   //This function takes as paramter the studentId and returns for the given student an array
   //The array contains student performane for each semester he is registered to
   //array()
   public function showStudentPathByClasse($classe)
   { 
       $array = [];
      
       $i=0;
       //Retrieving student registered to the class for the current year
       $students =  $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("class"=>$classe->getCode())); 
       foreach($students as $std)
       {
           $array[$i]=[];
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("matricule"=>$std->getMatricule()));
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
            $semesters = $this->entityManager->getRepository(\Application\Entity\SemesterAssociatedToClass::class)->findBy(array("academicYear"=>$acadYr,"classOfStudy"=>$classe));
            
            $arr = [];;
            $arr['No'] = array("value"=>$i+1);
            $arr['Matricule'] = array("value"=>$std->getMatricule());
            $arr['Nom'] = array("value"=>$std->getNom());   
            foreach($semesters as $sem)
            {
                $sem = $sem->getSemester();
                $semRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$std,"semester"=>$sem));

               

               // $semRegistration[$key] = $data;
                if($semRegistration)
                { 
                    if($sem->getRanking()%2==1)
                    {
                    $arr["TV n-1"] = array("value"=>$semRegistration->getNbCredtisCapitalizedPrevious());
                    $arr["MPC n-1"] = array("value"=>$semRegistration->getMpcPrevious());
                    
                    $arr["TI-".$sem->getCode()] = array("value"=>$semRegistration->getTotalCreditRegisteredCurrentSem());
                    $arr["TV-".$sem->getCode()] = array("value"=>$semRegistration->getNbCreditsCapitalizedCurrentSem());
                    $arr["MPC"] = array("value"=>$semRegistration->getMpcCurrentSem());
                    }

                }
                else
                {

                    $arr["TI-".$sem->getCode()] = array("value"=>"");
                    $arr["TV-".$sem->getCode()] = array("value"=>"");
                    $arr["MPC"] = array("value"=>"");    
                }
                $array[$i] = array_merge($array[$i],$arr);
            }
                
                $i++;
 
       }
       
       return $array;
   }   
   
   //This fucntion computes the MPS (Moyenne pondérée semestrielle) for a given student
   //It takes as parameter, classe,$semester, and student
    public function computeMps($classe,$semester,$student)
    {
        $sum=0;
        $countCredits=0;
        $i=0;
        
        //collect all subjects to which student is registered
        $subjectRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"semester"=>$semester ));
        $subjectDetails = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("classOfStudy"=>$classe,"semester"=>$semester ));
        //List of teaching unit of the classe
        foreach($subjectDetails as $details)
        {
            $subject[$i]= $details->getTeachingUnit();
            $i++;
        }
        foreach($subjectRegistration as $sub)
        {

            //Student can be registered to subject belonging to other semester
            //we have to make sure the calculation is perform only for subject of semesters entered as parameter
            //this condition checks if the $sub is among the subject of the semester
            if(in_array($sub->getTeachingUnit(),$subject))
            {
                $sum = $sum + $subjectDetails->getCredits()*$sub->getPoints();
                $count = $count + $subjectDetails->getCredits();
            }
        }
        
        return $sum/$count;
    }
    
   //This fucntion computes the MPS (Moyenne pondérée semestrielle) for a given student
   //It takes as parameter, classe,$semester, and student
    public function markAggregation($ue,$classe,$semester)
    {
       
        //collect all subjects to which student is registered
        $stdRegisteredToModule = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"semester"=>$semester,"subject"=>[NULL," "] ));
        $subjectDetails = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("classOfStudy"=>$classe,"semester"=>$semester ));
        
        $stdOutput = [];
        $i=0;
        //List of teaching unit of the classe
        foreach( $stdRegisteredToModule as $std)
        {
            $student = $std->getStudent();
            $subjects = $this->getSubjectFromUe($ue->getId(), $semester->getId(), $classe->getId());
            $mark = 0; $markCC =0; $markCCTP=0; $markEXAMTP = 0; $markEXAM=0;
            $weight = 0;
            $note = round(($std->getNoteExam()),2, PHP_ROUND_HALF_UP);
            $std->setNoteFinal($note);
            $std->setGrade($this->computeGradeSur100($classe, $note));
            $std->setPoints($this->computePointsSur100($classe, $note));
            $std->setNoteCctp(NULL);
            $std->setNoteExamtp(NULL);
            $std->setNoteCc(NULL);
            $std->setNoteExam(NULL); 
            $totalWeight= 0; 
            //$stdOutput[$i]["Num"] = $i;
            $stdOutput[$i]["Matricule"] = $std->getStudent()->getMatricule();
            $stdOutput[$i]["Nom"] = $std->getStudent()->getNom().' '.$std->getStudent()->getPrenom();
         
           
            foreach($subjects as $sub) $totalWeight += $sub["subjectWeight"];
            foreach($subjects as $sub)
            {
                $subject = $this->entityManager->getRepository(Subject::class)->find($sub["id"]);
                $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"semester"=>$semester,"subject"=>$subject, "student"=>$student ));
                if($stdRegisteredToSubject)
                {
                    $stdOutput[$i][$sub["subjectCode"]]= $stdRegisteredToSubject->getNoteFinal();
                    $markCC += $stdRegisteredToSubject->getNoteCc()*$sub["subjectWeight"];
                    $markCCTP += $stdRegisteredToSubject->getNoteCctp()*$sub["subjectWeight"];
                    $markEXAMTP += $stdRegisteredToSubject->getNoteExamtp()*$sub["subjectWeight"];
                    $markEXAM += $stdRegisteredToSubject->getNoteExam()*$sub["subjectWeight"];
                    
                    $mark += $stdRegisteredToSubject->getNoteFinal()*$sub["subjectWeight"];
                    $weight += $sub["subjectWeight"];
                }else  $stdOutput[$i][$sub["subjectCode"]]= NULL;
            }
            if($totalWeight <> 0)
            {
                $mark /= $totalWeight; $markCCTP /=$totalWeight; $markEXAMTP /=$totalWeight; $markEXAM /= $totalWeight; $markCC /= $totalWeight; 
            }
            
            $std->setNoteCctp(round($markCCTP,2,PHP_ROUND_HALF_UP));
            $std->setNoteExamtp(round($markEXAMTP,2,PHP_ROUND_HALF_UP));
            $std->setNoteCc(round($markCC,2,PHP_ROUND_HALF_UP));
            $std->setNoteExam(round($markEXAM,2,PHP_ROUND_HALF_UP));           
            $std->setNoteFinal(round($mark,2, PHP_ROUND_HALF_UP));
            $std->setGrade($this->computeGradeSur100($classe, $mark));
            $std->setPoints($this->computePointsSur100($classe, $mark));
            $stdOutput[$i]["Note"] = round($mark,2, PHP_ROUND_HALF_UP);
            $stdOutput[$i]["Grade"] = $this->computeGradeSur100($classe, $mark);
            $stdOutput[$i]["Points"] = $this->computePointsSur100($classe, $mark);
            
            $i++;
        }
        $this->entityManager->flush();
        $this->entityManager->commit();
        
       //$std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$ue->getId(),"idSubject"=>$subject->getId()),array("nom"=>"ASC"));
        
        return $stdOutput;

    }    
        
    //this function checks whether or not user as access to information of a gigen class
    //it takes as parameter current logged in user, classe
    //the third parameter is for determinig if the user is admin or not
    public function checkUserCanAccessClass($user,$classe,$isAdmin)
    {
        //check if the user has admin privilege    
        if($isAdmin)
            return true;
        //Find clases mananged by the current user
        $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
        $userClass = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findOneBy(Array("user"=>$user,"classOfStudy"=>$classe));
        if($userClass)
        {
            //Check whether or not current user has the right to access data for the given caclasse
           // $v = $userClass->getClassOfStudy()->getId();
            //return current(array_filter($userClass, function($e) use($v) { return $e->getId()==$v; }));  
            return true;
        }
        
        return false;
    }
    
    public function getCodeFiliere($classe)
    {
        $degree = $classe->getDegree();
        $filiere = $degree->getFieldStudy();
        
        return $filiere->getCode();
        
    }
    
   //returns all subjects related to a given course 
   public function getSubjectFromUe($ueID,$semID,$classeID,$acadYrId)
   {
        $query = $this->entityManager->createQuery('SELECT  s.id,c.id coshs ,s.subjectName,s.subjectCode,c1.code as classCode,c.subjectWeight,'
        . 'c.subjectHours,c.subjectCmHours,c.subjectTdHours,c.subjectTpHours ,c.moduleConsolidationStatus FROM Application\Entity\ClassOfStudyHasSemester c '
        . 'JOIN c.classOfStudy c1 '
        . 'JOIN c.semester sem '
        . 'JOIN sem.academicYear acad '
        . 'JOIN c.subject s '
        . 'WHERE s.teachingUnit = ?1'
        . 'AND  sem.id = ?2 '
        . 'AND  c1.id = ?3' 
        . 'AND  acad.id = ?4'       
        );
        $query->setParameter(1, $ueID)
                ->setParameter(2,$semID)
                ->setParameter(3,$classeID)
        ->setParameter(4,$acadYrId);
        $ue = $query->getResult();
      // var_dump($subjects); exit;
       //if(!$subjects) return $array;
      /* foreach($subjects as $sub)
       {
           if($sub)
           {
            $array[$i] = $sub->getSubject();
            $i++;
           }
           
       }*/
       
       return $ue;
   }
   
   //return  an array of exam perform for a given cours
   public function getExamList($ueID,$subjectID,$semID,$classeID,$acadYrId)
   {
                   
        $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($classeID);
        
        if($subjectID == -1)
            $subject = $this->getSubjectFromUe($ueID,$semID,$classeID,$acadYrId);
        else 
        {
            $ueID = null;
            $subject = array(["id"=>$subjectID]);
        }
            $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"acadYrId"=>$acadYrId,"status"=>1),array('type'=>'ASC'));
            foreach($ueExams as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $ueExams[$key] = $data;
            }

            $exams = $ueExams;
            $myArr = [];
            $i=0;
           foreach ($subject as $sub)
            {
               
                $subjectExam = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(array("subjectId"=>$sub["id"],"acadYrId"=>$acadYrId,"status"=>1),array("type"=>"ASC"));
                
                foreach($subjectExam as $sub1)
                {
                    $hydrator = new ReflectionHydrator();
                    $subjectExam = $hydrator->extract($sub1); 
                    $myArr[$i] = $subjectExam;
                    //$exams = array_merge($exams , $subjectExam );
                    $i++;
                }
            } 
            $exams = array_merge($exams , $myArr );

            
            return $exams;       
   }
   public function getModuleExamStatus($ueID,$semID,$classeID,$acadYrId)
   {
                  
         

            return $this->getSubjectFromUe($ueID, $semID, $classeID,$acadYrId);       
   }   
   
//return  an array of exam performed having mark registered
   public function getExamWithMarkRegistered($ueID,$semID,$classeID,$acadYrId)
   {
                  
        $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($classeID);
        $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"isMarkRegistered"=>1,"acadYrId"=>$acadYrId,"status"=>1),array('type'=>'ASC'));
        $subject = $this->getSubjectFromUe($ueID,$semID,$classeID,$acadYrId);

            foreach($ueExams as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $ueExams[$key] = $data;
            }
            
            $exams = $ueExams;
            $myArr = [];
            $i=0;
            foreach ($subject as $sub)
            {
                $subjectExam = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$sub["id"],"isMarkRegistered"=>1,"status"=>1),array("type"=>"ASC"));
                
                foreach($subjectExam as $sub1)
                {
                    $hydrator = new ReflectionHydrator();
                    $subjectExam = $hydrator->extract($sub1); 
                    $myArr[$i] = $subjectExam;
                    //$exams = array_merge($exams , $subjectExam );
                    $i++;
                }
            } 
            $exams = array_merge($exams , $myArr );

            
            return $exams;       
   }

   public function sendSMS($phoneNumber,$msge)
   {
            $data["msgeStatus"]=0;
            $msgeStatus = 0;
            $config = array(
                'adapter'      => 'Laminas\Http\Client\Adapter\Socket',
                'ssltransport' => 'tls',
                'sslcapath' => $_SERVER["DOCUMENT_ROOT"].'data\ssl\certs',
                'sslcafile'=>  $_SERVER["DOCUMENT_ROOT"].'data\ssl\certs',
                'sslverifypeer' => false,
            );
            //check firs if internet connextion is active or not
            $host_name = 'www.google.com';
            $port_no = '80';

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
    public function sendWeb2sms237API($phoneNumber,$message)
    { 
        $phoneNumber = "+237".$phoneNumber;
        
        W2SClient::setConfig([
            'api_user_id' => '52288231-4c16-49bc-9e7e-7c707a767d66',
            'api_key' => 'f89d858aff4736751e8a724e1dd2ab0f',
        ]);

        $telephone = $phoneNumber;
        $text = $message;
        $senderID = 'WTECH';

        try {
            $response = W2SClient::sendSMS($telephone, $text, $senderID);
            //var_dump($response);;
        } catch (Exception $e) {
            echo 'Exception when calling W2SClient::sendSMS( ', $e->getMessage(), PHP_EOL;
        }
    }  
    
   public function sendAvylTextSMS($phoneNumber,$msge)
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


   private function computeGradeSur100($classe,$moyenne)
   {
       //$grade = $this->entityManager->getRepository(Grade::class)->findByClassOfStudy($classe);
       $gradevalues = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
       
       foreach ($gradevalues as $gv)
       {
           $min = $gv->getMinsur100();
           $max = $gv->getMaxsur100();
           $grade = $gv->getGradeValue();
           if ($min <= $moyenne && $moyenne <= $max)
               return $grade;
           
       }
       
       
   }
   private function computePointsSur100($classe,$moyenne)
   {
      // $grade = $this->entityManager->getRepository(Grade::class)->findByClassOfStudy($classe);
       $gradevalues = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
      
       foreach ($gradevalues as $gv)
       {
           $min = $gv->getMinsur100();
           $max = $gv->getMaxsur100();
           $points = $gv->getGradePoints();
           if ($min <= $moyenne && $moyenne <= $max)
               return $points;
       }
   }   
   
   }
                   