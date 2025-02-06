<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Exam;
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\User;

class ExamController extends AbstractRestfulController
{
    private $entityManager;    
    private $sessionContainer;    
    private $examManager;
    
    public function __construct($entityManager,$sessionContainer,$examManager) {
        
        $this->entityManager = $entityManager;
        $this->sessionContainer= $sessionContainer;
        $this->examManager = $examManager;
    }

    public function get($id)
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            //Current loggedIn User
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
   
            $ueExam = $this->entityManager->getRepository(Exam::class)->find($id);
            $coshs = $ueExam->getClassOfStudyHasSemester();

            //check first wether or not the user has access permission to the given classe
            //if not retur null
            $exam = array("exam_code"=>$ueExam->getCode());
            $isAdmin = $this->access('global.system.admin',['user'=>$user]);
            if($this->examManager->checkUserCanAccessClass($user,$coshs->getClassOfStudy(),$isAdmin))
            {
                $exam= array("classe_id"=>$coshs->getClassOfStudy()->getId(),
                            "exam_code"=>$ueExam->getCode(),
                            "sem_id"=>$coshs->getSemester()->getId(),
                            "exam_type_code"=>$ueExam->getType(),
                            "ue_id"=>$coshs->getTeachingUnit()?$coshs->getTeachingUnit()->getId():$coshs->getSubject()->getTeachingUnit()->getId(),
                            "subject_id"=>$coshs->getSubject()?$coshs->getSubject()->getId():"",
                            "is_registered_mark"=>$ueExam->getIsMarkRegistered(),
                            "is_validated_mark"=>$ueExam->getIsMarkValidated(),
                            "is_confirmed_mark"=>$ueExam->getIsMarkConfirmed(),
                            "is_attendance_saved"=>$ueExam->getIsAttendanceSaved(),
                            "is_anonymat_saved"=>$ueExam->getIsAnonymatSaved());
            }
          
            $this->entityManager->getConnection()->commit();

            return new JsonModel([
                    $exam
            ]);
            //var_dump($output); //exit();
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
 //this fonction display exam manged by logged in user   
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            
            $ueExams=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {
                $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(Array(),Array("date"=>'DESC'));                
            }
            else{
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));

                foreach($userClasses as $classe)
                {
                    $ueExam1s = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(Array("classe"=>$classe->getClassOfStudy()->getCode()),Array("date"=>'DESC'));
                    $ueExams = array_merge($ueExams,$ueExam1s);
                    //$subjectExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(Array("classe"=>$classe->getClassOfStudy()->getCode()),Array("date"=>'DESC'));

                    //$ueExams = array_merge($ueExams , $subjectExams );
                }

            }
            //converting the $ueExams array of objects to array of arrays
                $i= 0;
                foreach($ueExams as $key=>$value)
                {
                    $i++;
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    //$data["date"] = date_format($data["date"], 'd/m/Y H:i:s');
                    $ueExams[$key] = $data;
                }
                //$this->compareClasseCode($ueExams, $ueExams);
                usort($ueExams, function($a, $b)
                    {
                        return strnatcmp($a['id'], $b['id']);
                    }
                );
                $ueExams = array_reverse($ueExams);

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $ueExams
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function create($data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            if($data['typeOperation']=="NEW_EXAM")
            {
                //retrieve , semester, class, subject invovled in the exam 
                //the associative table clas_of_study_has_smester is retrieved and stores  in the exam table
                //the exam cam be base on a teaching unit or a subject associted to a teaching unit
                $subject = null;
                $semester = $this->entityManager->getRepository(Semester::class)->find($data["semester"]);
                $class= $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
                $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue_id"]);
                
               
                
                if (isset($data["subject"]))
                {
                    if($data["subject"])
                    {
                        $subject = $this->entityManager->getRepository(Subject::class)->find($data["subject"]);
                        $classSemSubject = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)
                        ->findOneBy(array("semester"=>$semester,"classOfStudy"=>$class,"subject"=>$subject));
                    }
                
                }
                else {

                    $classSemSubject = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)
                    ->findOneBy(array("semester"=>$semester,"classOfStudy"=>$class,"teachingUnit"=>$teachingUnit));

                }

                //Retrieve the last exam ID
                $lastExamId =  $this->entityManager->getRepository(Exam::class)->findOneBy([], ['id' => 'desc']);
                if(!$lastExamId)
                {
                    $lastExamId = 1;
                }
                else {
                    $lastExamId = $lastExamId->getId();
                    $lastExamId++;                
                }

                //Generate exam code base on: exam type, year, sem, and last id
                $date= new \DateTime($data["date"]);
                $year = $date->format("Y");
                //$code = $data["examtype"]."/".$year."/".$semester->getCode()."/".$class->getCode()."/".str_pad($lastExamId, 3, '0', STR_PAD_LEFT);
                $code = str_pad($lastExamId, 8, '0', STR_PAD_LEFT);
                
                $value = 0;
                //Create new instance of exam
                $examRegistration = new Exam();
                $examRegistration->setDate(new \DateTime($data["date"]));
                $examRegistration->setCode($code );
                $examRegistration->setType($data["examtype"]);
                $examRegistration->setIsAttendanceSaved($value);
                $examRegistration->setIsAnonymatSaved($value);
                $examRegistration->setIsMarkRegistered($value);
                $examRegistration->setIsMarkValidated($value);
                $examRegistration->setIsMarkConfirmed($value);
                $examRegistration->setClassOfStudyHasSemester($classSemSubject);

                $this->entityManager->persist($examRegistration);
                $this->entityManager->flush();
                $examId = $examRegistration->getId();

                //Associate students to the exam
                foreach($data["students"] as $std)
                {
                    $stdExamRegistration = new ExamRegistration();
                    $stdExamRegistration->setExam($examRegistration);
                    $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($std["matricule"]);
                    $stdExamRegistration->setStudent($student);
                    $stdExamRegistration->setAttendance($std["attendance"]);
                    $stdExamRegistration->setNumAnonymat($std["anonymat"]);
                    $stdExamRegistration->setRegisteredMark($std["note"]);
                    $stdExamRegistration->setValidatedMark($std["note"]);
                    $stdExamRegistration->setConfirmedMark($std["note"]);
                    $this->entityManager->persist($stdExamRegistration);
                    $this->entityManager->flush();
                }

                $examDetails = array("code"=>$code,"examId"=>$examId);
                $this->entityManager->getConnection()->commit();


                $output = new JsonModel([
                        $examDetails 
                ]);

                return $output;
            }
             $operation = $data["typeOperation"];
               
               if ($operation=="CANCEL_EXAM")
                {
                    $exam =  $this->entityManager->getRepository(Exam::class)->findOneBy(['code' => $data["code"]]);  
                    $exam->setStatus(0);
                    $this->entityManager->flush();
                    $this->entityManager->getConnection()->commit();
            
                    return new JsonModel([
               // $this->getFaculty($data["school_id"])
                    ]);
                }
            
            
            
            $exam=$this->entityManager->getRepository(Exam::class)->findOneByCode($data['id']);
            
            foreach($data['data'] as $examReg)
            {
                $student=$this->entityManager->getRepository(Student::class)->findOneByMatricule($examReg["matricule"]);
                $examRegistration= $this->entityManager->getRepository(ExamRegistration::class)->findOneBy(array('exam'=>$exam,'student'=>$student));

               // $date = date('Y-m-d H:i:s');
                $value = 1;

                if($operation=="REGISTER_MARK")
                {
                    //$examRegistration->setAttendance($examReg["attendance"]);
                    //$examRegistration->setNumAnonymat($examReg["anonymat"]);
                    
                    $examRegistration->setRegisteredMark($examReg["note"]);
                    $exam->setDateRegistered(new \DateTime(date("Y-m-d H:i:s")));
                    
                    $exam->setIsMarkRegistered($value);
                    
                    
                }
                elseif ($operation=="VALIDATE_MARK") {
                    //During the validation phase, attendance should be update only when the value is A
                    //this for keeping track on which student initially mark as present
                    if($examReg["attendance"]=="A")$examRegistration->setAttendance($examReg["attendance"]);
                    
                    $examRegistration->setValidatedMark($examReg["note"]);
                    $exam->setDateValidated(new \DateTime(date("Y-m-d H:i:s")));
                    $exam->setIsMarkValidated($value);
                }
                elseif ($operation=="CONFIRM_MARK") {
                    $examRegistration->setConfirmedMark($examReg["note"]);
                    $exam->setDateConfirmed(new \DateTime(date("Y-m-d H:i:s")));
                    $exam->setIsMarkConfirmed($value);
                }
                elseif ($operation=="SAVE_ANONYMAT") {
                    $exam->setIsAnonymatSaved($value);
                    $examRegistration->setNumAnonymat($examReg["anonymat"]);

                }
                elseif ($operation=="SAVE_ATTENDANCE") {
                    $exam->setIsAttendanceSaved($value);
                    $examRegistration->setAttendance($examReg["attendance"]);

                }

            }
                


            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);         
        } 
        catch (Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            throw $ex;

        }
    }
    
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
               $operation = $data["typeOperation"];
               
               if ($operation=="CANCEL_EXAM")
                {
                    $exam =  $this->entityManager->getRepository(Exam::class)->findOneBy(['code' => $data["code"]]);  
                    $exam->setStatus(0);
                    $this->entityManager->flush();
                    $this->entityManager->getConnection()->commit();
            
                    return new JsonModel([
               // $this->getFaculty($data["school_id"])
                    ]);
                }
            
            
            $data1 = $data["data"];
            $exam=$this->entityManager->getRepository(Exam::class)->findOneByCode($id);
            
            foreach($data1 as $examReg)
            {
                $student=$this->entityManager->getRepository(Student::class)->findOneByMatricule($examReg["matricule"]);
                $examRegistration= $this->entityManager->getRepository(ExamRegistration::class)->findOneBy(array('exam'=>$exam,'student'=>$student));

               // $date = date('Y-m-d H:i:s');
                $value = 1;

                if($operation=="REGISTER_MARK")
                {
                    //$examRegistration->setAttendance($examReg["attendance"]);
                    //$examRegistration->setNumAnonymat($examReg["anonymat"]);
                    
                    $examRegistration->setRegisteredMark($examReg["note"]);
                    $exam->setDateRegistered(new \DateTime(date("Y-m-d H:i:s")));
                    
                    $exam->setIsMarkRegistered($value);
                    
                    
                }
                elseif ($operation=="VALIDATE_MARK") {
                    //During the validation phase, attendance should be update only when the value is A
                    //this for keeping track on which student initially mark as present
                    if($examReg["attendance"]=="A")
                        $examRegistration->setAttendance($examReg["attendance"]);
                    
                    $examRegistration->setValidatedMark($examReg["note"]);
                    $exam->setDateValidated(new \DateTime(date("Y-m-d H:i:s")));
                    $exam->setIsMarkValidated($value);
                }
                elseif ($operation=="CONFIRM_MARK") {
                    $examRegistration->setConfirmedMark($examReg["note"]);
                    $exam->setDateConfirmed(new \DateTime(date("Y-m-d H:i:s")));
                    $exam->setIsMarkConfirmed($value);
                }
                elseif ($operation=="SAVE_ANONYMAT") {
                    $exam->setIsAnonymatSaved($value);
                    $examRegistration->setNumAnonymat($examReg["anonymat"]);

                }
                elseif ($operation=="SAVE_ATTENDANCE") {
                    $exam->setIsAttendanceSaved($value);
                    $examRegistration->setAttendance($examReg["attendance"]);

                }

            }
                


            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);

        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }



    
}   
    

