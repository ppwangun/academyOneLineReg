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
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\Student;
use Application\Entity\School;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\AllYearsSubjectRegistrationView;
use Application\Entity\Degree;
use Application\Entity\FieldOfStudy;
use Application\Entity\Faculty;
use Application\Entity\GradeValueRange;
use Application\Entity\ClassOfStudy;
use Application\Entity\TrainingCurriculum;
use Application\Entity\AdminRegistration;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\TeachingUnit;
use Application\Entity\AcademicYear;
use Application\Entity\RegisteredStudentView;
use Application\Entity\Semester;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\Exam;
use Application\Entity\ExamRegistration;
use Application\Entity\Subject;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\StudentExamRegistrationView;
use Application\Entity\ProfileAcademic;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\AllYearsRegisteredStudentView;








use Student\Service\StudentManager;



class ExamReportsController extends AbstractActionController
{
    private $entityManager;
    private $examManager;
    private $backlogs;
    private $cptSubjects;
    private  $crtAcdYr;
    public function __construct($entityManager,$examManager,$sessionContainer) {
        $this->entityManager = $entityManager;
        $this->examManager = $examManager;
        $this->crtAcdYr = $sessionContainer->currentAcadYr;
    }

    public function indexAction()
    {
        return [];
    }
    
    public function printpvindivAction()
    {
         
       $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromRoute(); 
            $id = $data["id"];
            $semID = $data["semID"];
            $classeID = $data["classID"];
            
            $ue = null;
            $classe =  $this->entityManager->getRepository(ClassOfStudy::class)->find($classeID);
                       
            // retrieve the sutdent ID based on the student ID 
            $std = $this->entityManager->getRepository(AllYearsSubjectRegistrationView::class)->findBy(array("idUe"=>$id,"status"=>1,"idSubject"=>[NULL," "],"acadYrId"=>$this->crtAcdYr->getId()),array("nom"=>"ASC")); 
            $grode_of_failures = []; 
            if($classe->getCycle()->getCycleLevel() == 1)  $grode_of_failures = ["F","E","D","D+","C-"];
            else $grode_of_failures = ["F","E","D","D+","C-","C","C+"];
            $std1 = $this->entityManager->getRepository(AllYearsSubjectRegistrationView::class)->findBy(array("idUe"=>$id,"status"=>1,"idSubject"=>[NULL," "],"grade"=>$grode_of_failures,"acadYrId"=>$this->crtAcdYr->getId()),array("nom"=>"ASC")); 
           
            
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());
            if($std)
            {
                foreach($std as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $std[$key] = $data;
                    $credits = $value->getCredits();

                }

                $ue =  $this->entityManager->getRepository(TeachingUnit::class)->find($id);
            }
            $acadYr =  $this->crtAcdYr;; 
             

            $sem =  $this->entityManager->getRepository(Semester::class)->find($semID);
            $subjects = $this->examManager->getSubjectFromUe($id,$sem->getId(),$classe->getId(),$this->crtAcdYr->getId());

            $semestre = $sem->getCode();
            
            $school =  $this->entityManager->getRepository(School::class)->findAll()[0]; 
            //$classe = $classe->getClassOfStudy();
            
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            //$classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $acadYr = $acadYr->getCode();

            //List of exam performed for the given subject
            $exams = $this->examManager->getExamWithMarkRegistered($id,$semID,$classeID,$this->crtAcdYr->getId());
            
            //compute statistics
            $totalStudent = sizeof($std);
            $totalFailure = sizeof($std1);
            
            $this->entityManager->getConnection()->commit();
            
           $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
            $view = new ViewModel([
                'school'=>$school->getName(),
                'logo'=>$school->getLogo(),
                'brandInfo'=>$brandInfo,
                'students'=>$std,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe->getCode(),
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'subjects'=>$subjects,
                'exams'=>$exams,
                'totalStudent'=>$totalStudent,
                'totalFailure'=>$totalFailure,
                'credits'=>$credits,
                'cycle_level'=>$classe->getCycle()->getCycleLevel()
            ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);
            return $view;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         


    }
    
    public function printpvfailuresAction()
    {
         
       $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromRoute(); 
            $id = $data["id"];
            $semID = $data["semID"];
            $classeID = $data["classID"];
            
            $ue = null;
            $grode_of_failures = [];
            $classe =  $this->entityManager->getRepository(ClassOfStudy::class)->find($classeID);
            if($classe->getCycle()->getCycleLevel() == 1)  $grode_of_failures = ["F","E","D","D+","C-"];
            else $grode_of_failures = ["F","E","D","D+","C-","C","C+"];            
            // retrieve the sutdent ID based on the student ID 
            $std1 = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$id,"status"=>1,"idSubject"=>[NULL," "]),array("nom"=>"ASC"));
            $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$id,"status"=>1,"idSubject"=>[NULL," "],"grade"=>$grode_of_failures),array("nom"=>"ASC")); 
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());
            if($std)
            {
                foreach($std as $key=>$value)
                {
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $std[$key] = $data;

                }

                $ue =  $this->entityManager->getRepository(TeachingUnit::class)->find($id);
            }
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             
            
            $sem =  $this->entityManager->getRepository(Semester::class)->find($semID);
            $subjects = $this->examManager->getSubjectFromUe($id,$sem->getId(),$classe->getId(),$this->crtAcdYr);

            $semestre = $sem->getCode();
            //$classe = $classe->getClassOfStudy();
            
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            //$classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $acadYr = $acadYr->getCode();
            
            //List of exam performed for the given subject
            $exams = $this->examManager->getExamWithMarkRegistered($id,$semID,$classeID,$this->crtAcdYr);
            $school =  $this->entityManager->getRepository(School::class)->findAll()[0];
            
            //compute statistics
            $totalStudent = sizeof($std1);
            $totalFailure = sizeof($std);

            $this->entityManager->getConnection()->commit();
            
           $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
            $view = new ViewModel([
                'school'=>$school->getName(),
                'logo'=>$school->getLogo(),
                'brandInfo'=>$brandInfo,
                'students'=>$std,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe->getCode(),
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'subjects'=>$subjects,
                'exams'=>$exams,
                'totalStudent'=>$totalStudent,
                'totalFailure'=>$totalFailure,
                'cycle_level'=>$classe->getCycle()->getCycleLevel()
            ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);
            return $view;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         


    }

    public function printSubjectMarkReportAction()
    {

        $this->entityManager->getConnection()->beginTransaction();
         try
         { 
             $data = $this->params()->fromRoute();             
             $ueID = $data["ueID"];
             $subjectID = $data["subjectID"];
             $semID = $data["semID"];
             $classeID = $data["classID"];
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data["classID"]);
            $grode_of_failures = ["F","E"];

          //  if($classe->getCycle()->getCycleLevel() == 1)  $grode_of_failures = ["F","E","D","D+","C-"];
          //  else $grode_of_failures = ["F","E","D","D+","C-","C","C+"];             

             $ue = null;
             // retrieve the sutdent ID based on the student ID 
             $std1 = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$ueID,"status"=>1,"idSubject"=>$subjectID),array("nom"=>"ASC"));
             $std = $this->entityManager->getRepository(SubjectRegistrationView::class)->findBy(array("idUe"=>$ueID,"status"=>1,"idSubject"=>$subjectID,"grade"=>$grode_of_failures),array("nom"=>"ASC")); 
            // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());
             if($std1)
             {
                 foreach($std1 as $key=>$value)
                 {
                     $hydrator = new ReflectionHydrator();
                     $data = $hydrator->extract($value);
                     $std1[$key] = $data;

                 }

                 
             }
             $ue =  $this->entityManager->getRepository(TeachingUnit::class)->find($ueID);
             $subject = $this->entityManager->getRepository(Subject::class)->find($subjectID);
             $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             $school =  $this->entityManager->getRepository(School::class)->findAll()[0]; 
             
            // $classe =  $this->entityManager->getRepository(ClassOfStudy::class)->find($classeID);
             $sem =  $this->entityManager->getRepository(Semester::class)->find($semID);
             $subjects = $this->examManager->getSubjectFromUe($ueID,$sem->getId(),$classe->getId());

             $semestre = $sem->getCode();
             //$classe = $classe->getClassOfStudy();

             $diplome = $classe->getDegree();
             $filiere = $diplome->getFieldStudy();
             $faculty = $filiere->getFaculty();

             //$classe = $classe->getCode();
             $diplome = $diplome->getName();
             $filiere = $filiere->getName();
             $faculty = $faculty->getName();
             $acadYr = $acadYr->getCode();
             $logo = $school->getLogo();
             $school = $school->getName();
             

             //List of exam performed for the given subject
             $exams = $this->examManager->getExamList($ueID,$subjectID,$semID,$classeID);

             //compute statistics*
             if($std1) $totalStudent = sizeof($std1); else $totalStudent = 1;
             $totalFailure = sizeof($std);

             $this->entityManager->getConnection()->commit();

             $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
             $view = new ViewModel([
                 'module'=>$ue->getName(),
                 'subjectCode'=>$subject->getSubjectCode(),
                 'subjectName'=>$subject->getSubjectName(),
                 'students'=>$std1,
                 'acadYr'=>$acadYr,
                 'semestre'=>$semestre,
                 'classe'=>$classe->getCode(),
                 'diplome'=>$diplome,
                 'filiere'=>$filiere,
                 'faculty'=>$faculty,
                 'school'=>$school,
                 'logo'=>$logo,
                 'brandInfo'=>$brandInfo,
                 'subjects'=>$subjects,
                 'exams'=>$exams,
                 'totalStudent'=>$totalStudent,
                 'totalFailure'=>$totalFailure,
                 'cycle_level'=>$classe->getCycle()->getCycleLevel()
             ]);
             // Disable layouts; `MvcEvent` will use this View Model instead
             $view->setTerminal(true);
             return $view;       

         }
         catch(Exception $e)
         {
            $this->entityManager->getConnection()->rollBack();
             throw $e;

         }         

    }    
    
 public function printModuleMarkReportAction()
    {

        $this->entityManager->getConnection()->beginTransaction();
         try
         { 
             $data = $this->params()->fromRoute();             
             $subjects = [];
            $classe= $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data["classID"]);
            $grode_of_failures = ["F","E"];

           // if($classe->getCycle()->getCycleLevel() == 1)  $grode_of_failures = ["F","E","D","D+","C-"];
           // else $grode_of_failures = ["F","E","D","D+","C-","C","C+"];             
            $semester = $this->entityManager->getRepository(Semester::class)->findOneById($data["semID"]);
            $ue = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($data["ueID"]);
            $credits = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array('teachingUnit'=>$ue,'semester'=>$semester,"classOfStudy"=>$classe))->getCredits();
            
            $stdRegisteredToModule = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"semester"=>$semester,"subject"=>[NULL," "]));
            $stdRegisteredToModuleFailed = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("teachingUnit"=>$ue,"semester"=>$semester,"subject"=>[NULL," "],"grade"=>$grode_of_failures));
            $stdOutput = [];
            $i=0;
            //List of teaching unit of the classe
            foreach( $stdRegisteredToModule as $std)
            {
                $subjects = $this->examManager->getSubjectFromUe($ue->getId(), $semester->getId(), $classe->getId(),$this->crtAcdYr->getId());
                $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
                $stdAdminReg = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std->getStudent(),"academicYear"=>$acadYr));
                
                if($stdAdminReg->getStatus()==1)
                {
                
                    $stdOutput[$i]["Matricule"] = $std->getStudent()->getMatricule();
                    $stdOutput[$i]["Nom"] = $std->getStudent()->getNom().' '.$std->getStudent()->getPrenom();


                    foreach($subjects as $sub)
                    {
                        $subject = $this->entityManager->getRepository(Subject::class)->find($sub["id"]);
                        $stdRegisteredToSubject = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("teachingUnit"=>$ue,"semester"=>$semester,"subject"=>$subject, "student"=>$std->getStudent() ));
                        if($stdRegisteredToSubject)
                            $stdOutput[$i][$sub["subjectCode"]." [".$sub["subjectWeight"]."]"] = $stdRegisteredToSubject->getNoteFinal();
                        else  $stdOutput[$i][$sub["subjectCode"]." [".$sub["subjectWeight"]."]"]= NULL;
                    }  
                    $stdOutput[$i]["Note"] = $std->getNoteFinal();
                    $stdOutput[$i]["Grade"] = $std->getGrade();
                    $stdOutput[$i]["Points"] = $std->getPoints(); 
                    $i++;
                }
            }


             $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             $school =  $this->entityManager->getRepository(School::class)->findAll()[0]; 
             


             $semestre = $semester->getCode();
             //$classe = $classe->getClassOfStudy();

             $diplome = $classe->getDegree();
             $filiere = $diplome->getFieldStudy();
             $faculty = $filiere->getFaculty();

             //$classe = $classe->getCode();
             $diplome = $diplome->getName();
             $filiere = $filiere->getName();
             $faculty = $faculty->getName();
             $acadYr = $acadYr->getCode();
             $logo = $school->getLogo();
             $school = $school->getName();
             
            //Sorting the $std array according to the key "nom"
            $tmp = Array();
            foreach($stdOutput as &$ma)
                $tmp[] = &$ma["Nom"];
            array_multisort($tmp, $stdOutput);

             //compute statistics
             $totalStudent = sizeof($stdRegisteredToModule);
             $totalFailure = sizeof($stdRegisteredToModuleFailed);

             $this->entityManager->getConnection()->commit();
             $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";

             $view = new ViewModel([
                 'module'=>$ue->getName(),
                 'codeUe'=>$ue->getCode(),
                 'credits'=>$credits,
                 'subjectName'=>$subject->getSubjectName(),
                 'students'=>$stdOutput,
                 'acadYr'=>$acadYr,
                 'semestre'=>$semestre,
                 'classe'=>$classe->getCode(),
                 'diplome'=>$diplome,
                 'filiere'=>$filiere,
                 'faculty'=>$faculty,
                 'school'=>$school,
                 'logo'=>$logo,
                 'brandInfo'=>$brandInfo,
                 'subjects'=>$subjects,
                 'totalStudent'=>$totalStudent,
                 'totalFailure'=>$totalFailure,
                 'cycle_level'=>$classe->getCycle()->getCycleLevel()
             ]);
             // Disable layouts; `MvcEvent` will use this View Model instead
             $view->setTerminal(true);
             return $view;       

         }
         catch(Exception $e)
         {
            $this->entityManager->getConnection()->rollBack();
             throw $e;

         }         

    }        
    
public function printmpsAction()
{
         
         $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $classe_code= $this->params()->fromRoute('classe_code', -1); 
            $sem_id = $this->params()->fromRoute('sem_id', -1); 
            //Retrieve all student registered to the given classe
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("class"=>$classe_code,"status"=>1));
            $i= 0;
            $j=0;
            $r=0;
            $students = [];
            $studentsWithBaclogs = [];
            $sem = $this->entityManager->getRepository(Semester::class)->findOneById($sem_id);
            $classe_1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            //returns the list of all backlos subject for the given class
            $this->backlogs = $this->backlogsSubjectList($registeredStd,$sem,$classe_1);
            //returns the list of student having backlogs
            $backlogStudents = $this->backlogsStudentList($registeredStd, $sem, $classe_1);

            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                
                $this->getStudentMps($sem,$std,$classe, $students,$j);

             } 
            foreach($backlogStudents as $std)
            {

                       // $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));  
                       // $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));
                        $this->getStudentBacklogsMarks($backlogStudents,$sem,$classe_1,$std,$studentsWithBaclogs,$r);
                    //}
            }                    

            
     
            
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             
      
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            $classe = $classe_1->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $semestre = $sem->getCode();
            $acadYr = $acadYr->getCode();
            $subjetcs = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findByClasse($classe);
            
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $view = new ViewModel([
                'students'=>$students,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'studentsWithBaclogs'=>$studentsWithBaclogs,
                'filiere'=>$filiere,
                'faculty'=>$faculty
            ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

}

public function printdetailedmpsAction()
{
         
         $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $classe_code= $this->params()->fromRoute('classe_code', -1); 
            $sem_id = $this->params()->fromRoute('sem_id', -1); 
            $printingOption = $this->params()->fromRoute('printingOption', -1);
            
            //Retrieve all student registered to the given classe
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe_code,"acadYrId"=>$this->crtAcdYr->getId(),"status"=>1));
            $i= 0;
            $j=0;
            $r=0;
            $students = [];
            $studentsWithBaclogs = [];
            $sem = $this->entityManager->getRepository(Semester::class)->findOneById($sem_id);
            $classe_1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            //returns the list of all backlos subject for the given class
            $this->backlogs = $this->backlogsSubjectList($registeredStd,$sem,$classe_1,$this->crtAcdYr->getId());
            //returns the list of student having backlogs
            $backlogStudents = $this->backlogsStudentList($registeredStd, $sem, $classe_1,$this->crtAcdYr->getId());

            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                
                $this->getStudentMps($sem,$std,$classe_1, $students,$j,$this->crtAcdYr->getId());

             } 
            foreach($backlogStudents as $std)
            {

                       // $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));  
                       // $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));
                        $this->getStudentBacklogsMarks($backlogStudents,$sem,$classe_1,$std,$studentsWithBaclogs,$r,$this->crtAcdYr->getId());
                    //}
            }                    

            
     
            
            $acadYr = $this->crtAcdYr; 
             

           
            $diplome = $classe_1->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            $cycle = $classe_1->getCycle()->getCycleLevel();
            $niveauEtude = $classe_1->getStudyLevel();
            
            $cycle =  $classe_1->getCycle()->getCycleLevel();
            $classe = $classe_1->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $semestre = $sem->getName();
            $semRanking = $sem->getRanking();
            $acadYr = $acadYr->getCode();
            
            $subjects = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("classe"=>$classe,"semId"=>$sem_id,"acadYrId"=>$this->crtAcdYr->getId()));
            foreach($subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $subjects[$key] = $data;

            }
            
            $grade = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe_1->getGrade());
            foreach($grade as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $grade[$key] = $data;

            } 
            
            $msge = "";
            if(($niveauEtude==1)) $msge="AUCUNE ADMISSION EN L2 N'EST AUTORISEE AVEC MOINS DE 50% DE CREDITS ANNUELS CAPITALISES";
            if(($niveauEtude==2)) $msge="AUCUNE ADMISSION EN L3 N'EST AUTORISEE AVEC MOINS DE 50% DE CREDITS ANNUELS CAPITALISES";
                   // . " ET LA TOTALITE DES CREDITS DE L1 VALIDE";
            if(($niveauEtude==3)) $msge="AUCUN CHANGEMENT DE CYCLE N'EST AUTORISE AVEC DES UNITES D'ENSEIGNEMENT NON VALIDEES";
            if(($niveauEtude==4)) $msge="AUCUNE ADMISSION EN MASTER 2 N'EST AUTORISEE AVEC MOINS DE 60% DE CREDITS ANNUELS CAPITALISES";
            if(($niveauEtude==5)) $msge="AUCUN CHANGEMENT DE CYCLE N'EST AUTORISE AVEC DES UNITES D'ENSEIGNEMENT NON VALIDEES";

// $semRanking = $this->computeSemRanking($semRanking);

            $this->entityManager->getConnection()->commit();
            
           /* switch($printingOption)
            {
                case 1: function invenDescSort($item1,$item2)
                        {
                            if ($item1['MPC'] == $item2['MPC']) return 0;
                            return ($item1['MPC'] < $item2['MPC']) ? 1 : -1;
                        }
                        usort($students,'invenDescSort');
                       

            }*/
            
            //$output = json_encode($output,$depth=1000000); 
            $view = new ViewModel([
                'students'=>$students,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'studentsWithBaclogs'=>$studentsWithBaclogs,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'semRanking'=>$semRanking,
                'subjects'=>$subjects,
                'grade'=>$grade,
                'cycle_level'=>$cycle,
                'msge'=>$msge
            ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

}

public function printFinalYrMpsAction()
{
         
         $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $classe_code= $this->params()->fromRoute('classe_code', -1); 
            $sem_id = $this->params()->fromRoute('sem_id', -1); 
            //Retrieve all student registered to the given classe
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe_code,"acadYrId"=>$this->crtAcdYr->getId(),"status"=>1));
            $i= 0;
            $j=0;
            $r=0;
            $students = [];
            $studentsWithBaclogs = [];
            $sem = $this->entityManager->getRepository(Semester::class)->findOneById($sem_id);
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            //returns the list of all backlos subject for the given class
            $this->backlogs = $this->backlogsSubjectList($registeredStd,$sem,$classe);
            //returns the list of student having backlogs
            $backlogStudents = $this->backlogsStudentList($registeredStd, $sem, $classe);

            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $std = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                
                $this->getStudentMps($sem,$std,$classe, $students,$j);

             } 
            foreach($backlogStudents as $std)
            {

                       // $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));  
                       // $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));
                        $this->getStudentBacklogsMarks($backlogStudents,$sem,$classe,$std,$studentsWithBaclogs,$r);
                    //}
            }                    

            
     
            
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             

           
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            $classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $semestre = $sem->getCode();
            $semRanking = $sem->getRanking();
            $acadYr = $acadYr->getCode();
            $semRanking = $this->computeSemRanking($semRanking);

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $view = new ViewModel([
                'students'=>$students,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'studentsWithBaclogs'=>$studentsWithBaclogs,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'semRanking'=>$semRanking,
             
            ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

}
    public function printstudentlistAction()
    {
         
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $id = $this->params()->fromRoute('exam_id', -1); 
            $ue = null;
            $std = [];
            // retrieve the sutdent ID based on the student ID 
            $exam = $this->entityManager->getRepository(Exam::class)->findOneById($id); 
            $exam_registration = $this->entityManager->getRepository(ExamRegistration::class)->findBy(array("exam"=>$exam,"attendance"=>"P"));
           // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());
            if($exam_registration)
            {
                $i=0;
                foreach($exam_registration as $key=>$value)
                {
                    

                    $std[$i]['N°'] = $value->getStudent()->getMatricule();
                    $std[$i]['matricule'] = $value->getStudent()->getMatricule();
                    $std[$i]['nom et prenom'] = $value->getStudent()->getNom().' '.$value->getStudent()->getPrenom(); 
                    //$std[$i]['prenom'] = $value->getStudent()->getPrenom(); 
                   /* $std[$i]['intercalaires'] = "Intercalaires"; 
                    $std[$i]['signatures'] = "Signatures"; 
                    $std[$i]['notes'] = "Notes"; */
                    $i++;
                   

                }
               

               
            }
            
            $acadYr =  $this->crtAcdYr; 
             
            $classe =  $exam->getClassOfStudyHasSemester()->getClassOfStudy();
 
            if($exam->getClassOfStudyHasSemester()->getTeachingUnit())
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getCode();
                $nomUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getName();
                
            }
            else
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectCode(); 
                $nomUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectName();
            }
            $semestre = $exam->getClassOfStudyHasSemester()->getSemester()->getCode();
            $school =  $this->entityManager->getRepository(School::class)->findAll()[0]; 
            $date = $exam->getDate();
            //$date = new \DateTime($date);
            $date = $date->format('d/m/Y');
            $typeExam = $exam->getType();
            
            
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            $classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $acadYr = $acadYr->getCode();
            $codeExam = $exam->getCode();
            

            $this->entityManager->getConnection()->commit();
            $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
            
           
            $view = new ViewModel([
                'students'=>$std,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'codeUe'=>$codeUe,
                'nomUe'=>$nomUe,
                'date'=>$date,
                'logo'=>$school->getLogo(),
                'school'=>$school->getName(),
                'typeExam'=>$typeExam,
                'codeExam'=>$codeExam,
                'schoolName'=>$school->getName(),
                'brandInfo'=>$brandInfo
            ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);
            return $view;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         

    }
   
    public function printnotesAction()
    {
         
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $id = $this->params()->fromRoute('exam_id', -1); 
            $ue = null;
            $std = [];
            // retrieve the sutdent ID based on the student ID 
            $exam = $this->entityManager->getRepository(Exam::class)->findOneById($id); 
            //$exam_registration = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam);
            $exam_registration = $this->entityManager->getRepository(StudentExamRegistrationView::class)->findBy(array("codeExam"=>$exam->getCode(),"status"=>1,"attendance"=>"P","acadYrId"=>$this->crtAcdYr->getId()));
            if($exam_registration)
            {
                $i=0;
                foreach($exam_registration as $key=>$value)
                {
                    
                    $std[$i]['num'] = 0;
                    $std[$i]['matricule'] = $value->getMatricule(); 
                    $std[$i]['nom'] = $value->getNom(); 
                   // $std[$i]['prenom'] = $value->getStudent()->getPrenom(); 
                    $std[$i]['note'] = $this->examManager->getMark($exam,$value);
                    $i++;
                   

                }
               
                for($i=0;$i<sizeof($std);$i++)
                {
                    //$std[$i]['nom']= utf8_encode($std[$i]['nom']);
                    //$std[$i]['prenom']= utf8_encode($std[$i]['prenom']);

                }
               
            }
            
            $acadYr =  $this->crtAcdYr; 
             
            $classe =  $exam->getClassOfStudyHasSemester()->getClassOfStudy();
          
            if($exam->getClassOfStudyHasSemester()->getTeachingUnit())
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getCode();
                $nomUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getName();
                
            }
            else
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectCode(); 
                $nomUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectName();
            }
            $semestre = $exam->getClassOfStudyHasSemester()->getSemester()->getCode();
            $school =  $this->entityManager->getRepository(School::class)->findAll()[0];
            $date = $exam->getDate();
            //$date = new \DateTime($date);
            $date = $date->format('d/m/Y');
            $typeExam = $exam->getType();
            
            
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            $classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $acadYr = $acadYr->getCode();
            $codeExam = $exam->getCode();
            

            $this->entityManager->getConnection()->commit();
            $brandInfo = "Report generated with UdMAcademy By W-TECH(" . date("d-m-Y H:i") .")";
            
           
            $view = new ViewModel([
                'students'=>$std,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'codeUe'=>$codeUe,
                'nomUe'=>$nomUe,
                'date'=>$date,
                'typeExam'=>$typeExam,
                'codeExam'=>$codeExam,
                'logo'=>$school->getLogo(),
                'school'=>$school->getName(),
                'brandInfo'=>$brandInfo
            ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);
            return $view;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         

    }
    public function printexametiquetteAction()
    {
         
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $id = $this->params()->fromRoute('exam_id', -1); 
            $ue = null;
            $std = [];
            // retrieve the sutdent ID based on the student ID 
            $exam = $this->entityManager->getRepository(Exam::class)->findOneById($id); 
            $exam_registration = $this->entityManager->getRepository(ExamRegistration::class)->findBy(array("exam"=>$exam,"attendance"=>"P"));
           
            $nbreCopies = sizeof($exam_registration );            // $std_registered_subjects = $this->entityManager->getRepository(SubjectRegistrationView::class)->findByStudentId($std->getStudentId());
            if($exam_registration)
            {
                $i=0;
                foreach($exam_registration as $key=>$value)
                {
                    

                    $std[$i]['matricule'] = $value->getStudent()->getMatricule(); 
                    $std[$i]['nom'] = $value->getStudent()->getNom(); 
                    $std[$i]['prenom'] = $value->getStudent()->getPrenom(); 
                    $std[$i]['note'] = $this->examManager->getMark($exam,$value);
                    $i++;
                   

                }
               
                for($i=0;$i<sizeof($std);$i++)
                {
                    //$std[$i]['nom']= utf8_encode($std[$i]['nom']);
                    //$std[$i]['prenom']= utf8_encode($std[$i]['prenom']);

                }
               
            }
            
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1)); 
             
            $classe =  $exam->getClassOfStudyHasSemester()->getClassOfStudy();
            if($exam->getClassOfStudyHasSemester()->getTeachingUnit())
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getCode();
                $nomUe = $exam->getClassOfStudyHasSemester()->getTeachingUnit()->getName();
                
            }
            else
            {
                $codeUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectCode(); 
                $nomUe = $exam->getClassOfStudyHasSemester()->getSubject()->getSubjectName();
            }
            $semestre = $exam->getClassOfStudyHasSemester()->getSemester()->getCode();
            $date = $exam->getDate();
            //$date = new \DateTime($date);
            $date = $date->format('d/m/Y');
            $typeExam = $exam->getType();

            $grade = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe->getGrade());
            foreach($grade as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $grade[$key] = $data;

            }            
            
            $diplome = $classe->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            
            $classe = $classe->getCode();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $faculty = $faculty->getName();
            $acadYr = $acadYr->getCode();
            $codeExam = $exam->getCode();
            
           
            $this->entityManager->getConnection()->commit();
            
           
            $view = new ViewModel([
                'students'=>$std,
                'acadYr'=>$acadYr,
                'semestre'=>$semestre,
                'classe'=>$classe,
                'diplome'=>$diplome,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'codeUe'=>$codeUe,
                'nomUe'=>$nomUe,
                'date'=>$date,
                'typeExam'=>$typeExam,
                'codeExam'=>$codeExam,
                'grade'=>$grade,
                'nbreCopies'=>$nbreCopies 
            ]);
            // Disable layouts; `MvcEvent` will use this View Model instead
            $view->setTerminal(true);
            return $view;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         

    }  
    
public function printTranscriptsAction()
{
         
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $classe_code= $this->params()->fromRoute('classe_code', -1); 
            $sem_id = $this->params()->fromRoute('sem_id', -1); 
            $acadYrId = $this->params()->fromRoute('acadYrId', -1); 
            $stdId = $this->params()->fromRoute('stdId', -1);
            $duplicata = $this->params()->fromRoute('duplicata', -1);
          
            if($duplicata==-1) $duplicata = 0;

            //Retrieve all student registered to the given classe
            if($stdId==-1 || $stdId==0 || $stdId==1) $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe_code,"yearID"=>$acadYrId,"status"=>[1,6,7]));
            else $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("studentId"=>$stdId,"yearID"=>$acadYrId,"status"=>[1,6,7]));
            
            if($acadYrId!=-1)
            {           
                $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->find($acadYrId);
                 
            }
            else
            {
                $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
               
            }
        
            $students = [];
            $studentsWithBaclogs = [];
            $sem = $this->entityManager->getRepository(Semester::class)->find($sem_id);
            $classe_1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            $studyLevel = $classe_1->getStudyLevel();
            
            //$today = new \DateTime(date('Y-m-d'));
            $i=0;
            
            foreach($registeredStd as $key=>$value1)
            {
                
                $std = [];
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value1);
               
                $stud = $this->entityManager->getRepository(Student::class)->find($value1->getStudentId());
                
                    $std['nom'] = $data['nom'].' '.$data['prenom'];
                    $std['matricule'] = $data['matricule'];
                    $std['bornAt'] = $data['bornPlace'];
                    $std['dateOfBirth']= $data['dateNaissance']->format('d/m/Y'); 
                    
               
                $semesters = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$classe_1,"academicYear"=>$acadYr)); 
                $recapN0 = [];
                foreach($semesters as $sem_1)
                {
                    $stdSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$stud,"semester"=>$sem_1->getSemester()));
                    if($stdSemRegistration)
                        if($sem_1->getSemester()->getRanking()%2<>0)
                        {
                            $recapN0["referenceId"] = $stdSemRegistration->getTranscriptReferenceId();
                            $recapN0["mpc"]= $stdSemRegistration->getMpcPrevious();
                            $recapN0["TCC"]=$stdSemRegistration->getNbCredtisCapitalizedPrevious();
                            
                            $recapN["referenceId"] = $stdSemRegistration->getTranscriptReferenceId();
                            $recapN["TCI"]= $stdSemRegistration->getTotalCreditRegisteredCurrentSem();
                            $recapN["TCC"]=$stdSemRegistration->getNbCreditsCapitalizedCurrentSem(); 
                            $recapN["mpc"]= $stdSemRegistration->getMpcCurrentSem();
                            $recapN["mps"]=$stdSemRegistration->getMpsCurrentSem(); 
                            $recapN["semRank"] = $sem_1->getSemester()->getRanking();

                            $coursesN["courses"] = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("studentId"=>$value1->getStudentId(),"semID"=>$sem_1->getSemester()->getId()));
                             
                            foreach ($coursesN["courses"]  as $key=>$value)
                            {
                                $value = $hydrator->extract($value);
                                $value["date"] = $sem_1->getSemester()->getEndingDate()->format('M-y');
                                $value["semRank"] = $sem_1->getSemester()->getRanking();
                                $coursesN["courses"] [$key] = $value;

                            }
                            $coursesN["backlogs"] = $this->studentBacklogstList($stud,$acadYr,$sem_1->getSemester());
                            $coursesN["semRanking"] = $sem_1->getSemester()->getRanking();
                        }
                        else
                        {
                            $recapN1["referenceId"] = $stdSemRegistration->getTranscriptReferenceId();
                            $recapN1["TCI"]= $stdSemRegistration->getTotalCreditRegisteredCurrentSem();
                            $recapN1["TCC"]=$stdSemRegistration->getNbCreditsCapitalizedCurrentSem(); 
                            $recapN1["mpc"]= $stdSemRegistration->getMpcCurrentSem();
                            $recapN1["mps"]=$stdSemRegistration->getMpsCurrentSem(); 
                            $recapN1["TV"]=$stdSemRegistration->getNbCreditsCapitalizedCurrentSem()+$stdSemRegistration->getNbCredtisCapitalizedPrevious(); 
                            $recapN1["validation"]=$stdSemRegistration->getValidationPercentage();
                            $recapN1["semRank"] = $sem_1->getSemester()->getRanking();
                            $mention = $stdSemRegistration->getAcademicProfile();
                            
                            $coursesN1["courses"] = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("studentId"=>$value1->getStudentId(),"semID"=>$sem_1->getSemester()->getId()));
                           
                            foreach ($coursesN1["courses"] as $key=>$value)
                            {
                                $value = $hydrator->extract($value);
                                $value["date"] = $sem_1->getSemester()->getEndingDate()->format('M-y');
                                $value["semRank"] = $sem_1->getSemester()->getRanking();
                                $coursesN1["courses"][$key] = $value;
                            }
                            $coursesN1["backlogs"] =  $this->studentBacklogstList($stud,$acadYr,$sem_1->getSemester());
                            $coursesN1["semRanking"] = $sem_1->getSemester()->getRanking();

                        }
                    
                    
                }
                         
                

                $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$stud,"academicYear"=>$acadYr));
                if($adminRegistration)
                    $decision = $adminRegistration->getDecision();
                else $decision = "RAS";
                
                if($stdSemRegistration)
                    $stdSemRegistration = $hydrator->extract($stdSemRegistration);
                else{ 
                    $recapN0=[];$recapN1=[];$recapN=[];$recapN0=[];$coursesN1=[];$coursesN =[];
                    $mention = "";
                    
                }
                $i++;

                $students[$i]=array("student"=>$std,"recapN0"=>$recapN0,"coursesN"=>$coursesN,"recapN"=>$recapN,"coursesN1"=>$coursesN1,"recapN1"=>$recapN1,"decision"=>$decision,"mention"=>$mention);
           
             

             } 

            $diplome = $classe_1->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            $personOnChargeOfFaculty = $faculty->getResponsable();
            $cycle = $classe_1->getCycle()->getCycleLevel();
            $niveauEtude = $classe_1->getStudyLevel();
            
            $classe = $classe_1->getCode();
           // $specialite = $classe_1->getOption();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $facultyCode = $faculty->getCode();
            $faculty = $faculty->getName();
            $semestre = $sem->getName();
            $semRank =  $sem->getRanking();
            $semRanking = $sem->getRanking();
            $acadYrCode = $acadYr->getCode();
            $acadYr = $acadYr->getName();
            
            $subjects = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("classe"=>$classe,"semId"=>$sem_id));
            foreach($subjects as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $subjects[$key] = $data;

            }
            
            $grade = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($classe_1->getGrade());
            foreach($grade as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $grade[$key] = $data;

            } 

            $this->entityManager->getConnection()->commit();
            

            $view = new ViewModel([
                'students'=>$students,
                'acadYr'=>$acadYr,
                'acadYrCode'=>$acadYrCode,
                'semestre'=>$semestre,
                '$semRank'=>$semRank,
                'classe'=>$classe,
               // 'specialite'=>$specialite,
                'studyLevel'=>$studyLevel,
                'studentsWithBaclogs'=>$studentsWithBaclogs,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'facultyCode'=>$facultyCode,
                'personOnChargeOfFaculty'=>$personOnChargeOfFaculty,
                'semRanking'=>$semRanking,
                'subjects'=>$subjects,
                'grade'=>$grade,
                'diplome'=>$diplome,
                'duplicata'=>$duplicata
                
            ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
            return $view;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

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
    
    public function gradelistAction()
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

        foreach ($reader as $row) {
            $id=$this->studentManager->getRegistrationID(10);
           
            $row_1 = array_slice($row, 0, 4);
            $data = array("matricule"=>$row["matricule"],"classe"=>$row["classe"]);
            $this->studentManager->addStudent($row);
            
            $this->studentManager->stdAdminRegistration($data);
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
    
    private function  getStudentMps($sem,$student,$classe,&$students,&$j,$acadYrId)
    {
         
            
        //Retrive all courses of the class
        $currentYrCourses = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findBy(array("classe"=>$classe->getCode(),"semester"=>$sem->getCode(),"acadYrId"=>$acadYrId),array("id"=>"DESC"));



        $students[$j]["matricule"]= $student->getMatricule();
        $students[$j]["nom"]= $student->getNom().' '.$student->getPrenom();
        $studentsSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$sem));
        $studentsSemRegistration?$students[$j]["MPCPREV"] = $studentsSemRegistration->getMpcPrevious():$students[$j]["MPCPREV"]=NULL;
        $studentsSemRegistration?$students[$j]["CrV"] = $studentsSemRegistration->getNbCredtisCapitalizedPrevious():$students[$j]["CrV"]=NULL;

        
        $total_credits = 0;
        $mps = 0;
        $total_credits_valides = 0;
        $k=0;
        foreach($currentYrCourses as $course)
        {
            $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($course->getTeachingUnitId());
            //check if the student is register to the course
            $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$sem,"teachingUnit"=>$teachingUnit,"subject"=>[null," "]));

            $subjects = $this->entityManager->getRepository(Subject::class)->findByTeachingUnit($teachingUnit);
            //Managing information for 7th and 6th level
            if($classe->getStudyLevel()==7)
            {
                foreach($subjects as $sub)
                { 
                    $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("semester"=>$sem,"subject"=>$sub));
                    $exam = $this->entityManager->getRepository(Exam::class)->findOneBy(array("classOfStudyHasSemester"=>$coshs,"status"=>1));
                    $arr = [];
                    $cpt=0;


                    if($exam)
                    {

                        $examRegistrations = $this->entityManager->getRepository(ExamRegistration::class)->findByExam($exam);
                        foreach($examRegistrations as $examR)
                        if($student->getMatricule()==$examR->getStudent()->getMatricule())
                        {

                            ($examR)?$arr[$cpt]=$students[$j][$sub->getSubjectName()]=$this->getMark($exam, $examR):$students[$j][$sub->getSubjectCode()]=NULL;
                            ($examR)?$students[$j]["note".$k]["grd"] = $this->getMark($exam, $examR):$students[$j]["note".$k]["grd"]=NULL;

                            $cpt++;
                            $k++;

                        }
                    }
                    $this->cptSubjects = $this->cptSubjects+$cpt;    
                } 
            

                if(sizeof($subjects)>0)
                {

                    ($unitRegistration)?$students[$j]["Total sur 200"] = $unitRegistration->getNoteFinal()*2:$students[$j]["(Total sur 200)"]=NULL;
                    ($unitRegistration)?$students[$j]["note".$k]["grd"] = $unitRegistration->getNoteFinal()*2:$students[$j]["note".$k]["grd"]=NULL;
                    $k++;
                    ($unitRegistration)?$students[$j]["Total sur 100"] = $unitRegistration->getNoteFinal():$students[$j]["(Total sur 100)"]=NULL;
                    ($unitRegistration)?$students[$j]["note".$k]["grd"] = $unitRegistration->getNoteFinal():$students[$j]["note".$k]["grd"]=NULL;
                    $k++;
                    ($unitRegistration)?$students[$j]["Total sur 20"] = $unitRegistration->getNoteFinal()/5:$students[$j]["(Total sur 20)"]=NULL;
                    ($unitRegistration)?$students[$j]["note".$k]["grd"] = $unitRegistration->getNoteFinal()/5:$students[$j]["note".$k]["grd"]=NULL;
                    $k++;
                }

                ($unitRegistration)?$students[$j]["Moyenne".$k] = number_format(($unitRegistration->getNoteFinal()),1)."%":$students[$j]["Moyenne"]=NULL;
                //Grade
                ($unitRegistration)?$students[$j]["note".$k]["grd"] = number_format(($unitRegistration->getNoteFinal()),1)."%":$students[$j]["note".$k]["grd"]=NULL;
                $k++;  
                //Grade
                ($unitRegistration)?$students[$j]["note".$k]["grd"] = $unitRegistration->getGrade():$students[$j]["note".$k]["grd"]=NULL;
                $k++;  
                ($unitRegistration)?$students[$j]["Grade".$k] = $unitRegistration->getGrade():$students[$j]["Grade"]=NULL;
            }
            else
            {
                ($unitRegistration)?$students[$j][$course->getCodeUe()] = $unitRegistration->getGrade():$students[$j][$course->getCodeUe()]=NULL;
                 $students[$j]["credits".$k] = $course->getCredits();
                //Grade
                ($unitRegistration)?$students[$j]["note".$k]["grd"] = $unitRegistration->getGrade():$students[$j]["note".$k]["grd"]=NULL;
                //Moyenne en pourcentage
                ($unitRegistration)?$students[$j]["note".$k]["moy"] = ($unitRegistration->getNoteFinal())."%":$students[$j]["note".$k]["moy"]=NULL;

                $k++;         
            }
                
        }
        

            $studentsSemRegistration = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$sem));
            $studentsAdminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$student,"academicYear"=>$sem->getAcademicYear()));
            //total ccredit registered for the sem
            $studentsSemRegistration?$students[$j]["TCIS"] = $studentsSemRegistration->getTotalCreditRegisteredCurrentSem():$students[$j]["TCIS"]=NULL;
            //total ccredit cleared for the sem
            $studentsSemRegistration?$students[$j]["TCVS"] = $studentsSemRegistration->getNbCreditsCapitalizedCurrentSem():$students[$j]["TCVS"]=NULL;
            
            //total ccredit registered for the year
            $studentsSemRegistration?$students[$j]["TCIA"] = $studentsSemRegistration->getTotalCreditRegisteredCurrentSem()+$studentsSemRegistration->getTotalCreditRegisteredPreviousSem():$students[$j]["TCIA"]=NULL;
            //total ccredit cleared for the year
            $studentsSemRegistration?$students[$j]["TCVA"] = $studentsSemRegistration->getNbCreditsCapitalizedCurrentSem()+$studentsSemRegistration->getNbCreditsCapitalizedPreviousSem():$students[$j]["TCVA"]=NULL;
            
            //total credit cleared within the cycle
             $studentsSemRegistration?$students[$j]["TCIC"] = $studentsSemRegistration->getTotalCreditRegisteredCurrentCycle():$students[$j]["TCIC"]=NULL;
             $studentsSemRegistration?$students[$j]["TCC"] = $studentsSemRegistration->getNbCreditsCapitalizedCurrentSem()+$studentsSemRegistration->getNbCredtisCapitalizedPrevious():$students[$j]["TCC"]=NULL;
             $studentsSemRegistration?$students[$j]["PERCENT"] = $studentsSemRegistration->getValidationPercentage():$students[$j]["PERCENT"]=NULL;
                  
            $studentsSemRegistration?$students[$j]["MPS"] = $studentsSemRegistration->getMpsCurrentSem():$students[$j]["MPS"]=NULL;  
            $studentsSemRegistration?$students[$j]["MPC"] = $studentsSemRegistration->getMpcCurrentSem():$students[$j]["MPC"]=NULL;

            if($classe->getCode()=="MED7")
            $studentsSemRegistration?$students[$j]["MENTION"] = $this->computeMentionMed7($studentsSemRegistration->getMpcCurrentSem()):$students[$j]["MENTION"]=NULL;
           else
            $studentsSemRegistration?$students[$j]["MENTION"] = $this->computeMention($studentsSemRegistration->getMpcCurrentSem()):$students[$j]["MENTION"]=NULL;   

           $studentsSemRegistration?$students[$j]["DECISION"] = $studentsAdminRegistration->getDecision():$students[$j]["DECISION"]=NULL;
        $j++;
        
            
            
    }
    private function  getStudentBacklogsMarks($registeredStd,$sem,$classe,$student,&$students,&$j,$acadYr)
    {
           


        $students[$j]["matricule"]= $student->getMatricule();
        $students[$j]["nom"]= $student->getNom().' '.$student->getPrenom();
        $flag =false;
        $total_credits = 0;
        $mps = 0;
        $total_credits_valides = 0;
        $k=0;
        if($sem->getRanking()%2==0) $maxRank = $sem->getRanking()-2; else $maxRank = $sem->getRanking()-1; 
        for($rank=1;$rank<=$maxRank;$rank++)
        {          
            if($rank%2==$sem->getRanking()%2)
            {
                //$acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));  
                $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));
                $backlogs = $this->backlogsSubjectPerSem($registeredStd,$semester, $classe);
          
                foreach($backlogs as $key=>$value)
                {

                    //Retrive all courses to which student is registered for a given semester
                    $ue = $this->entityManager->getRepository(TeachingUnit::class)->find($key);
                    $unit = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$semester,"teachingUnit"=>$ue,"subject"=>NULL));
                    if($unit)
                    {
                        $students[$j][$value] = $unit->getGrade();
                        //Grade
                        $students[$j]["note".$k]["grd"] = $unit->getGrade();
                        //Moyenne
                        $students[$j]["note".$k]["moy"] = ($unit->getNoteFinal()*5)."%";
                        $k++;
                     
                     }
                    else{
                        $students[$j][$value] = "";
                        //Grade
                        $students[$j]["note".$k]["grd"] = "";
                        //Moyenne
                        $students[$j]["note".$k]["moy"] = ""; 
                        $k++;
                    }
            }
        }
        }
        $j++;

    } 
    // this function returns the list of all backlogs subject code 
    //It takes as parameter suteents list as well as the current semester
    //looking for  student having backlog and add into the list
    //the search is made only for semester withe same parity. exemple sem1,sem3,sem5 etc...
    private function backlogsSubjectList($registeredStd,$sem, $classe,$acadYr)
    {
        $subjects = [];
        $i=0;
            foreach($registeredStd as $key=>$value)
            {
               
                $std = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                
                if($sem->getRanking()>1)
                { 
                    if($sem->getRanking()%2==0)$maxRank = $sem->getRanking()-2; else $maxRank =$sem->getRanking()-1;
                    for($rank=1;$rank<=$maxRank;$rank++)
                    {
                        if($rank%2==$sem->getRanking()%2)
                        {
 
                        $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));
                        $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$semester,"subject"=>NULL));
                        foreach ($unitRegistration as $unit)
                        {
                            if(!in_array($unit->getTeachingUnit()->getCode(), $subjects))
                            {
                                $subjects[$unit->getTeachingUnit()->getId()] = $unit->getTeachingUnit()->getCode(); 
                                $i++;
                            }
                        }

                        }
                     
                    }
                }
                
            } 
            
            return $subjects;
    }
    
    // this function returns the list of student of current classe having backlogs
    //It takes as parameter suteents list as well as the current semester
    //looking for each student, having backlog subjects and add into the list
    //the search is made only for semester withe same parity. exemple sem1,sem3,sem5 etc...
    private function backlogsStudentList($registeredStd,$sem, $classe,$acadYr)
    {
        $students = [];
        $i=0;
        for($rank=1;$rank<$sem->getRanking()-1;$rank++)
        {
            if($rank%2==$sem->getRanking()%2)
            {
                
                $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));

                foreach($registeredStd as $key=>$value)
                {
                    $student = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                    foreach($this->backlogs as $backId=>$values)
                    { 
 
                        //retrieve course object associated with the above code
                        $unit = $this->entityManager->getRepository(TeachingUnit::class)->find($backId); 
                        //check wether or not current user is registered to this course 
                        $unit = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$semester,"teachingUnit"=>$unit,"subject"=>NULL)); 
                        if($unit)
                        {
                            if(in_array($student, $students)==false)
                            {
                                $students[$i] = $student;
                                $i++; 
                            }
                        } 
                    }
                }
            }
        }

        return $students;
    }
    // this function returns the list of all backlogs subject code 
    //It takes as parameter suteents list as well as the current semester
    //looking for  student having backlog and add into the list
    //the search is made only for semester withe same parity. exemple sem1,sem3,sem5 etc...
    private function backlogsSubjectPerSem($registeredStd,$sem, $classe)
    {
        $subjects = [];
        $i=0;
            foreach($registeredStd as $std)
            {
                //$std = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
                $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$std,"semester"=>$sem,"subject"=>NULL));
                foreach ($unitRegistration as $unit)
                { 
                    if(!in_array($unit->getTeachingUnit()->getCode(), $subjects))
                    {
                        $subjects[$unit->getTeachingUnit()->getId()] = $unit->getTeachingUnit()->getCode(); 
                        $i++;
                    }
                }
             }

            
            return $subjects;
    }
    
    private function computeSemRanking($rank)
    {
        if($rank>1 && $rank<=6) return $rank;
        
        if($rank==9) return 3;
        if($rank==12) return 6;
        return($rank%3);
        
    }
    
    private function computeMention($mpc)
    {
        $profilAcademic = $this->entityManager->getRepository(ProfileAcademic::class)->findAll();
        foreach($profilAcademic as $prof)
        {
            if($mpc>=$prof->getMinval()&& $mpc<=$prof->getMaxval()) return $prof->getGrade();
        }
    }
    private function computeMentionMed7($mpc)
    {
        if(0<=$mpc AND $mpc<=1.99) return null;
        if(2<=$mpc AND $mpc<=2.29) return "Passable";
        if(2.3<=$mpc AND $mpc<=2.99) return "Honorable";
        if(3<=$mpc AND $mpc<=3.69) return "Très honorable";
        if(3.7<=$mpc AND $mpc<=4) return "Très honorable";
    }    
    // for a given studnet, this function returns the list of all backlogs subject code 
    //It takes as parameter student as well as the current semester
    //looking for  student having backlog and add into the list
    //the search is made only for semester withe same parity. exemple sem1,sem3,sem5 etc...
    private function studentBacklogstList($student,$acadYr,$sem)
    {
        $subjects = [];
        $i=0;

        
            $maxSem =$sem->getRanking()-2;
        
        for($rank=1;$rank<=$maxSem;$rank++)
        {

                if($rank%2==$sem->getRanking()%2)
                {   
                    $semester = $this->entityManager->getRepository(Semester::class)->findOneBy(array("academicYear"=>$acadYr,"ranking"=>$rank));

                   $unitRegistration = $this->entityManager->getRepository(AllYearsSubjectRegistrationView::class)->findBy(array("studentId"=>$student->getId(),"semID"=>$semester->getId(),$this->crtAcdYr->getId()));
                   // $unitRegistration = $this->entityManager->getRepository(UnitRegistration::class)->findBy(array("student"=>$student,"semester"=>$semester));
                   
                    foreach ($unitRegistration as $unit)
                    {
                        $hydrator = new ReflectionHydrator();
                        $extractedValue = $hydrator->extract($unit);
                        $extractedValue["date"]=$sem->getEndingDate()->format('M-y');
                        $subjects[$i] = $extractedValue;
                        $i++;
                       
                    }
                     
                }
            


        }

            return $subjects;
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
}
