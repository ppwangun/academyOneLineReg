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
use Application\Entity\UnitRegistration;
use Application\Entity\Subject;
use Application\Entity\Exam;
use Application\Entity\ExamType;
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\CurrentYearOnlyUeExamsView;
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\Grade;
use Application\Entity\GradeValueRange;
use Application\Entity\CalculationRule;
use Application\Entity\CalculationRulesWeight;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\AllYearsSubjectRegistrationView;
use Application\Entity\UnitReportPerExamType;
use Application\Entity\UnitReportPerSession;
use Application\Entity\ExamSession;
use Application\MyRepository\SubjectRepository;


class CalculNotesController extends AbstractRestfulController
{
    private $entityManager;
    private $examManager;
    private $sessionContainer;
    private $crtAcadYr;
    private $subjectRepository;
    
    public function __construct($entityManager,$examManager,$sessionContainer,$subjectRepository) {
        
        $this->entityManager = $entityManager;  
        $this->examManager = $examManager; 
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
        $this->subjectRepository = $subjectRepository;
    }

    public function get($id)
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $data = json_decode($id,true);
            
            if(isset($data["isModular"]))
                $exams = $this->examManager->getModuleExamStatus($data["ue_id"],$data["sem_id"],$data["classe_id"],$this->crtAcadYr->getId());
            else    $exams = $this->examManager->getExamList($data["ue_id"],$data["subject_id"],$data["sem_id"],$data["classe_id"],$this->crtAcadYr->getId());
            
                


            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    $exams
            ]);
            
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findAll();
            $subjectExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findAll();
            
            $ueExams = array_merge($ueExams , $subjectExams );
            $i= 0;
            foreach($ueExams as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $ueExams[$key] = $data;
            }

            $this->entityManager->getConnection()->commit();

            $output = new JsonModel([
                    $ueExams
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function create($data)
    {
        try
        { 
            //$this->entityManager->getConnection()->beginTransaction();
            //Retrieve all exams performed for the geiving course
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->find($data["class_id"]);
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["sem_id"]);
            $examSession = $this->entityManager->getRepository(ExamSession::class)->find($data["session_id"]);
            $ue = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue_id"]);
            

            if(isset($data["isMarkAggregation"])&&!isset($data["subject_id"]))
            {
              
                $stdMarks = $this->examManager->markAggregation($ue,$classe,$semester,$examSession,$this->crtAcadYr->getId());
                /*foreach($stdMarks as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $stdMarks[$key] = $data;

                }*/
                //Sorting the $std array according to the key "nom"
       
                $tmp = Array();
                foreach($stdMarks as &$ma)
                    $tmp[] = &$ma["Nom"];
                array_multisort($tmp, $stdMarks);                
                return new JsonModel([
                       $stdMarks
                ]);

               
            }

            if(!isset($data["subject_id"]))
            {
                $subject = [null," "]; 
                $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(array("sessionId"=>$data["session_id"],"subjectId"=>$data["ue_id"],"classe"=>$classe->getCode(),"status"=>1,"acadYrId"=>$this->crtAcadYr->getId()));
                $subjects = $this->examManager->getSubjectFromUe($data["ue_id"],$data["sem_id"],$data["class_id"],$this->crtAcadYr);
                $subjectExams = $this->getSubjectExams($subjects);
                $ueExams = array_merge($ueExams , $subjectExams );
                $subject =null;
                
            }
            else 
            {
                $subject = $this->entityManager->getRepository(Subject::class)->findOneById($data["subject_id"]);
                $ueExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$data["subject_id"],"classe"=>$classe->getCode(),"acadYrId"=>$this->crtAcadYr->getId(),"status"=>1));
            }
          
            $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("teachingUnit"=>$ue,"classOfStudy"=>$classe,"status"=>1,"semester"=>$semester));

            $subjectId = $data["subject_id"]??null;
                $evaluations= $this->entityManager->createQueryBuilder();
                $exp = $evaluations->expr();
                $evaluations = $evaluations->select( 'er','std','e','es','cosh','ue')
                    ->from('Application\Entity\ExamRegistration','er')
                    ->leftjoin('er.student','std')
                    ->leftjoin('er.exam','e')
                    ->leftjoin('e.examSession','es')
                   ->leftjoin('e.classOfStudyHasSemester','cosh')
                    ->leftjoin('cosh.teachingUnit','ue')
                    ->where('cosh.classOfStudy = :classId')
                    ->andWhere('cosh.semester = :semId')
                    ->andWhere('cosh.teachingUnit = :ueId')
                    ->andWhere($exp->orX(
                        $exp->eq('e.examSession', ':sessionId'),
                        $exp->isNull('e.examSession')
                        )
                    )
                    ->andWhere('e.status = 1')
                    ->setParameter('classId', $data["class_id"])    
                    ->setParameter('semId', $data["sem_id"])
                    ->setParameter('ueId', $data["ue_id"])
                    ->setParameter('sessionId', $data["session_id"])
                    ->getQuery()
            ->getArrayResult();    
            if($subjectId)
            {  
                $evaluations= $this->entityManager->createQueryBuilder();
                $exp = $evaluations->expr();
                $evaluations = $evaluations->select( 'er','std','e','es','cosh','ue')
                    ->from('Application\Entity\ExamRegistration','er')
                    ->leftjoin('er.student','std')
                    ->leftjoin('er.exam','e')
                    ->leftjoin('e.examSession','es')
                   ->leftjoin('e.classOfStudyHasSemester','cosh')
                    ->leftjoin('cosh.subject','ue')
                    ->where('cosh.classOfStudy = :classId')
                    ->andWhere('cosh.semester = :semId')
                    ->andWhere('cosh.subject = :subjectId')
                    ->andwhere($exp->orX(
                        $exp->eq('e.examSession', ':sessionId'),
                        $exp->isNull('e.examSession')
                        )
                    )
                    ->andWhere('e.status = 1')
                   // ->andWhere('cosh.subject = :subjectId')
                    //->setParameter('role', $role)
                    ->setParameter('classId', $data["class_id"])    
                    ->setParameter('semId', $data["sem_id"])
                    ->setParameter('subjectId', $subjectId)
                    ->setParameter('sessionId', $data["session_id"])  
                    ->getQuery()
                    ->getArrayResult();                        
            }
            
            //check first if all the mark are register
            //throw an errow if there is even a single exam that the mark is not yet registered
    /*        if(!$this->checkAllMarksAreRegistered($ueExams))
                return new JsonModel([ "ERROR_NO_CC_OR_EXAM_DONE"  ]);            
    */        
            $examtypes = array_map(fn($e) => $e['exam']['type'], $evaluations);
            $examtypes = array_unique($examtypes);
            sort($examtypes);
            
           
            $students = $this->entityManager->createQueryBuilder()->select('ur','std')
            ->from('Application\Entity\UnitRegistration','ur')
            ->leftjoin('ur.student','std')
            ->leftjoin('ur.teachingUnit','ue')
            ->leftjoin('ur.semester','sem')
           // ->leftjoin('ur.examSession','session')
            ->where('ur.teachingUnit = :ue')
            
            ->andwhere($exp->orX(
                $exp->isNull('ur.subject')
                ) )                  
            ->andwhere('ur.semester = :semester')
            //->andwhere('ur.examSession = :session')
            ->setParameter('ue',$data["ue_id"])
            //->setParameter('session',$data["session_id"])
            ->setParameter('semester',$data["sem_id"])
            ->getQuery()
        ->getArrayResult();
            
            if(!is_null($subjectId))
            {
                $students = $this->entityManager->createQueryBuilder()->select('ur','std')
                ->from('Application\Entity\UnitRegistration','ur')
                ->leftjoin('ur.student','std')
                ->leftjoin('ur.teachingUnit','ue')
                ->leftjoin('ur.subject','sub')
                ->leftjoin('ur.semester','sem')
               // ->leftjoin('ur.examSession','session')
                //->where('ur.teachingUnit = :ue')
                ->andwhere('ur.subject = :subject')
                ->andwhere('ur.semester = :semester')
                //->andwhere('ur.examSession = :session')
                //->setParameter('ue',$data["ue_id"])
                ->setParameter('subject',$subjectId)
                ->setParameter('semester',$data["sem_id"])
                //->setParameter('session',$data["session_id"])
                ->getQuery()
                ->getArrayResult();                
            }
            
            $report = $this->calculNote($evaluations,$students,$examSession,$classe,$ue,$subject,$coshs,$semester);
            
            if($report == "ERROR_NO_RULE_DEFINE") return new JsonModel([ "ERROR_NO_RULE_DEFINE" ]);
            if($report == "ERROR_PED_REGISTRATION" ) return new JsonModel([ "ERROR_PED_REGISTRATION"  ]);

            



            array_multisort(array_column($report, 'Nom'), SORT_ASC, $report);
            
           
            $output = new JsonModel([
                   $report
            ]);
            
            return $output;
         
        } 
        catch (Exception $ex) {
            
            $this->entityManager->getConnection()->rollBack();
            throw $ex;

        }
    }
    

   //This function takes as parameter a list of exams performed for a given course
   //the fonction checks if all marks are registered 
   //return true in case all mark are registered and 0 otherwise
   private function checkAllMarksAreRegistered($exams)
   { 
        foreach($exams as $exam)
        {
            if($exam->getIsMarkRegistered()==0)
              return false;
   
        } 
        return true;
   }  
   //This fonction takes as parameters semester, course and  list of exam performed for the given course
   //for each exam types, it calculates it calculates the mean value of marks for each student and report it to course registration table (unit_registration)
   private function checkStudentInExam($student,$evaluation)
   { 
       
        $evaluation = array_map(fn($e)=>$e["matricule"],$evaluation);  
        
        if (in_array($student,$evaluation)) return true ;

        return false;
  
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
   private function computeGrade($classe,$moyenne)
   {
       //$grade = $this->entityManager->getRepository(Grade::class)->findByClassOfStudy($classe);
       $gradevalues = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
       
       foreach ($gradevalues as $gv)
       {
           $min = $gv->getMinsur20();
           $max = $gv->getMaxsur20();
           $grade = $gv->getGradeValue();
           if ($min <= $moyenne && $moyenne <= $max)
               return $grade;
           
       }
       
       
   }  
   private function resultStatus($classe,$moyenne)
   {
       //$grade = $this->entityManager->getRepository(Grade::class)->findByClassOfStudy($classe);
       $gradevalues = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
       
       foreach ($gradevalues as $gv)
       {
           $min = $gv->getMinsur100();
           $max = $gv->getMaxsur100();
           $resultStatus = $gv->getResultStatus();
           if ($min <= $moyenne && $moyenne <= $max)
               return $resultStatus;
           
       }
   }
   private function computePoints($classe,$moyenne)
   {
      // $grade = $this->entityManager->getRepository(Grade::class)->findByClassOfStudy($classe);
       $gradevalues = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
      
       foreach ($gradevalues as $gv)
       {
           $min = $gv->getMinsur20();
           $max = $gv->getMaxsur20();
           $points = $gv->getGradePoints();
           if ($min <= $moyenne && $moyenne <= $max)
               return $points;
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
   
   //This function checks if the mark is registered, validated or confirmed
   //in case mark is confirm, and not validated neither confirmed it return registered mark
   //incase mark is validated it returns validated marks
   //in case mark is confirmed it returns confirmed mark
   //the function takes as parameters the exam and the exam registration
   private function getMark($exam,$examR)
   {
       if($exam->getIsMarkRegistered()==1 && $exam->getIsMarkValidated()==0 && $exam->getIsMarkConfirmed()==0)
           return $examR->getRegisteredMark();
       if($exam->getIsMarkValidated()==1 && $exam->getIsMarkConfirmed()==0 )
           return $examR->getValidatedMark();
       if($exam->getIsMarkConfirmed()==1)
           return $examR->getConfirmedMark();       
   }
   
   //This function takes as paramter an array of subjects
   //and return an array of exam performed for each subjects
   private function getSubjectExams($subjects)
   {
        $myArr = [];
       
        foreach ($subjects as $sub)
        {
            $subjectExam = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$sub["id"],"acadYrId"=>$this->crtAcadYr->getid(),"status"=>1));
            $myArr = array_merge($myArr,$subjectExam);
      
         
        } 
       
        return $myArr;
   }
   private function getSubjectRat($subject)
   {
       // $myArr = [];
       
       // foreach ($subjects as $sub)
       // {
            $subjectExam = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$subject["id"],"type"=>"RAT","acadYrId"=>$this->crtAcadYr->getId(),"status"=>1));
           // $myArr = array_merge($myArr,$subjectExam);
      
         
        //} 
       
        //return $myArr;
            return $subjectExam;
   }   
   
   private function computeRattrapeMark($ueID,$semID,$classeID)
   {
     
        $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($classeID);
        $ueExams = $this->entityManager->getRepository(CurrentYearOnlyUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"type"=>["EXAM","EXAMC","STAE"],"acadYrId"=>$this->crtAcadYr->getId(),"status"=>1));
        $ueRat = $this->entityManager->getRepository(CurrentYearOnlyUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"type"=>"RAT","acadYrId"=>$this->crtAcadYr->getId(),"status"=>1));

        $subjects = $this->examManager->getSubjectFromUe($ueID,$semID,$classeID,$this->crtAcadYr);
                    
        $ueExamsRatMark = $this->mergeUeRatMark($ueRat);
            
            foreach($ueExams as $ueExam)
            {
 
                //If catchup exam is not for move to course
                        
                $exam = $this->entityManager->getRepository(Exam::class)->find($ueExam->getId());
               // if($ueExam->getIsCatchupExamPerformed()==1) 
               //     continue;
                $stdRegisteredToSubject = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam);
              
                   // break;

                foreach($stdRegisteredToSubject as $std)
                { 
                    //we are taking only the students that are present in the catch up exam session

                        
                    foreach($ueExamsRatMark as $key=>$value )
                    {
                        if($std->getStudent()->getId()==$key)
                        {
                            if($exam->getIsMarkConfirmed()==1)
                                $std->setConfirmedMark($value);
                            if($exam->getIsMarkValidated()==1)
                                $std->setValidatedMark($value);
                            if($exam->getIsMarkRegistered()==1)
                                $std->setRegisteredMark($value);
                            
                            $std->setIsMarkFromCatchUpExam(1);
                        }
                    }
                    $this->entityManager->flush();
          

                }
            }   
                if(!empty($subjects))
                {
                    foreach($subjects as $subject)
                    {                       

                        
                        $subjectExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$subject["id"],"classe"=>$classe->getCode(),"type"=>["EXAM","STAC","STAE"],"acadYrId"=>$this->crtAcadYr->getId(),"status"=>1));
                        
                        foreach($subjectExams as $ueExam)
                        {
                            $subjectRat = $this->getSubjectRat($subject); 
                            $ueExamsRatMark = $this->mergeUeRatMark($subjectRat);
                            //If catchup exam is not for move to course

                            $ueExam = $this->entityManager->getRepository(Exam::class)->find($ueExam->getId());
                           // if($ueExam->getIsCatchupExamPerformed()==1) 
                           //     continue;
                            $stdRegisteredToSubject = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($ueExam);
                            $exam = $ueExam;
                               // break;

                            foreach($stdRegisteredToSubject as $std)
                            { 
                                //we are taking only the students that are present in the catch up exam session
                                foreach($ueExamsRatMark as $key=>$value)
                                {
                                    if($std->getStudent()->getId()==$key)
                                    {   
                                        if($exam->getIsMarkConfirmed()==1)
                                            $std->setConfirmedMark($value);
                                        elseif($exam->getIsMarkValidated()==1)
                                            $std->setValidatedMark($value);
                                        elseif($exam->getIsMarkRegistered()==1)
                                            $std->setRegisteredMark($value);
                                        
                                        $std->setIsMarkFromCatchUpExam(1);
                                    }
                                }


                            }
                            $this->entityManager->flush();
                    }
                }
            }
                //$ueExam->setIsCatchUpExamPerformed(1);
               // $this->entityManager->flush();
                
            
            
            
        }
        
    //Reset all the catchup exams to 0
   /* $sql = "UPDATE Exam SET is_catch_up_exam_performed = 0   ";
    $stmt = $this->entityManager->getConnection()->prepare($sql);
    $stmt->execute();*/
        
                    
 //  }
   
    private function computeMention($mpc)
    {
        $profilAcademic = $this->entityManager->getRepository(ProfileAcademic::class)->findAll();
        foreach($profilAcademic as $prof)
        {
            if($mpc>=$prof->getMinval()&& $mpc<=$prof->getMaxval()) return $prof->getGrade();
        }
    }
    
    private function mergeUeRatMark($ueRat)
    {
        $cpt=0;
        $noteRat = [];
        //initializing $noteRat table
        foreach ($ueRat as $rat)
        {
            $rat = $this->entityManager->getRepository(Exam::class)->findOneBy(array("code"=>$rat->getCode(),"type"=>"RAT","status"=>1));
            $ratRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($rat);        
            foreach($ratRegistration as $ratR)
            {
                if (($ratR->getAttendance()=="P"))
                {
                    $noteRat[$ratR->getStudent()->getId()] = 0;                    
                }
            }
                        
        }
        
        foreach($ueRat as $rat)
        {
            $cpt++;
            $note = null;
            $rat = $this->entityManager->getRepository(Exam::class)->findOneBy(array("code"=>$rat->getCode(),"type"=>"RAT","status"=>1));
            $ratRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($rat);        
            foreach($ratRegistration as $ratR)
            { 
                if (($ratR->getAttendance()=="P"))
                {
                                          
                        if(($rat->getIsMarkConfirmed()==1))
                            $note = $ratR->getConfirmedMark();
                        elseif(($rat->getIsMarkValidated()==1))
                            $note=$ratR->getValidatedMark();
                        elseif(($rat->getIsMarkRegistered()==1))
                            $note = $ratR->getRegisteredMark();
                        
                    $noteRat[$ratR->getStudent()->getId()]+=$note;
                    //$noteRat[$ratR->getStudent()->getId()]= $noteRat[$ratR->getStudent()->getId()]/$cpt;
                }
            }
        }
        foreach ($noteRat as $key=>$value)
        {
            $noteRat[$key]=round($value/$cpt,2,PHP_ROUND_HALF_UP);
            
                        
        }        
        return $noteRat;
    }
    

   private function setStdtMarkSumary($classe,$ue,$subject,$semester){
       
        
        $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
        foreach($stdRegisteredToSubject as $std)
        {

            $note = round(($std->getNoteExam()),2, PHP_ROUND_HALF_UP);
            $std->setNoteFinal($note);
            $std->setGrade($this->computeGradeSur100($classe, $note));
            $std->setPoints($this->computePointsSur100($classe, $note));
            $std->setNoteCctp(NULL);
            $std->setNoteExamtp(NULL);
            $std->setNoteCc(NULL);
            $std->setNoteExam(NULL);
            $this->entityManager->flush();

        } 
        return;
   } 
   
   private function setStdMark($ue,$subject,$sem,$key,$value,$count,$evalType)
   {
        //Collect all student registered to the given unit related exam
        $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
        $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$key ));
        
//if(!is_null($value)) $std->setNoteExam(round($value/$countTHESE,2,PHP_ROUND_HALF_UP));
        switch($evalType)
        {
            case "EXAM":if((isset($value )&& $count>0 )) $std->setNoteExam(round($value/$count,2,PHP_ROUND_HALF_UP));  BREAK;
            case "CC":if((isset($value )&& $count>0 )) $std->setNoteCC(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "CCTP":if((isset($value )&& $count>0 )) $std->setNoteCctp(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "EXAMTP":if((isset($value )&& $count>0 )) $std->setNoteExamtp(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "EXAMC":if((isset($value )&& $count>0 )) $std->setNoteExamc(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "STAGEE":if((isset($value )&& $count>0 )) $std->setNoteStagee(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "STAGEC":if((isset($value )&& $count>0 )) $std->setNoteStagec(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "ECN":if((isset($value )&& $count>0 )) $std->setNoteECN(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
            case "THESE":if((isset($value )&& $count>0 )) $std->setNoteThese(round($value/$count,2,PHP_ROUND_HALF_UP));BREAK;
        
        }
        
        return;
   }
   
   private function calculNote($evaluations,$students,$examSession,$classe,$ue,$subject,$coshs,$semester)
   {
       $this->entityManager->getConnection()->beginTransaction();
       $report = [];
            $students= array_map(fn($e)=>$e['student'],$students);
            $students= array_values(array_column($students, null, 'matricule')); 
     
            if($examSession->getSessionType()=='RAT')
                $studentsInExam = array_filter($evaluations,fn($e)=>$e['exam']['type']!= "CC");
            else $studentsInExam = $evaluations;
    
            $studentsInExam = array_map(fn($e)=>$e['student'],$studentsInExam);
            $studentsInExam = array_values(array_column($studentsInExam, null, 'matricule')); 
         
            //check student that are registered to evaluation are registered to subject
            foreach($studentsInExam  as $std):
                //$this->checkStudentInExam ($std, $students);
                
                if(!$this->checkStudentInExam ($std['matricule'], $students)) return "ERROR_PED_REGISTRATION"; 
            endforeach;
             
            $sequence = 0;
    
            foreach($studentsInExam as $key=>$value)
            {
                $stdEvals = array_filter($evaluations,fn($e)=>$e['student']['id']===$value['id']); 
                $studentId = $value['id'];
                
                $grouped = [];
                foreach($stdEvals as $eval)
                {
                    $exam = $this->entityManager->getRepository(Exam::class)->find($eval['exam']['id']);
                    $examR = $this->entityManager->getRepository(ExamRegistration::class)->find($eval['id']);
                    $grouped[$eval['exam']['type']][] = $this->getMark($exam, $examR);
                } 
                $std = $this->entityManager->getRepository(Student::class)->find($studentId);
                $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester,"student"=>$std ));
               
                $sessionReport = $this->entityManager->getRepository(UnitReportPerSession::class)->findOneBy(array("unitRegistration"=>$stdRegisteredToSubject,"examSession"=>$examSession));
                $flag = 0;
                if(is_null($sessionReport))
                {
                    $flag = 1;
                    $sessionReport = new UnitReportPerSession();
                }  
                    $sessionReport->setUnitRegistration($stdRegisteredToSubject);
                    $sessionReport->setExamSession($examSession);
                    
                
                if($flag)
                    $this->entityManager->persist($sessionReport);                

                // Average per type
                $typeAverages = [];
                foreach ($grouped as $type => $values) {
                    $typeAverages[$type] = round(array_sum($values) / count($values),2);
                    $examT = $this->entityManager->getRepository(ExamType::class)->findOneByCode($type);
                    $stdReporPerExamType = $this->entityManager->getRepository(UnitReportPerExamType::class)->findOneBy(array("unitReportPerSession"=>$sessionReport,"examType"=>$examT));
                    $flag = 0;
                    if(is_null($stdReporPerExamType))
                    {
                        $flag = 1;
                        $stdReporPerExamType = new UnitReportPerExamType();
                    }
                    
                    $stdReporPerExamType->setNote(round(array_sum($values) / count($values),2));
                   
                    $stdReporPerExamType->setExamType($examT);                    
                    $stdReporPerExamType->setTeachingUnit($ue);                   
                    $stdReporPerExamType->setSubject($subject);

                    $stdReporPerExamType->setUnitReportPerSession($sessionReport);
                    
                    if($flag)
                        $this->entityManager->persist($stdReporPerExamType);
                    
                }  
                
                // Detect combination
                $combination = array_keys($typeAverages);
                sort($combination);
                $key = implode('+', $combination);
               
                // Find rule
                //if rule is not difine directly to the subject, find the default rule;
                $weightRules = [];
                $rule = $this->entityManager->getRepository(CalculationRule::class)->findOneBy(array("classOfStudyHasSemester"=>$coshs,'combination'=>$key));
                if(!$rule)
                    $rule = $this->entityManager->getRepository(CalculationRule::class)->findOneBy(array("isDefault"=>1,'combination'=>$key));
                
                if($rule)
                    $weightRules = $this->entityManager->createQueryBuilder()->select('rw','e')
                        ->from('Application\Entity\CalculationRulesWeight','rw')
                        ->leftjoin('rw.calculationRule','r')
                        ->leftjoin('rw.examType','e')
                        ->where('rw.calculationRule = :ruleId')
                        ->setParameter('ruleId',$rule->getId())
                        ->getQuery()
                        ->getArrayResult();
                else 
                    return "ERROR_NO_RULE_DEFINE" ;

                
                $report[$sequence]["Nom"] = $std->getNom()." ".$std->getPrenom();
                $report[$sequence]["Matricule"] = $std->getMatricule();
                
                $total =0;
                $totalWeight = null;
                $coef =1;
               // var_dump($weightRules); exit;
                foreach($typeAverages as $key=>$value)
                {   
                    $report[$sequence][$key]=$value;
                    foreach($weightRules as $weight)
                        if($weight['examType']['code'] === $key)
                        {   
                            if (in_array($key,["CC","EXAM"])) $coef = 5;
                            
                                $total +=  $value*$weight['ruleweightvalue'];
                                $totalWeight += $weight['ruleweightvalue'];
                        }
                }
                
                
                $note = $totalWeight ? round($total/$totalWeight,2, PHP_ROUND_HALF_UP) : null;
                $note = $note*$coef;
                

                $report[$sequence]["Note Finale"]=['note'=>$note,'isFromDeliberation'=>$stdRegisteredToSubject->getIsFromDeliberation()];
                $report[$sequence]["Grade"]=$this->computeGradeSur100($classe, $note);
                $report[$sequence]["Points"]=$this->computePointsSur100($classe, $note);
                $report[$sequence]["Statut"]=$this->resultStatus($classe, $note);

                $stdRegisteredToSubject->setNoteFinal($note);
                $stdRegisteredToSubject->setGrade($this->computeGradeSur100($classe, $note));
                $stdRegisteredToSubject->setPoints($this->computePointsSur100($classe, $note));
                $stdRegisteredToSubject->setStudentUnitResult($this->resultStatus($classe, $note));
                $stdRegisteredToSubject->setExamSession($examSession);


                $sessionReport->setNote($note);
                $sessionReport->setGrade($this->computeGradeSur100($classe, $note));
                $sessionReport->setPoints($this->computePointsSur100($classe, $note));
                
                $sessionReport->setResultStatus($this->resultStatus($classe, $note));

                
                
                
                
                $sequence ++;
                $this->entityManager->flush();
            }
            $this->entityManager->flush();
            $this->entityManager->commit();
            
            return $report;
            
   }
    
}
