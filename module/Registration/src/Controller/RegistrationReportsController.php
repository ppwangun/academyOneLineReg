<?php

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\AcademicYear;
use Application\Entity\RegisteredStudentView;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\AllYearsRegisteredStudentView;
use Application\Entity\StudentSemRegistration;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\Semester;
use Application\Entity\AdminRegistration;


class RegistrationReportsController extends AbstractActionController
{

    private $entityManager;
    private $studentManager;
    private $sessionContainer;
    
    public  function __construct($entityManager,$studentManager,$sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->studentManager = $studentManager;
        $this->sessionContainer = $sessionContainer;
 
    }
    
    public function transcriptsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }    
    public function scholarshipCertificatesAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    
    public function printScholarshipCertificatesAction()
    {    
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  

            $classe_code= $this->params()->fromRoute('classe_code', -1); 
            $stdId = $this->params()->fromRoute('stdId', -1);
            $acadYr =  $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            $acadCode = $acadYr->getCode();
            //Retrieve all student registered to the given classe
            if($stdId==-1 || $stdId==0 ) $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("class"=>$classe_code,"status"=>1));
            else $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findBy(array("studentId"=>$stdId,"status"=>1));
            

        
            $students = [];

            $classe_1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            $studyLevel = $classe_1->getStudyLevel();
            $totalStudent = 100;

            $i=0;
            foreach($registeredStd as $key=>$value)
            {
                $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($value->getMatricule());
                $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("academicYear"=>$acadYr,"student"=>$student,"classOfStudy"=>$classe_1));
                $numRef = $adminRegistration->getSchoolCertificateReferenceId();
                
                if($adminRegistration->getSchoolCertificateAvailabilityStatus() ==0 && $adminRegistration->getStatus()==1)
                {
                    //Genereate scholarshi certificate reference ID
                    
                   $totalStudentWithScholarshipCertificate = $this->entityManager->getRepository(AdminRegistration::class)->createQueryBuilder('a')
                    // Filter by some parameter if you want
                    // ->where('a.published = 1')
                    ->select('count(a.id)')
                    ->where('a.schoolCertificateAvailabilityStatus = 1')
                   ->where('a.academicYear = 1')
                    ->getQuery()
                    ->getSingleScalarResult();
                    $ref = $totalStudentWithScholarshipCertificate;
                    $acadCode = substr($acadYr->getCode(),-4); 
                    if($i<10) $id = "R00".$i;
                    elseif($i<100) $id = "R0".$ref ;
                    elseif($id<100) $id = "R".$i;
                    $indice = $totalStudent -$ref ;
                    $numRef = $id."-".$classe_code."-".$indice."-".$acadCode;
                    $adminRegistration->setSchoolCertificateReferenceId($numRef);

                    $i++; 
                    $this->entityManager->flush();
                }

                $hydrator = new ReflectionHydrator();
                $std = $hydrator->extract($value);
                $stud = $this->entityManager->getRepository(Student::class)->findOneById($value->getStudentId());
               
                $std = $hydrator->extract($stud);
                $std['dateOfBirth'] = $std['dateOfBirth']->format('d/m/Y');
                $std['referenceNumber'] = $numRef;
               
                $students[$i]=$std;
                $i++;
             

             } 


            $diplome = $classe_1->getDegree();
            $filiere = $diplome->getFieldStudy();
            $faculty = $filiere->getFaculty();
            $personOnChargeOfFaculty = $faculty->getResponsable();
            $cycle = $classe_1->getCycle()->getCycleLevel();
            $niveauEtude = $classe_1->getStudyLevel();
            
            $classe = $classe_1->getCode();
            //$option = $classe_1->getOption();
            $diplome = $diplome->getName();
            $filiere = $filiere->getName();
            $facultyCode = $faculty->getCode();
            $faculty = $faculty->getName();

            $acadYrCode = $acadYr->getCode();
            $acadYr = $acadYr->getName();


            $this->entityManager->getConnection()->commit();
           

            $view = new ViewModel([
                'students'=>$students,
                'acadYr'=>$acadYr,
                'acadYrCode'=>$acadYrCode,
                'niveauEtude'=>$niveauEtude ,
                'classe'=>$classe,
                'studyLevel'=>$studyLevel,
                '$acadYrCode'=>$acadYrCode,
                'filiere'=>$filiere,
                'faculty'=>$faculty,
                'facultyCode'=>$facultyCode,
                'personOnChargeOfFaculty'=>$personOnChargeOfFaculty,
                'diplome'=>$diplome,
               // 'option'=>$option
               
                
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

    public function generateTranscriptsReferencesAction()
    {    
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $data = $this->params()->fromQuery();
            $classe_code= $data['classe']; 
            $sem_id = $data['sem_id']; 
            $acadYrId = $data['acadYrId'];
            
            $semester = $this->entityManager->getRepository(Semester::class)->find($sem_id);
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->find($acadYrId);
            $semClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("academicYear"=>$acadYr,"semester"=>$semester,"classOfStudy"=>$classe));
            //Retrieve all student registered to the given classe
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe_code,"yearID"=>$acadYrId,"status"=>[1,6,7]));
            $i=1;
            $totalStudent = sizeof($registeredStd);
            
            foreach($registeredStd as $std)
            {
                $student = $this->entityManager->getrepository(Student::class)->find($std->getStudentId());
                $stdSemReg = $this->entityManager->getRepository(StudentSemRegistration::class)->findOneBy(array("student"=>$student,"semester"=>$semester));
                $acadCode = substr($acadYr->getCode(),-4); 
                if($i<10) $id = "R00".$i;
                elseif($i<100) $id = "R0".$i;
                elseif($id<100) $id = "R".$i;
                $indice = $totalStudent -$i;
                
                if($stdSemReg)
                    $stdSemReg->setTranscriptReferenceId($id."-".$classe_code."-".$indice."-".$acadCode);
                
                $i++;
            }
            
            $semClass->setTranscriptReferenceGenerationStatus(1);
            $this->entityManager->flush();

            $data = array("status"=>1);
            $this->entityManager->getConnection()->commit();
            

            $view = new JsonModel([
                $data
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

public function getTranscriptReferenceGenerationStatusAction()
    {    
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $data = $this->params()->fromQuery();
            $classe_code= $data['classe']; 
            $sem_id = $data['sem_id']; 
            $acadYrId = $data['acadYrId'];
            
            $semester = $this->entityManager->getRepository(Semester::class)->find($sem_id);
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($classe_code);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->find($acadYrId);
            //Retrieve all student registered to the given classe
            $registeredStd = $this->entityManager->getRepository(AllYearsRegisteredStudentView::class)->findBy(array("class"=>$classe_code,"yearID"=>$acadYrId,"status"=>[1,6,7]));
            $semClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("academicYear"=>$acadYr,"semester"=>$semester,"classOfStudy"=>$classe));
            
            $this->entityManager->flush();

            $data = array("status"=>$semClass->getTranscriptReferenceGenerationStatus());
            
            $this->entityManager->getConnection()->commit();
            

            $view = new JsonModel([
                $data
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
}

