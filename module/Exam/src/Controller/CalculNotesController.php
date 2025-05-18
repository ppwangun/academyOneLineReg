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
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\CurrentYearOnlyUeExamsView;
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\Grade;
use Application\Entity\GradeValueRange;
use Application\Entity\SubjectRegistrationView;


class CalculNotesController extends AbstractRestfulController
{
    private $entityManager;
    private $examManager;
    private $sessionContainer;
    private $crtAcadYr;
    
    public function __construct($entityManager,$examManager,$sessionContainer) {
        
        $this->entityManager = $entityManager;  
        $this->examManager = $examManager; 
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
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
            $this->entityManager->getConnection()->beginTransaction();
            //Retrieve all exams performed for the geiving course
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data["class_id"]);
            $semester = $this->entityManager->getRepository(Semester::class)->findOneById($data["sem_id"]);
            $ue = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($data["ue_id"]);
            
            
            
            if(isset($data["isMarkAggregation"])&&!isset($data["subject_id"]))
            {
                $stdMarks = $this->examManager->markAggregation($ue,$classe,$semester);
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
                $ueExams = $this->entityManager->getRepository(CurrentYearUeExamsView::class)->findBy(array("subjectId"=>$data["ue_id"],"classe"=>$classe->getCode(),"status"=>1));
                $subjects = $this->examManager->getSubjectFromUe($data["ue_id"],$data["sem_id"],$data["class_id"],$this->crtAcadYr);
                $subjectExams = $this->getSubjectExams($subjects);
                $ueExams = array_merge($ueExams , $subjectExams );
            }
            else 
            {
                $subject = $this->entityManager->getRepository(Subject::class)->findOneById($data["subject_id"]);
                $ueExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$data["subject_id"],"classe"=>$classe->getCode(),"status"=>1));
            }

            
           
           


            //check first if all the mark are register
            //throw an errow if there is even a single exam that the mark is not yet registered
            if(!$this->checkAllMarksAreRegistered($ueExams))
                return new JsonModel([ "ERROR_NO_CC_OR_EXAM_DONE"  ]);
            
            //Processing catchup exam 
            //
            //Thje aim of this code is reportong mark of student that have attended catch up exam to the respective exam
            $this->computeRattrapeMark($data["ue_id"],$data["sem_id"],$data["class_id"]);
            
            $msge = $this->computeNotesAndReport($ueExams, $semester, $ue, $subject); 
                if (strcmp($msge, "ERROR_PED_REGISTRATION")==0) return new JsonModel([ "ERROR_PED_REGISTRATION"  ]);
                    
            switch($this->checkTypeOfExamsDone($ueExams))
            {
               
                case "CC_EXAM": 
                    
                    

                    $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
                
                    foreach($stdRegisteredToSubject as $std)
                    {
                        
                        $note = round(($std->getNoteCc()*0.40 + $std->getNoteExam()*0.60),2, PHP_ROUND_HALF_UP);
                        $std->setNoteFinal($note*5);
                        $std->setNoteCctp(NULL);
                        $std->setNoteExamtp(NULL);
                        $std->setGrade($this->computeGrade($classe, $note));
                        $std->setPoints($this->computePoints($classe, $note));
                       
                        $this->entityManager->flush();
                    }
                    break;
                case "CC_EXAM_CCTP": 
                
                    $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
                    foreach($stdRegisteredToSubject as $std)
                    {
                        
                        $note = round(($std->getNoteCc()*0.20 + $std->getNoteCctp()*0.20 + $std->getNoteExam()*0.60),2, PHP_ROUND_HALF_UP);
                        $std->setNoteFinal($note*5);
                        $std->setGrade($this->computeGrade($classe, $note));
                        $std->setPoints($this->computePoints($classe, $note));
                        $std->setNoteExamtp(NULL);
                        $this->entityManager->flush();
                    }
                    break;
                case "CC_EXAM_CCTP_EXAMTP": 
                    
                    $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
                    foreach($stdRegisteredToSubject as $std)
                    {
                        
                        $note = round(($std->getNoteCc()*0.20 + $std->getNoteCctp()*0.10 + $std->getNoteExam()*0.50+ $std->getNoteExamtp()*0.20),2, PHP_ROUND_HALF_UP);
                        $std->setNoteFinal($note*5);
                        $std->setGrade($this->computeGrade($classe, $note));
                        $std->setPoints($this->computePoints($classe, $note));
                        $this->entityManager->flush();
                        
                    }
                    break;
                case "CC_EXAM_EXAMTP" : 
                    
                    $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
                    foreach($stdRegisteredToSubject as $std)
                    {
                        switch($this->examManager->getCodeFiliere($classe))
                        {
                            case "PHA": $note = round(($std->getNoteCc()*0.20 +  $std->getNoteExam()*0.30+ $std->getNoteExamtp()*0.50),2, PHP_ROUND_HALF_UP);
                                        break;
                            default : $note = round(($std->getNoteCc()*0.30 +  $std->getNoteExam()*0.50+ $std->getNoteExamtp()*0.20),2, PHP_ROUND_HALF_UP);        
                        }
                        
                        $std->setNoteFinal($note*5);
                        $std->setGrade($this->computeGrade($classe, $note));
                        $std->setPoints($this->computePoints($classe, $note));
                        $std->setNoteCctp(NULL);
                        
                        $this->entityManager->flush();
                    }
                    break;
                
                case "RATTRAPAGE": 
                   
                break;

                case "STAGE_CLINIQUE_EXAM_CLINIQUE" :
                    
                   
                    $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$semester ));
                    foreach($stdRegisteredToSubject as $std)
                    { 
                        
                        $note = round(($std->getNoteCc()*0.40 + $std->getNoteExam()*0.60),2, PHP_ROUND_HALF_UP);
                        if($std->getNoteExam()<60) $note =$std->getNoteExam();
                        
                        $std->setNoteFinal($note);
                        $std->setGrade($this->computeGradeSur100($classe, $note));
                        $std->setPoints($this->computePointsSur100($classe, $note));
                        $this->entityManager->flush();
                        
                    }                  
                    break;
                case "STAGE_ENTREPRISE" :
                    
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
                    break;
                case "STAGE_HOSPITALIER" :
                    
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
                    break;                    
                case "ECN" :
                    
                    
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
                    break; 
                case "THESE" :
                    
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
                    break;                    
                default : return new JsonModel([ "ERROR_NO_CC_OR_EXAM_DONE"  ]);

            }
            $this->entityManager->getConnection()->commit();
            if(isset($data["subject_id"]))
            // retrieve the sutdent ID based on the student ID 
                $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$data["ue_id"],"idSubject"=>$data["subject_id"]),array("nom"=>"ASC")); 
            else    $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$data["ue_id"],"idSubject"=>[NULL," "]),array("nom"=>"ASC"));  
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());

            foreach($std as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std[$key] = $data;

            }
           
            
           
            $output = new JsonModel([
                   $std
            ]);
            
            return $output;
         
        } 
        catch (Exception $ex) {
            
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
    
 
  
   //This function takes as parameter a list of exams performed for a given course
   //the fonction identifies all the exam types performed and returns a code 
   //the code returned is  used to match the algorithm that  performs mark calculation
   private function checkTypeOfExamsDone($exams)
   { 
       $isCcPerformed = FALSE;
       $isExamPerformed = FALSE;
       $isCcTpPerformed = FALSE;
       $isExamTpPerformed = FALSE;
       $isRattrapagePerformed = FALSE;
       $isStageCliniquePerformed = FALSE;
       $isExamCliniquePerformed = FALSE;
       $isStageExamPerformed = FALSE;
       $isStageEntreprisePerformed = FALSE;
       $isStageHospitalierPerformed = FALSE;
       $isEcnPerformed = FALSE;
       $isThesePerformed = FALSE;

        foreach($exams as $exam)
        { 
            
                switch($exam->getType())
                {
                    case 'CC' : 
                        if($exam->getIsMarkRegistered()==1)
                            $isCcPerformed = true;
                        break;
                    case 'EXAM':
                        if($exam->getIsMarkRegistered()==1)
                            $isExamPerformed = true;
                        break;
                    case 'CCTP' : 
                        if($exam->getIsMarkRegistered()==1)
                            $isCcTpPerformed = true;
                        break;
                    case 'EXAMTP':
                        if($exam->getIsMarkRegistered()==1)
                        $isExamTpPerformed = true;
                        break;
                    case 'RAT' : 
                        if($exam->getIsMarkRegistered()==1)
                            $isRattrapagePerformed = true;
                        break;
                    case 'STAC':
                        if($exam->getIsMarkRegistered()==1)
                            $isStageCliniquePerformed = true;
                        break;
                    case 'EXAMC':
                        if($exam->getIsMarkRegistered()==1)
                            $isExamCliniquePerformed = true;
                        break;                        
                    case 'STAE':
                        if($exam->getIsMarkRegistered()==1)
                            $isStageEntreprisePerformed = true;
                        break;  
                    case 'STAH':
                        if($exam->getIsMarkRegistered()==1)
                            $isStageHospitalierPerformed = true;
                        break;                         
                    case 'ECN':
                        if($exam->getIsMarkRegistered()==1)
                            $isEcnPerformed = true;
                        break; 
                    case 'THESE':
                        if($exam->getIsMarkRegistered()==1)
                            $isThesePerformed = true;
                        break;                         
                } 
                
        } 
  
        
        if($isCcPerformed&&$isExamPerformed && !$isCcTpPerformed && !$isExamTpPerformed)
            return "CC_EXAM";
        if($isCcPerformed&&$isExamPerformed && $isCcTpPerformed && !$isExamTpPerformed)
            return "CC_EXAM_CCTP";
        if($isCcPerformed&&$isExamPerformed && !$isCcTpPerformed && $isExamTpPerformed)
            return "CC_EXAM_EXAMTP";
        if($isCcPerformed&&$isExamPerformed && $isCcTpPerformed && $isExamTpPerformed)
            return "CC_EXAM_CCTP_EXAMTP";
       /* if($isRattrapagePerformed)
            return "RATTRAPAGE";*/
        if($isStageCliniquePerformed && $isExamCliniquePerformed) 
            return "STAGE_CLINIQUE_EXAM_CLINIQUE";
        if($isStageEntreprisePerformed)
            return "STAGE_ENTREPRISE";
        if($isStageHospitalierPerformed)
            return "STAGE_HOSPITALIER";        
        if($isEcnPerformed)
            return "ECN";  
        if($isThesePerformed)
            return "THESE";        
        return -1;
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
   private function computeNotesAndReport($ueExams,$sem,$ue,$subject)
   {
        $countCC = 0;
        $countEXAM = 0;
        $countCCTP = 0;
        $countEXAMTP = 0;
        $countSTAGEC = 0;
        $countEXAMC = 0;
        $countSTAGEE = 0;
        $countSTAGEH = 0;
        $countRAT = 0;
        $countECN = 0;
        $countTHESE = 0;
	$noteCc = [];
        $noteExam = [];
        $noteCctp = [];
        $noteExamtp = [];
        $noteStagee = [];
        $noteStageh = [];
        $noteStagec = [];
        $noteRat = [];
        $noteECN = [];
        $noteTHESE = [];
        $noteExamc = [];
        //initializing 
        
        $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem ));
        foreach ($stdRegisteredToSubject as $std)
        {
            
                $noteCc[$std->getStudent()->getId()] = 0;
                $noteExam[$std->getStudent()->getId()] = 0;
                $noteCctp[$std->getStudent()->getId()] = 0;
                $noteExamtp[$std->getStudent()->getId()] = 0;
                $noteStagee[$std->getStudent()->getId()] = 0;
                $noteStageh[$std->getStudent()->getId()] = 0;
                $noteStagec[$std->getStudent()->getId()] = 0;
                $noteRat[$std->getStudent()->getId()] = 0;
                $noteECN[$std->getStudent()->getId()] = 0;
                $noteTHESE[$std->getStudent()->getId()] = 0;
                $noteExamc[$std->getStudent()->getId()] = 0;
                
        }
        

        foreach($ueExams as $exam)
        {
            $tmp = 0;
            $examObject = $this->entityManager->getRepository(Exam::class)->findOneById($exam->getId());
            $examRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($examObject );
            if($exam->getType()=="CC")
            {
                $countCC++;

                foreach ($examRegistration as $examR)
                { 
                    if(!array_key_exists($examR->getStudent()->getId(), $noteCc)) return "ERROR_PED_REGISTRATION";
                  
                        $tmp = $noteCc[$examR->getStudent()->getId()] + $this->getMark($examObject, $examR);
                        $noteCc[$examR->getStudent()->getId()] = $tmp;
                
                } 

            }
            if($exam->getType()=="EXAM")
            {
                $countEXAM++; 

                foreach ($examRegistration as $examR)
                { 
                    if(!array_key_exists($examR->getStudent()->getId(), $noteExam))   return "ERROR_PED_REGISTRATION";
                                        
                        $tmp = $noteExam[$examR->getStudent()->getId()] + $this->getMark($examObject, $examR);
                        $noteExam[$examR->getStudent()->getId()] =  $tmp;
                    
                }
            }
            if($exam->getType()=="EXAMC")
            {
                $countEXAMC++; 

                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteExamc)) return "ERROR_PED_REGISTRATION";
                                        
                        $tmp = $noteExamc[$examR->getStudent()->getId()] + $this->getMark($examObject, $examR);
                        $noteExamc[$examR->getStudent()->getId()] =  $tmp;
                    
                }

            }

            if($exam->getType()=="CCTP")
            {
                $countCCTP++;
                foreach ($examRegistration as $examR) 
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteCctp)) return "ERROR_PED_REGISTRATION";
                        $tmp =$noteCctp[$examR->getStudent()->getId()] + $this->getMark($examObject, $examR);
                        $noteCctp[$examR->getStudent()->getId()] =  $tmp;
                }
                

            }
            if($exam->getType()=="EXAMTP")
            {
                $countEXAMTP++;

                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteExamtp)) return "ERROR_PED_REGISTRATION";
                        $noteExamtp[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }
            if($exam->getType()=="STAC")
            {
                $countSTAGEC++;
                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteStagec)) return "ERROR_PED_REGISTRATION";
                        $noteStagec[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }
            if($exam->getType()=="STAE")
            {
                $countSTAGEE++;
                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteStagee)) return "ERROR_PED_REGISTRATION";
                        $noteStagee[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }
            if($exam->getType()=="STAH")
            {
                $countSTAGEH++;
                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteStageh)) return "ERROR_PED_REGISTRATION";
                        $noteStageh[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }            
            if($exam->getType()=="ECN")
            {
                $countECN++; 
                foreach ($examRegistration as $examR)
                {
                    if(!array_key_exists($examR->getStudent()->getId(), $noteECN)) return "ERROR_PED_REGISTRATION";
                        $noteECN[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }
            if($exam->getType()=="THESE")
            {
                $countTHESE++;
                foreach ($examRegistration as $examR)
                {
                    //Saving mark only for students who have atten
                    if(!array_key_exists($examR->getStudent()->getId(), $noteTHESE)) return "ERROR_PED_REGISTRATION";
                   if(strcmp($examR->getAttendance(),"P")==0 ) 
                    $noteTHESE[$examR->getStudent()->getId()] += $this->getMark($examObject, $examR);
                }

            }            
        }

        foreach($noteExam as $key=>$value)
        {
            
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countEXAM>0)$std->setNoteExam(round($value/$countEXAM,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush(); 
        }
        foreach($noteExamc as $key=>$value)
        {
            
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countEXAMC>0)$std->setNoteExam(round($value/$countEXAMC,2,PHP_ROUND_HALF_UP));
            
            //if(!is_null($value))$std->setNoteExamc(round($value/$countEXAMC,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush(); 

        }       
        foreach($noteCc as $key=>$value)
        {
            //Collect all student registered to the given unit for a given semester
            
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countCC>0) $std->setNoteCc(round($value/$countCC,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush(); 
        }
        foreach($noteCctp as $key=>$value)
        { 
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countCCTP>0 ) $std->setNoteCctp(round($value/$countCCTP,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush();
        }
        foreach($noteExamtp as $key=>$value)
        {
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countEXAMTP>0) $std->setNoteExamtp(round($value/$countEXAMTP,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush();
        }
        foreach($noteStagee as $key=>$value)
        {
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
           // if(!is_null($value)) $std->setNoteStagee(round($value/$countSTAGEE,2,PHP_ROUND_HALF_UP));
            if($countSTAGEE>0) $std->setNoteExam(round($value/$countSTAGEE,2,PHP_ROUND_HALF_UP));
     
            $this->entityManager->flush();
        }
        foreach($noteStageh as $key=>$value)
        {
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
           // if(!is_null($value)) $std->setNoteStagee(round($value/$countSTAGEE,2,PHP_ROUND_HALF_UP));
            if($countSTAGEH>0) $std->setNoteExam(round($value/$countSTAGEH,2,PHP_ROUND_HALF_UP));
     
            $this->entityManager->flush();
        }        
        foreach($noteStagec as $key=>$value)
        { 
            //Collect all student registered to the given unit for a given semester
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            //if(!is_null($value)) $std->setNoteStagec(round($value/$countSTAGEC,2,PHP_ROUND_HALF_UP));
            if($countSTAGEC>0)$std->setNoteCc(round($value/$countSTAGEC,2,PHP_ROUND_HALF_UP));

            $this->entityManager->flush();
        } 
        foreach($noteECN as $key=>$value)
        {
            //Collect all student registered to the given unit related exam
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            if($countECN>0) $std->setNoteExam(round($value/$countECN,2,PHP_ROUND_HALF_UP));
              $this->entityManager->flush();
        }
        foreach($noteTHESE as $key=>$value)
        {
            //Collect all student registered to the given unit related exam
            $std = $this->entityManager->getRepository(Student::class)->findOneBy(array("id"=>$key ));
            $std = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"subject"=>$subject,"semester"=>$sem,"student"=>$std ));
            //if(!is_null($value)) $std->setNoteExam(round($value/$countTHESE,2,PHP_ROUND_HALF_UP));
            if($countEXAMC>0 && $countTHESE>0) $std->setNoteExam(round($value/$countTHESE,2,PHP_ROUND_HALF_UP));
            $this->entityManager->flush();
        } 
        
        return "SUCCESS";
        
        
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
            $subjectExam = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$sub["id"],"status"=>1));
            $myArr = array_merge($myArr,$subjectExam);
      
         
        } 
       
        return $myArr;
   }
   private function getSubjectRat($subject)
   {
       // $myArr = [];
       
       // foreach ($subjects as $sub)
       // {
            $subjectExam = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$subject["id"],"type"=>"RAT","status"=>1));
           // $myArr = array_merge($myArr,$subjectExam);
      
         
        //} 
       
        //return $myArr;
            return $subjectExam;
   }   
   
   private function computeRattrapeMark($ueID,$semID,$classeID)
   {
     
        $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($classeID);
        $ueExams = $this->entityManager->getRepository(CurrentYearOnlyUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"type"=>["EXAM","EXAMC","STAE"],"status"=>1));
        $ueRat = $this->entityManager->getRepository(CurrentYearOnlyUeExamsView::class)->findBy(array("subjectId"=>$ueID,"classe"=>$classe->getCode(),"type"=>"RAT","status"=>1));

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

                        
                        $subjectExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$subject["id"],"classe"=>$classe->getCode(),"type"=>["EXAM","STAC","STAE"],"status"=>1));
                        
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
    
}
