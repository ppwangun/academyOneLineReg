<?php

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Escaper\Escaper;
use Laminas\Hydrator\ReflectionHydrator as ReflectionHydrator;

use Application\Entity\Faculty;
use Application\Entity\FieldOfStudy;
use Application\Entity\Degree;
use Application\Entity\DegreeHasCourseCategory;
use Application\Entity\CourseCategory;
use Application\Entity\ProspectiveStudent;
use Application\Entity\AcademicYear;
use Application\Entity\ClassOfStudy;
use Application\Entity\CursusAcademique;
use Application\Entity\ProspetiveRegistration;
use Application\Entity\StudentParent;
use Jurosh\PDFMerge\PDFMerger;

class OnlineRegController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;
    
     const PENDING = 0;
     const REJECTED = 2;
     const APPROVED = 1;
    
    public function __construct($entityManager, $sessionContainer) 
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    public function searchFilByFacultyAction()
    {
      $this->entityManager->getConnection()->beginTransaction();
      try
      {
            $data = $this->params()->fromQuery();            
            $fac= $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findBy(array('faculty'=>$fac),array("name"=>"ASC"));
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $data['fac_id'] = $value->getFaculty()->getId();
               ($value->getDepartment())? $data['dpt_id'] = $value->getDepartment()->getId():$data['dpt_id']=-1;
                $filieres[$key] = $data;
            }
        $this->entityManager->getConnection()->commit();
        return new JsonModel([
                $filieres
        ]);          
      }
      catch(Exception $e){
            $this->entityManager->getConnection()->rollBack();
            throw $e; 
      }
        
    }
    
    public function searchDegreeAction()
    {
        $data = $this->params()->fromQuery();
               
        $cycleFormation = $this->entityManager->getRepository(CourseCategory::class)->find($data["cycle_id"]);
        //$filiere = $this->entityManager->getRepository(FieldOfStudy::class)->find($data["fil_id"]);
        $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findBy(array("courseCategory"=>$cycleFormation));
        
        foreach ($degreeCategory as $key=>$value)
        { 
            $degree = $value->getDegree();
            $filiere = $degree->getFieldStudy();
            $school = $filiere->getFaculty();
            
            if($degree)
            {
                
                $data = $this->entityManager->getRepository(Degree::class)->find($degree->getId());
                $hydrator = new ReflectionHydrator();
                $data1 = $hydrator->extract($data); 
                
               $degreeCategory[$key] =  array("id"=>$data->getId(),"name"=>$data->getName(),"code"=>$data->getCode(),"filiere"=>$filiere->getName(),"faculty"=>$school->getName());
                
            }

        }

        return new JsonModel([
                $degreeCategory
        ]);
        
        //return $faculties;
    }   
    
    
   public function searchCycleFormationAction()
    {
        $cycles = $this->entityManager->getRepository(CourseCategory::class)->findAll([],["name"=>"ASC"]);
        
     
        foreach($cycles as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $cycles[$key] = $data;
        }
        
        return new JsonModel([
                $cycles
        ]);
        
        //return $faculties;
    }
    
    public  function submitregistrationformAction()
    {
        $data = $this->params()->fromQuery();
        $studentDetails = json_decode($data["student"],true);
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            //Default academic year
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            
            $pdf = new PDFMerger();
            
            $student = new ProspectiveStudent();
            //collection image and get it compresses
           if($_FILES['img_file'])
           {
                $filename = $_FILES['img_file']['name'][0];   
                $source = $_SERVER['DOCUMENT_ROOT'] .'/img/'.$filename;
                $destination = $_SERVER['DOCUMENT_ROOT'] .'/img/'.$filename;
                $filename ="parfait.pdf";
                // Upload file    
                move_uploaded_file($_FILES['img_file']['tmp_name'][0],$source);  
                $this->imgageCompress($source, $destination, 0.7);
                //covert the image to blob compatible type
                $image_base64 = base64_encode(file_get_contents($destination));
               $student->setPhoto($image_base64);

           }

           
              
      
             
            $escaper = new Escaper("utf-8");   
            
            if(isset($studentDetails["nom"]))   
                $student->setNom($escaper->escapeHtml($studentDetails["nom"]));
            if(isset($studentDetails["prenom"]))
                $student->setPrenom($escaper->escapeHtml($studentDetails["prenom"]));
            if(isset($studentDetails["dateOfBirth"]))
            {
                $date = new \DateTime($escaper->escapeHtml($studentDetails["dateOfBirth"]));
                $student->setDateOfBirth($date);
            }
            if(isset($studentDetails["bornAt"]))
                $student->setBornAt($escaper->escapeHtml($studentDetails["bornAt"]));
            if (isset($studentDetails["phoneNumber"]))
                $student->setPhoneNumber($escaper->escapeHtml($studentDetails["phoneNumber"]));
            if(isset($studentDetails["gender"]))
                $student->setGender($escaper->escapeHtml($studentDetails["gender"]));
            if(isset($studentDetails["email"]))
                $student->setEmail($escaper->escapeHtml($studentDetails["email"]));
            if(isset($studentDetails["regionOfOrigin"]))
                $student->setRegionOfOrigin($escaper->escapeHtml($studentDetails["regionOfOrigin"]));
            if(isset($studentDetails["nationality"]))
                $student->setNationality($escaper->escapeHtml($studentDetails["nationality"]));
            if(isset($studentDetails["religion"]))
                $student->setReligion($escaper->escapeHtml($studentDetails["religion"]));
            if(isset($studentDetails["language"]))
            $student->setLanguage($escaper->escapeHtml($studentDetails["language"]));
            if(isset($studentDetails["maritalStatus"]))
                $student->setMaritalStatus($escaper->escapeHtml($studentDetails["maritalStatus"]));
            if(isset($studentDetails["workingStatus"]))
                $student->setWorkingStatus($escaper->escapeHtml($studentDetails["workingStatus"]));
           // $student->setHandicap((isset($studentDetails["handicap1"])&&!empty($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));
            if(isset($studentDetails["fatherName"]))
                $student->setfatherName($escaper->escapeHtml($studentDetails["fatherName"]));
            if(isset($studentDetails["fatherProfession"]))
                $student->setFatherProfession($escaper->escapeHtml($studentDetails["fatherProfession"]));
            if(isset($studentDetails["fatherPhoneNumber"]))
                $student->setFatherPhoneNumber($escaper->escapeHtml($studentDetails["fatherPhoneNumber"]));
            if(isset($studentDetails["fatherEmail"]))
                $student->setFatherEmail($escaper->escapeHtml($studentDetails["fatherEmail"]));
            if(isset($studentDetails["fatherCountry"]))
                $student->setFatherCountry($escaper->escapeHtml($studentDetails["fatherCountry"]));
            if(isset($studentDetails["fatherCity"]))
                $student->setFatherCity($escaper->escapeHtml($studentDetails["fatherCity"]));
            if(isset($studentDetails["motherName"]))
                $student->setMotherName($escaper->escapeHtml($studentDetails["motherName"]));
            if(isset($studentDetails["motherProfession"]))
                $student->setMotherProfession($escaper->escapeHtml($studentDetails["motherProfession"]));
            if(isset($studentDetails["motherPhoneNumber"]))
                $student->setMotherPhoneNumber($escaper->escapeHtml($studentDetails["motherPhoneNumber"]));
            if(isset($studentDetails["motherEmail"]))
                $student->setMotherEmail($escaper->escapeHtml($studentDetails["motherEmail"]));
            if(isset($studentDetails["motherCountry"]))
                $student->setMotherCountry($escaper->escapeHtml($studentDetails["motherCountry"]));
            if(isset($studentDetails["motherCity"]))
                $student->setMotherCity($escaper->escapeHtml($studentDetails["motherCity"]));

            if(isset($studentDetails["sponsorName"]))
                $student->setSponsorName($escaper->escapeHtml($studentDetails["sponsorName"]));
            if(isset($studentDetails["sponsorProfession"]))
                $student->setSponsorProfession($escaper->escapeHtml($studentDetails["sponsorProfession"]));
            if(isset($studentDetails["sponsorPhoneNumber"]))
                $student->setSponsorPhoneNumber($escaper->escapeHtml($studentDetails["sponsorPhoneNumber"]));
            if(isset($studentDetails["sponsorEmail"]))
                $student->setSponsorEmail($escaper->escapeHtml($studentDetails["sponsorEmail"]));
            if(isset($studentDetails["sponsorCountry"]))
                $student->setSponsorCountry($escaper->escapeHtml($studentDetails["sponsorCountry"]));
            if(isset($studentDetails["sponsorCity"]))
                $student->setSponsorCity($escaper->escapeHtml($studentDetails["sponsorCity"]));
           
         
            
            //$student->setPaymentProofPath($filename);
            

            
            //generating a file number
            $prefixe = date('y');
            $numDossier= $prefixe.'-'.mt_rand(100000, 199999);
            while($this->entityManager->getRepository(ProspetiveRegistration::Class)->findOneByNumDossier($numDossier))
            {
                $numDossier = $prefixe.'-'.mt_rand(10000, 19999);
            }
            
           //Colecting file dans save the  path
           //PDF files are not stored into the data base but instead, just the path is sotred
           //Final user will get access to the file through a link

           foreach ($_FILES as $key=>$file)
           {               //var_dump($file); exit;
               if($key!="img_file")
               {
               $filename = $file["name"][0];  
               $destination = $_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$filename;
               move_uploaded_file($file['tmp_name'][0],$destination);
               $pdf->addPDF($destination,'all');
               }


           }
            $pdf->merge('file',$_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$numDossier.'.pdf');
           //$studentDetails = json_decode($data["student"],true); print_r($studentDetails);
           $this->entityManager->persist($student);
           $this->entityManager->flush();
            $this->sessionContainer->onlineLoggedInUser = ["stdId"=>$student->getId(),"numDossier"=>-1];
            //looking class of study
                        //class of study
            if(isset($studentDetails["choixDeg1"]))
                $degree = $this->entityManager->getRepository(Degree::class)->find($escaper->escapeHtml($studentDetails["choixDeg1"]));
            if(isset($studentDetails["study_level"]))
            {
                $classOfStudy1 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>$studentDetails["study_level"]));
                //$student->setClassOfStudy($classofStudy);
            }           
            if(isset($studentDetails["choixDeg2"]))
                $degree = $this->entityManager->getRepository(Degree::class)->find($escaper->escapeHtml($studentDetails["choixDeg2"]));
            if(isset($studentDetails["study_level"]))
            {
                $classOfStudy2 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>$studentDetails["study_level"]));
                //$student->setClassOfStudy($classofStudy);
            }
            if(isset($studentDetails["choixDeg1"]))
                $degree = $this->entityManager->getRepository(Degree::class)->find($escaper->escapeHtml($studentDetails["choixDeg3"]));
            if(isset($studentDetails["study_level"]))
            {
                $classOfStudy3 = $this->entityManager->getRepository(ClassOfStudy::class)->findOneBy(array("degree"=>$degree,"studyLevel"=>$studentDetails["study_level"]));
                //$student->setClassOfStudy($classofStudy);
            }            
           $prospectiveRegistration = new ProspetiveRegistration();
           
           $prospectiveRegistration->setAcademicYear($academicYear);
           //rospectiveRegistration->setClassOfStudy($classOfStudy);
           $prospectiveRegistration->setProspectiveStudent($student);
           $prospectiveRegistration->setNumDossier($numDossier);
           $prospectiveRegistration->setPaymentProofPath($numDossier.'.pdf');
           $prospectiveRegistration->setChoixFormation1($classOfStudy1->getCode());
           $prospectiveRegistration->setChoixFormation2($classOfStudy2->getCode());
           $prospectiveRegistration->setChoixFormation3($classOfStudy3->getCode());
           $prospectiveRegistration->setRegistrationDecision(self::PENDING);
           $prospectiveRegistration->setStatus(self::PENDING);
          
           $this->entityManager->persist($prospectiveRegistration); 
           $this->entityManager->flush();
           
           $studentFather = new StudentParent();
           $studentFather->setProspectiveStudent($student);
            if(isset($studentDetails["fatherName"]))
                $studentFather->setName($escaper->escapeHtml($studentDetails["fatherName"]));
            if(isset($studentDetails["fatherProfession"]))
                $studentFather->setProfession($escaper->escapeHtml($studentDetails["fatherProfession"]));
            if(isset($studentDetails["fatherPhoneNumber"]))
                $studentFather->setPhoneNumber($escaper->escapeHtml($studentDetails["fatherPhoneNumber"]));
            if(isset($studentDetails["fatherEmail"]))
                $studentFather->setEmail($escaper->escapeHtml($studentDetails["fatherEmail"]));
            if(isset($studentDetails["fatherAdress"]))
                $studentFather->setAdress($escaper->escapeHtml($studentDetails["fatherAdress"]));            
            if(isset($studentDetails["fatherCountry"]))
                $studentFather->setCountry($escaper->escapeHtml($studentDetails["fatherCountry"]));
            if(isset($studentDetails["fatherCity"]))
                //$studentFather->setCity($escaper->escapeHtml($studentDetails["fatherCity"]));
            $studentFather->setParentTye("PERE");
            

           $studentMother = new StudentParent();
           $studentMother->setProspectiveStudent($student);
            if(isset($studentDetails["motherName"]))
                $studentMother->setName($escaper->escapeHtml($studentDetails["motherName"]));
            if(isset($studentDetails["motherProfession"]))
                $studentMother->setProfession($escaper->escapeHtml($studentDetails["motherProfession"]));
            if(isset($studentDetails["motherNumber"]))
                $studentMother->setPhoneNumber($escaper->escapeHtml($studentDetails["motherPhoneNumber"]));
            if(isset($studentDetails["motherEmail"]))
                $studentMother->setEmail($escaper->escapeHtml($studentDetails["motherEmail"]));
            if(isset($studentDetails["motherAdress"]))
                $studentFather->setAdress($escaper->escapeHtml($studentDetails["motherAdress"]));                 
                
            if(isset($studentDetails["motherCountry"]))
                $studentMother->setCountry($escaper->escapeHtml($studentDetails["motherCountry"]));
            if(isset($studentDetails["motherCity"]))
                $studentMother->setCity($escaper->escapeHtml($studentDetails["motherCity"]));
            $studentMother->setParentTye("MERE");
            
            $studentSponsor = new StudentParent();
            $studentSponsor->setProspectiveStudent($student);
            if(isset($studentDetails["sponsorName"]))
                 $studentSponsor->setName($escaper->escapeHtml($studentDetails["sponsorName"]));
            if(isset($studentDetails["sponsorProfession"]))
                 $studentSponsor->setProfession($escaper->escapeHtml($studentDetails["sponsorProfession"]));
            if(isset($studentDetails["sponsorPhoneNumber"]))
                 $studentSponsor->setPhoneNumber($escaper->escapeHtml($studentDetails["sponsorPhoneNumber"]));
            if(isset($studentDetails["sponsorEmail"]))
                 $studentSponsor->setEmail($escaper->escapeHtml($studentDetails["sponsorEmail"]));
            if(isset($studentDetails["sponsorAdress"]))
                $studentFather->setAdress($escaper->escapeHtml($studentDetails["sponsorAdress"]));                
                
            if(isset($studentDetails["sponsorCountry"]))
                 $studentSponsor->setCountry($escaper->escapeHtml($studentDetails["sponsorCountry"]));
            if(isset($studentDetails["sponsorCity"]))
                 $studentSponsor->setCity($escaper->escapeHtml($studentDetails["sponsorCity"]));
            if(isset($studentDetails["sponsorRelationShipWithStd"]))
                 $studentSponsor->setTutorRalationWithStudent($escaper->escapeHtml($studentDetails["sponsorRelationShipWithStd"]));                
            $studentMother->setParentTye("TUTOR");
           
            
            $this->entityManager->persist($studentFather);
            $this->entityManager->persist($studentMother);
            $this->entityManager->persist($studentSponsor);

            
    
            /*$student->setLastSchool($studentDetails["lastSchool"]);
            $student->setEnteringDegree($studentDetails["enteringDegree"]);
            $student->setDegreeId($studentDetails["degreeId"]);

            $student->setDegreeOption($studentDetails["degreeOption"]);
            $student->setDegreeExamCenter($studentDetails["degreeExamCenter"]);
            $student->setDegreeSession($studentDetails["degreeSession"]);
            $student->setDegreeJuryNumber($studentDetails["degreeJuryNumber"]);
            $student->setDegreeReferenceId($studentDetails["degreeReferenceId"]);


            $student->setSportiveInformation($studentDetails["sport"]);
            $student->setCulturalInformation($studentDetails["cultural"]);
            $student->setAssociativeInformation($studentDetails["association"]);
            $student->setItKnowledge($studentDetails["computer"]);*/


            //perform student administrative registration and update registration status to 2
           /* $data["matricule"]=$studentMat;
            $data["classe"]=$studentDetails["classe"];    */           
           
            
            if(isset($studentDetails["cursus"]))
            {
                foreach ($studentDetails["cursus"] as $key=>$value)
                {
                    $cursus = new CursusAcademique();
                    $cursus->setAnnee($escaper->escapeHtml($value["annee"]));
                    $cursus->setEtablissement($escaper->escapeHtml($value["etablissement"]));
                    $cursus->setDiplome($escaper->escapeHtml($value["diplome"]));
                    $cursus->setMention($escaper->escapeHtml($value["mention"]));
                    $cursus->setProspectiveStudent($student);
                    $this->entityManager->persist($cursus);
                    
                }
            }            
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();  
            $this->sessionContainer->onlineLoggedInUser["numDossier"] = $numDossier;
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
         
        $view = new ViewModel([
           
         ]);
        return $view; 
        
    }
    public function applyAction()
    {
        $this->layout()->setTemplate('layout/layout3');
            $data = $this->params()->fromPost();

            if(!isset($data["numDossier"]))  return new ViewModel();
            $prospective = $this->entityManager->getRepository(ProspetiveRegistration::class)->findOneByNumDossier($data['numDossier']);
            
            if($prospective)
            {  
                $id = $prospective->getProspectiveStudent()->getId();
          
                $this->sessionContainer->onlineLoggedInUser = ["numDossier"=>$data['numDossier'],"stdId"=>$id] ;
                
                return $this->redirect()->toRoute('endApplication');
               /* $hydrator = new ReflectionHydrator();
                $prospective = $hydrator->extract($prospective); 
                        $view = new ViewModel([
                        "student"=> $prospective 
                        ]);
                       return $view;*/
            }
            else{
                
     
                $this->layout()->setVariable('fileStatus', true);
                //$this->redirect()->toRoute('apply');
           
                return new ViewModel();
            }

    }     
    
    public function endApplicationAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $numDossier = $this->sessionContainer->onlineLoggedInUser["numDossier"];
            $stdId =  $this->sessionContainer->onlineLoggedInUser["stdId"];
            if(!$numDossier ) return $this->redirect()->toRoute('apply');
            $pros = $this->entityManager->getRepository(ProspectiveStudent::class)->find($stdId);
            $cursusAcademic = $this->entityManager->getRepository(CursusAcademique::class)->findByProspectiveStudent($pros);
            
            $prosReg = $this->entityManager->getRepository(ProspetiveRegistration::class)->findOneByNumDossier($numDossier);
            $academicYear = $prosReg->getAcademicYear()->getCode(); 
            $stdParent = $this->entityManager->getRepository(StudentParent::class)->findByProspectiveStudent($pros);
          //  $id = intval($prosReg->getProspectiveStudent()->getId());
            
           // $pros = $prosReg->getProspectiveStudent();
            $hydrator = new ReflectionHydrator();
            $prospective = $hydrator->extract($pros);
            $registrationDetails =  $hydrator->extract($prosReg); 
            foreach($stdParent as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data =  $hydrator->extract($value);
                $stdParent[$key] = $data;
            }
            foreach($cursusAcademic as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data =  $hydrator->extract($value);
                $cursusAcademic[$key] = $data;
            }
            //var_dump($registrationDetails);
            //return;          
            //var_dump($prospective); return;
            //$this->sessionContainer->onlineLoggedInUser = null;
        } 
        catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
            throw $ex;
        }
        $this->layout()->setTemplate('layout/layout3');
        $view = new ViewModel([
         "student"=> $prospective,
         "registrationDetails" => $registrationDetails,
         "status"  => $pros->getStatus(),
         "stdParent"=>$stdParent,
         "academicYear"=>$academicYear,
            "cursusAcademic"=>$cursusAcademic
         ]);
        return $view;         
    }
    
    public function searchapplicationfileAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $data = $this->params()->fromPost();
           // var_dump($data); exit;
            
           
            $prospective = $this->entityManager->getRepository(ProspectiveStudent::class)->findOneByNumDossier($data['numDossier']);
            if($prospective)
            {
                $numDossier = $this->sessionContainer->onlineLoggedInUser;
                return $this->redirect()->toRoute('endApplication');
                $hydrator = new ReflectionHydrator();
                $prospective = $hydrator->extract($prospective); 
                        $view = new ViewModel([
                        "student"=> $prospective 
                        ]);
                       return $view;
            }
            else{
                
                $this->layout()->setTemplate('layout/layout3');
                $this->layout()->setVariable('fileStatus', true);
                //$this->redirect()->toRoute('apply');
            }
            
        } 
        catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
            throw $ex;
        }
         
    }
    
    private function imgageCompress ($source, $destination, $quality)
    {
        
        $info = getimagesize($source);                    

            if ($info['mime'] == 'image/jpeg') 
                $image = imagecreatefromjpeg($source);

            elseif ($info['mime'] == 'image/gif') 
                $image = imagecreatefromgif($source);

            elseif ($info['mime'] == 'image/png') 
                $image = imagecreatefrompng($source);

            imagejpeg($image,$destination, 90);
             
    }
    
}
