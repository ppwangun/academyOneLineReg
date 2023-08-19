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

class OnlineRegController extends AbstractActionController
{
    private $entityManager;
    
     const PENDING = 0;
     const REJECTED = 2;
     const APPROVED = 1;
    
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
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
        $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->find($data["fil_id"]);
        $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findBy(array("fieldOfStudy"=>$filiere,"courseCategory"=>$cycleFormation));
        
        foreach ($degreeCategory as $key=>$value)
        { 
            $degree = $value->getDegree();
            
            if($degree)
            {
                
                $data = $this->entityManager->getRepository(Degree::class)->find($degree->getId());
                $hydrator = new ReflectionHydrator();
                $data1 = $hydrator->extract($data); 
                
               $degreeCategory[$key] =  array("id"=>$data->getId(),"name"=>$data->getName(),"code"=>$data->getCode());
                
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
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            //Default academic year
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            
            
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

           }
           //Colecting file dans save the  path
           //PDF files are not stored into the data base but instead, just the path is sotred
           //Final user will get access to the file through a link

           if($_FILES['file'])
           {
               $filename = $_FILES['file']['name'][0];
               $destination = $_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$filename;
               move_uploaded_file($_FILES['file']['tmp_name'][0],$destination);  


           }
           $studentDetails = json_decode($data["student"],true);
           
           var_dump($studentDetails);
             
            $escaper = new Escaper("utf-8");   
            $student = new ProspectiveStudent();
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
            

            
            $student->setPaymentProofPath($filename);
            $student->setPhoto($image_base64);
            $student->setAcademicYear($academicYear);
            
            //looking class of study
                        //class of study
            if(isset($studentDetails["degree_id"]))
                $degree = $this->entityManager->getRepository(Degree::class)->find($escaper->escapeHtml($studentDetails["degree_id"]));
            if(isset($studentDetails["study_level"]))
            {
                $classofStudy = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByDegree($degree);
                $student->setClassOfStudy($classofStudy);
            }
            
            //generating a file number
            $prefixe = date('y');
            $numDossier= $prefixe.'-'.mt_rand(100000, 199999);
            while($this->entityManager->getRepository(ProspectiveStudent::Class)->findOneByNumDossier($numDossier))
            {
                $numDossier = $prefixe.'-'.mt_rand(10000, 19999);
            }
            $student->setNumDossier($numDossier);
            
            $student->setStatus(self::PENDING);
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
            $this->entityManager->persist($student);
            
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
