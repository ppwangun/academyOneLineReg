<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
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
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\UnitRegistration;


class ParcourtController extends AbstractActionController
{
    private $entityManager;
    
    public function __construct($entityManager,$examManager) {
        
        $this->entityManager = $entityManager;
        $this->examManager = $examManager;
    }

    public function getparcourtbyclassAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {    
            $data = $this->params()->fromQuery();
            
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["id"]);
           
            
            $parcourt = $this->examManager->showStudentPathByClasse($classe);
           
            $this->entityManager->getConnection()->commit();

            $output = new JsonModel([
                    $parcourt
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function afficheruenonvalidesAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            $data = $this->params()->fromQuery();
            $std= $this->entityManager->getRepository(Student::class)->findOneBy(Array("matricule"=>$data["std_mat"]));
            $subject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"grade"=>"F"));
            $i = 0;
            $sub = [];
            foreach($subject  as $key=>$value)
            {
   
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $sub[$i]["id"] = $value->getTeachingUnit()->getId();
                $sub[$i]["code"] = $value->getTeachingUnit()->getCode();
                $sub[$i]["nom"] = $value->getTeachingUnit()->getName();
                $i++;
            }
           /* $output['draw']=1;
            $output['recordsTotal']=sizeof($registeredStd);
            $output['recordsFiltered']=sizeof($registeredStd);
            $output['data'] = $registeredStd;*/
            //$output = json_encode($registeredStd,$depth=10000000);
            //$output = array_slice($registeredStd,0,5);
            //$output = $registeredStd;
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $sub
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
            //retrieve , semester, class, subject invovled in the exam 
            //the associative table clas_of_study_has_smester is retrieved and stores  in the exam table
            //the exam cam be base on a teaching unit or a subject associted to a teaching unit
            $subject = null;
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["semester"]);
            $class= $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
            $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue_id"]);
            if(isset($data["subject"]))
            {
                $subject = $this->entityManager->getRepository(Subject::class)->find($data["subject"]);
                $classSemSubject = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)
                ->findOneBy(array("semester"=>$semester,"classOfStudy"=>$class,"subject"=>$subject));
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
            $code = $data["examtype"]."/".$year."/".$semester->getCode()."/".$class->getCode()."/".str_pad($lastExamId, 3, '0', STR_PAD_LEFT);
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
        catch (Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

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
            
            
            $data = $data["data"];
            $exam=$this->entityManager->getRepository(Exam::class)->findOneByCode($id);
            
            foreach($data as $examReg)
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
