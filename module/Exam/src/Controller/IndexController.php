<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Student;

use Exam\Service\ExamManager;
use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\UnitRegistration;
use Application\Entity\ExamRegistration;
use Application\Entity\Exam;
use Application\Entity\User;
use Application\Entity\Deliberation;
use Application\Entity\ClassOfStudy;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\AllYearsSubjectRegistrationView;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\CurrentYearOnlyUeExamsView;
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\AcademicYear;
use Application\Entity\AdminRegistration;
use Application\Entity\StudentSemRegistration;

use Port\Csv\CsvReader;
use Port\Csv\CsvWriter;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\StepAggregator as Workflow;
use Port\Steps\Step\FilterStep;
use Port\Filter\OffsetFilter;
use Port\Reader\ArrayReader;
use Port\Writer\ArrayWriter;
use Port\Steps\Step\ConverterStep;

class IndexController extends AbstractActionController
{
    private $entityManager;
    private $examManager;
    private $sessionContainer;
    private $crtAdadYr;
    public function __construct($entityManager,$examManager,$sessionContainer) {
        $this->entityManager = $entityManager;
        $this->examManager = $examManager;
        $this->sessionContainer = $sessionContainer;
        $this->crtAdadYr = $sessionContainer->currentAcadYr;
    }

    public function indexAction()
    {
        return [];
    }
    
    public function examlistplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    public function newexamAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }    
    public function calculnotestplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function deliberationAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    public function startDeliberationAction()
    {
         $data = $this->params()->fromRoute();            
         $subjectID = -1;
         $subject= null;
         if(isset($data['subjectId']))
         {
             $subjectID = $data['subjectId'];
             $subject = $this->entityManager->getRepository(Subject::class)->find($data["subjectId"])->getSubjectCode();
         }
         if(isset($data['ueId'])) 
         {
             $ueID = $data["ueId"];
             $ue = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ueId"])->getCode();
         }
        
         $class= $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classeId"]);
         $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ueId"]);
        
         if($class->getDeliberation())
            $delibCondition = $class->getDeliberation()->getDelibCondition();
         else $delibCondition="RAS";
         $delibCondition = str_replace("IF", "SI", $delibCondition);
         $delibCondition = str_replace("&&", "ET", $delibCondition);
         $delibCondition = str_replace("||", "OU", $delibCondition);
      
          $view = new ViewModel([
            'delibCondition'=>$delibCondition, 
            'ue'=>$ue,
            'ueID'=>$ueID ,
            'subject'=>$subject,
            'subjectID'=>$subjectID,
            'semID'=>$data["semId"],
            'classeID'=>$data["classeId"]
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function applyDelibConditionAction()
    {
        $data = $this->params()->fromQuery();   
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        
         $class= $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classeID"]);
         $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ueID"]);
         $subjectID = NULL; 
         if(isset($data["subjectId"]) && $data["subjectId"]!=-1 )
         {
            $subject = $this->entityManager->getRepository(Subject::class)->find($data["subjectId"]);
            $subjectID = $data["subjectId"];
         }
         else {$subject= NULL;  }
  
         $semester = $this->entityManager->getRepository(Semester::class)->find($data["semID"]);
         $students =  $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("semester"=>$semester,"teachingUnit"=>$teachingUnit,"subject"=>$subject));
  
         if($class->getDeliberation())
            $delibCondition = $class->getDeliberation()->getDelibCondition();
         else $delibCondition="RAS";     
         //$delibCondition ="'".$delibCondition.";';";
         
     
         foreach($students as $std)
         {
          $note = $std->getNoteFinal();  
          $noteBeforeDelib = $std->getNoteFinal();
       
          
          eval($delibCondition);

                   if($noteBeforeDelib<$note)
                {
                  $noteGap = $note - $noteBeforeDelib;



                  $ueExams = $this->entityManager->getRepository(CurrentYearOnlyUeExamsView::class)->findBy(array("subjectId"=>$data["ueID"],"classe"=>$class->getCode(),"status"=>1,"acadYrId"=>$this->crtAdadYr->getId()));
                  $subjects = $this->examManager->getSubjectFromUe($data["ueID"],$data["semID"],$data["classeID"],$this->crtAdadYr);

                  foreach($subjects as $sub)
                  {
                      $subjectExams = $this->entityManager->getRepository(CurrentYearSubjectExamsView::class)->findBy(array("subjectId"=>$sub["id"],"classe"=>$class->getCode(),"status"=>1,"acadYrId"=>$this->crtAdadYr->getId()));
                      $ueExams = array_merge($ueExams,$subjectExams);

                  }
                  $totalExamsDone = sizeof($ueExams);


                  $sharedValued = round($noteGap/5,2,PHP_ROUND_HALF_UP);

                  $std->setNoteFinal($note);
                  if( $class->getCycle()->getCycleLevel()==1)
                  {
                      $std->setGrade("C");
                      $std->setPoints(2);
                  }
                  if( $class->getCycle()->getCycleLevel()==2)
                  {
                     $std->setGrade("B-");
                     $std->setPoints(2.7);
                  }
                  $std->setIsFromDeliberation(1);
                  if(!$data["isModularComputation"])
                  foreach ($ueExams as $ueExam)
                  {               
                      $exam = $this->entityManager->getRepository(Exam::class)->find($ueExam->getId());

                      $stdRegisteredToSubject = $this->entityManager->getRepository(ExamRegistration::class)->findOneBy(array("exam"=>$exam,"student"=>$std->getStudent()));
                      if($stdRegisteredToSubject)
                      {
                          if($exam->getIsMarkConfirmed()==1)
                              $stdRegisteredToSubject->setConfirmedMark(round($stdRegisteredToSubject->getConfirmedMark()+$sharedValued,2,PHP_ROUND_HALF_UP));
                          if($exam->getIsMarkValidated()==1)
                              $stdRegisteredToSubject->setValidatedMark(round($stdRegisteredToSubject->getValidatedMark()+$sharedValued,2,PHP_ROUND_HALF_UP));
                          if($exam->getIsMarkRegistered()==1)
                              $stdRegisteredToSubject->setRegisteredMark(round($stdRegisteredToSubject->getRegisteredMark()+$sharedValued,2,PHP_ROUND_HALF_UP));  
                      }
                  }
          }
          
          
         }

         $this->entityManager->flush();
         $this->entityManager->getConnection()->commit();
           $std = $this->entityManager->getRepository(AllYearsSubjectRegistrationView::class)->findBy(array("idUe"=>$data["ueID"],"idSubject"=>$subjectID,"acadYrId"=>$this->crtAdadYr->getId()),array("nom"=>"ASC")); 
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());

            foreach($std as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std[$key] = $data;

            }
            for($i=0;$i<sizeof($std);$i++)
            {
                $std[$i]['nom']= mb_convert_encoding($std[$i]['nom'], 'UTF-8', 'UTF-8');
                //$registeredStd[$i]['prenom']= mb_convert_encoding($registeredStd[$i]['prenom'], 'UTF-8', 'UTF-8');
                
            }    
         //$this->entityManager->getConnection()->commit();
          $view = new JsonModel([
              $std
         ]);
        return $view; 
        } 
        catch (Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }        

    }    
    public function calculmpstplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }  
    public function suiviparcourtplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }  
    public function gradelistAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    public function delibConditionsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }     
    
    public function newgradeAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    public function updateexamregistraionAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
            $data = $this->params()->fromQuery();
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["semId"]);
            $ue = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ueId"]);
            if(isset($data["subjectId"]))
            {
                $subject = $this->entityManager->getRepository(Subject::class)->find($data["subjectId"]);
                $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("semester"=>$semester,"teachingUnit"=>$ue,"subject"=>$subject));
            }
            else
                $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("semester"=>$semester,"teachingUnit"=>$ue,"subject"=>null));
            
            $exam = $this->entityManager->getRepository(Exam::class)->find($data["examId"]);
            $examRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam );
            
            //Searching student who registered to subject but not registered to exam related to that subject
            //update exam_registration in order to add the missed student
            foreach($unitRegistration as $unitR)
            {
                //Searching for student
                $flag = false; 
                foreach ($examRegistration as $examR)
                {
                    if($unitR->getStudent()==$examR->getStudent()) 
                    {
                        $flag = true;
                        break;
                    }
                }
                if(!$flag)
                {
                    //Updating the exam_registration table
                    $examR = new ExamRegistration ();
                    $examR->setExam($exam);
                    $examR->setStudent($unitR->getStudent());
                    $this->entityManager->persist($examR);
                    $this->entityManager->flush();
                }
            }
     //This code performs the reverse of the previous one
     //We look at student who a register to an exam, and no longer exists as student registered for the given subject
            foreach($examRegistration  as $examR)
            {
                //Searching
                $flag = false; 
                foreach ($unitRegistration as $unitR)
                {
                    if($unitR->getStudent()==$examR->getStudent()) 
                    {
                        //for clearing duplicates if student is registered to exam twice
                        if($flag)
                        {
                            //updating the exam_registration table
                            $this->entityManager->remove($examR);
                            $this->entityManager->flush();
                            
                        }
                        $flag = true;
                        
                    }
                }
                if(!$flag)
                {
                    //updating the exam_registration table
                    $this->entityManager->remove($examR);
                    $this->entityManager->flush();

                }

            }
            
            //this code clear duplicates from EXAM_REGISTRATION
            //looking for student that is registered to subject
            //if he his registered many times to exam then clear duplicates
            
            foreach ($unitRegistration as $unitR) 
            {
                $examRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findBy(array("exam"=>$exam,"student"=>$unitR->getStudent()) );
                
                if(sizeof($examRegistration)>1)
                {
                    for($i=1;$i<sizeof($examRegistration);$i++)
                    {
                        $this->entityManager->remove($examRegistration[$i]);
                        $this->entityManager->flush();
                    }
                }
            }
            
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
    public function examSearchAction()
    {
         $this->entityManager->getConnection()->beginTransaction();

        try
        { 
            $data = $this->params()->fromQuery();
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {            
                // retrieve subjects based on subject code

                //$rsm = new ResultSetMapping();
                // build rsm here

                $query = $this->entityManager->createQuery('SELECT e.code,e.classe,e.semester,e.subject,e.date FROM Application\Entity\CurrentYearUeExamsView e'
                        .' WHERE e.code LIKE :code  AND e.status = 1 AND e.isMarkRegistered != 1');
                $query->setParameter('code', '%'.$data["id"].'%');

                $subjects = $query->getResult();
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));  
                if($userClasses)
                {

                    foreach($userClasses as $classe)
                    {
                        $query = $this->entityManager->createQuery('SELECT e.code,e.classe,e.semester,e.subject,e.date FROM Application\Entity\CurrentYearUeExamsView e'
                                .' WHERE e.classe = :classe AND e.code LIKE :code AND e.status = 1 AND e.isMarkRegistered != 1');
                        $query->setParameter('code', '%'.$data["id"].'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());

                        $subjects_1 = $query->getResult();
                        $subjects= array_merge($subjects , $subjects_1);
                    }
                }                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $subjects
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    
    public function  noteDuplicationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();

        try
        {
            $data = $this->params()->fromQuery();
            $data = json_decode($data["data"]);
            
            
            $fromExam = $data->fromExam;
            $toExams = $data->toExams;
           
         
            $exam = $this->entityManager->getRepository(Exam::class)->findOneByCode($fromExam);
            $examRegistration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam );
            //prevent duplication when marks are not registered from source
            if($exam->getIsMarkRegistered()==0)
            {
                $output = new JsonModel([
                    false
                ]);

                return $output;                
            }
            foreach($examRegistration as $examR)
            {
                foreach($toExams as $dest)
                {
                  
                    $exam_1 = $this->entityManager->getRepository(Exam::class)->findOneByCode($dest);
                    //dulicate only marks that are not ye registered
                    if($exam_1->getIsMarkRegistered()==1) continue; 
                    
                    $examRegistration_1 = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam_1 );
                    foreach($examRegistration_1 as $examR_1)
                    {
                        if($examR->getStudent()->getMatricule() == $examR_1->getStudent()->getMatricule())
                        {
                            $examR_1->setRegisteredMark($examR->getRegisteredMark());
                            $this->entityManager->flush();
                            
                            break;
                        }
                    }
                    

                    
                }
            }
            
            foreach($toExams as $dest)
            {
                $exam_1 = $this->entityManager->getRepository(Exam::class)->findOneByCode($dest);
                $exam_1->setIsMarkRegistered(1);
                $this->entityManager->flush();                
            }
            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    true
            ]);

            return $output;   
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    public function classesAssociatedToDelibAction()
    {
        $this->entityManager->getConnection()->beginTransaction();

        try
        {
            $data = $this->params()->fromQuery();
            $data = json_decode($data["id"]);
          
         
            $delib= $this->entityManager->getRepository(Deliberation::class)->find($data);
            $classes = $this->entityManager->getRepository(ClassOfStudy::class)->findByDeliberation($delib);
            //prevent duplication when marks are not registered from source
            foreach($classes as $key=>$value)
            {
                //$hydrator = new ReflectionHydrator();
                //$data = $hydrator->extract($value);

                $classes[$key] = $value->getCode();
            }
            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    $classes
            ]);

            return $output;   
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 
    }  
    public function ueMarkTransactionLockAction()
    {
        $this->entityManager->getConnection()->beginTransaction();

        try
        {
            $data = $this->params()->fromQuery();
            $data = json_decode($data["id"]);
          
         
            $classe= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data);
            $classe->setMarkCalculationStatus(1);
            $this->entityManager->flush();
            //prevent duplication when marks are not registered from source

            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                   
            ]);

            return $output;   
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 
    }  
    public function semMarkTransactionLockAction()
    {
        $this->entityManager->getConnection()->beginTransaction();

        try
        {
            $data = $this->params()->fromQuery();
          
            $classCode = $data["classCode"];
            $semID = $data["semID"];
         
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classCode);
            $sem= $this->entityManager->getRepository(Semester::class)->find($semID);
            $acadYr= $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            $semClasse= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("classOfStudy"=>$classe,"semester"=>$sem,"academicYear"=>$acadYr));
            
            //update and set  student final sitatus (admitted or repeating class)
            //
            if($sem->getRanking()%2==0)
            {
                $students = $this->entityManager->getRepository(AdminRegistration::class)->findBy(array("academicYear"=>$acadYr,
                    "classOfStudy"=>$classe));


                foreach ($students as $std)
                {
                    if($std->getDecision()=="AJR")
                        $std->setDecision("RED");
                        $std->setRegistrationStatus("COMPLETED");
                }
                
               
            }
            if($sem->getRanking()%2==0)
                $firstSem = $sem->getRanking()-1;
            else $firstSem = $sem->getRanking();
            
            for($i=$firstSem;$i<=$sem->getRanking();$i++)
            {
                 
                
                $sem_1= $this->entityManager->getRepository(Semester::class)->findOneBy(array("ranking"=>($firstSem),"academicYear"=>$acadYr));
                $semClasse= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("classOfStudy"=>$classe,"semester"=>$sem_1,"academicYear"=>$acadYr));
                  
                //locking MPS calculation
                $semClasse->setMarkCalculationStatus(1);

                $units = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(array("classOfStudy"=>$classe,"semester"=>$sem_1)); 

                //Locking mark calculation for each subject
                foreach($units as $unit)
                {
                    $unit->setMarkCalculationStatus(1);
                }
                
                $firstSem++;
                
            }
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                   
            ]);

            return $output;   
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 
    }    
    
 public function sendResultBySMSAction()
    {
        $this->entityManager->getConnection()->beginTransaction();

        try
        {
            $data = $this->params()->fromQuery();
          
            $classCode = $data["classCode"];
            $semID = $data["semID"];
         
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classCode);
            $sem= $this->entityManager->getRepository(Semester::class)->find($semID);
            $acadYr= $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            $semClasse= $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("classOfStudy"=>$classe,"semester"=>$sem,"academicYear"=>$acadYr));
            
            //Send SMS Message to student
            $msge = "";
            
            if($sem->getRanking()%2==0)
            {
                $students = $this->entityManager->getRepository(AdminRegistration::class)->findBy(array("academicYear"=>$acadYr,
                    "classOfStudy"=>$classe,"status"=>1));

                $totalSMS = 0;
                $count = 0;
                $decision = "";
                
                foreach ($students as $std)
                {
                    $language = "FR";
                    if($std->getStudent()->getLanguage()=="ANG") $language = "ANG";
                    if($std->getStudent()->getGender()=="F") $civilite = "Mme";
                    elseif($std->getStudent()->getGender()=="M") $civilite = "Mr";
                    $msge_fr_prefix= $civilite.". ".$std->getStudent()->getNom().", ";
                    $msge_en_prefix= "Dear ".$civilite.". ".$std->getStudent()->getNom().", ";
                    $msge_fr_std=$msge_fr_prefix."Vos résultats  pour le compte de l'année académique ".$acadYr->getCode().": \n ";
                    $msge_en_std = $msge_en_prefix."Your results for the ".$acadYr->getCode()." academic year: \n ";
                    $msge_fr_parent = "Année académique ".$acadYr->getCode()." résultats  de  ".$std->getStudent()->getNom()."  ".$classCode.": \n";
                    
                    $msge_en_parent = "Results for the ".$acadYr->getCode()." academic year of ".$civilite.". ".$std->getStudent()->getNom()."  ".$classCode.": \n"; 
                    $phoneNum = $std->getStudent()->getPhoneNumber();
                    $sponsorPhoneNum = $std->getStudent()->getSponsorPhoneNumber();
                    //$phoneNum="691144986";
                    //$sponsorPhoneNum = "675070526";
                    //$phoneNum="670128098";
                    //$sponsorPhoneNum = "655524865";
                    $studentSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$std->getStudent(),"semester"=>$sem));
                    if($studentSemRegistration)
                    { if($std->getDecision()=="AJR" || $std->getDecision()=="RED")     continue; // skip student that have failed
                        $pourcentageValide = $studentSemRegistration->getValidationPercentage();
                        $mpc = $studentSemRegistration->getMpcCurrentSem();
                        $mention = $studentSemRegistration->getAcademicProfile();
                        if($std->getDecision()=="RED") $decision = "Redouble";
                        elseif($std->getDecision()=="ADM") $decision = "Admis(e)";
                        elseif($std->getDecision()=="AJR") $decision = "Redouble";
                        //$msge.= "Crédits validés:".$pourcentageValide."%  MPC:".$mpc." Mention:".$mention." Décision: ".$decision.". Bien vouloir veiller à votre inscription académique et au paiement de vos frais de scolarité dans les delais. https://bit.ly/3yAU3P3";
                        //$msge_1 .= "Crédits validés:".$pourcentageValide."%  MPC:".$mpc." Mention:".$mention." Décision ".$decision.". Bien vouloir veiller à son inscription académique et au paiement de ses frais de scolarité dans les delais. https://bit.ly/3yAU3P3 ";
                        $msge_fr_std.= "MPC:".$mpc."\n Mention:".$mention."\n Décision: ".$decision.".\n Bien vouloir veiller à votre inscription académique et au paiement de vos frais de scolarité avant le 18/09/2023. http://bit.ly/3KGCHIF";
                        $msge_fr_parent .= " MPC:".$mpc."\n Mention:".$mention."\n Décision ".$decision.".\n Bien vouloir veiller à son inscription académique et au paiement de ses frais de scolarité avant le 18/09/2023. http://bit.ly/3KGCHIF ";
                                            
                        if($studentSemRegistration->getAcademicProfile()=="AB")  $mention ="Fairly.Good";
                        elseif($studentSemRegistration->getAcademicProfile()=="P") $mention="Fair";
                        elseif($studentSemRegistration->getAcademicProfile()=="B") $mention="Good";
                        elseif($studentSemRegistration->getAcademicProfile()=="TB") $mention="V.Good";
                        elseif($studentSemRegistration->getAcademicProfile()=="AV") $mention="Poor";
                        if($std->getDecision()=="ADM") $decision = "Promoted";
                        elseif($std->getDecision()=="RED") $decision = "Failed";
                        elseif($std->getDecision()=="AJR") $decision = "Failed";
                        //$msge_english .= "Credits validated:".$pourcentageValide."%  GPA:".$mpc." Classification:".$mention." Decision: ".$decision.". Kindly ensure your registration and payment of your tuition  fee is done on time. https://bit.ly/3yAU3P3";
                        //$msge_to_parent .= "Credits validated:".$pourcentageValide."%  GPA:".$mpc." Classification:".$mention." Decision: ".$decision.". Please ensure that they register and pay their tuition fees on time. https://bit.ly/3yAU3P3 ";
                        
                        $msge_en_std .= "GPA: ".$mpc."\n Classification: ".$mention."\n Decision: ".$decision."\n Please proceed to your academic year registration and payment of your tuition fees before the 18/09/2023. http://bit.ly/3KGCHIF";
                        $msge_en_parent .= "GPA: ".$mpc."\n Classification: ".$mention."\n Decision: ".$decision."\n Please proceed to his(her) academic registration and the payment of the tuition fees before the 18/09/2023. http://bit.ly/3KGCHIF ";
                        /*$msge_encoded = urlencode($msge);
                        $msge_1_encoded = urlencode($msge_1);
                        $msge_english_encoded = urlencode($msge_english);
                        $msge_to_parent_encoded = urlencode($msge_to_parent);*/
                        //if($std->getDecision()=="ADM")
                        //{
                        $phoneNum = '+237'.$phoneNum;
                        $sponsorPhoneNum = '+237'.$sponsorPhoneNum;
                            if($std->getStudent()->getLanguage()=="ANG")
                            { 
                                $count++;
                                if($this->telephoneIsCmr($phoneNum))
                                $this->examManager->sendAvylTextSMS($phoneNum,$msge_en_std);
                                if($this->telephoneIsCmr($sponsorPhoneNum))
                                    $this->examManager->sendAvylTextSMS($sponsorPhoneNum,$msge_en_parent);
                                //$this->examManager->sendSMS($phoneNum,$msge_english_zncodzd);
                                //$this->examManager->sendSMS($sponsorPhoneNum,$msge_to_parent_encoded);
                                $totalSMS=$totalSMS+2;                                
                            }
                            else{
                                $count++;
                                if($this->telephoneIsCmr($phoneNum))
                                    $this->examManager->sendAvylTextSMS($phoneNum,$msge_fr_std);
                                if($this->telephoneIsCmr($sponsorPhoneNum))
                                    $this->examManager->sendAvylTextSMS($sponsorPhoneNum,$msge_fr_parent);
                                //$this->examManager->sendSMS($phoneNum,$msge_english_encoded);
                                //$this->examManager->sendSMS($sponsorPhoneNum,$msge_to_parent_encoded);
                                $totalSMS=$totalSMS+2;
                            }

                        //} 
                    }
                 
                }
                
                //Send Feedback to administrator
                $msge = $classe->getCode().": ".$totalSMS." SMS envoyés   avec succès";
                //$msge = urlencode($msge);
                $phoneNum="+237670128098";
                $this->examManager->sendAvylTextSMS($phoneNum,$msge);
                $totalSMS++;
                $totalSMS += $acadYr->getTotalSmsSent(); 
                $semClasse->setSendSmsStatus(1); 
                $acadYr->setTotalSmsSent($totalSMS);
            }
           
                
            
            $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                   
            ]);

            return $output;   
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        } 
    }
    
/**
 * Vérifier si le numéro de téléphone est camerounais
 * 
 * @param string $telephone Numéro de telephone au format +2376XXXXXXXX ou 6XXXXXXXX
 * @return bool
 */
    private function telephoneIsCmr( $telephone) {
	if (!preg_match('/^\+237/', $telephone)) $telephone = '+237'.$telephone;
	return preg_match('/^\+237(((222|233|242|243)\d{6})|((62|65|66|67|68|69)\d{7}))$/', $telephone) ? true : false;
    }

     
}
