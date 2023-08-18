<?php

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Hydrator\ReflectionHydrator as ReflectionHydrator;

use Application\Entity\Faculty;
use Application\Entity\FieldOfStudy;
use Application\Entity\Degree;
use Application\Entity\DegreeHasCourseCategory;
use Application\Entity\CourseCategory;
use Application\Entity\ProspectiveStudent;

class OnlineRegController extends AbstractActionController
{
    private $entityManager;
    
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
               var_dump($data);
               
            $student = new ProspectiveStudent();
               
            $student->setNom($studentDetails["nom"]);
            $student->setPrenom($studentDetails["prenom"]);
            $date_naissance = date("Y-m-d",$studentDetails["birthdate"]);
            $date = new \DateTime($studentDetails["dateOfBirth"]);
            $student->setDateOfBirth($date);
            $student->setBornAt($studentDetails["bornAt"]);
            $student->setPhoneNumber($studentDetails["phoneNumber"]);
            $student->setGender($studentDetails["gender"]);
            $student->setEmail($studentDetails["email"]);
            $student->setRegionOfOrigin($studentDetails["regionOfOrigin"]);
            $student->setNationality($studentDetails["nationality"]);
            $student->setReligion($studentDetails["religion"]);
            $student->setLanguage($studentDetails["language"]);
            $student->setMaritalStatus($studentDetails["maritalStatus"]);
            $student->setWorkingStatus($studentDetails["workingStatus"]);
           // $student->setHandicap((isset($studentDetails["handicap1"])&&!empty($studentDetails["handicap1"])?$studentDetails["handicap1"]:"NON"));

            $student->setfatherName($studentDetails["fatherName"]);
            $student->setFatherProfession($studentDetails["fatherProfession"]);
            $student->setFatherPhoneNumber($studentDetails["fatherPhoneNumber"]);
            $student->setFatherEmail($studentDetails["fatherEmail"]);
            $student->setFatherCountry($studentDetails["fatherCountry"]);
            $student->setFatherCity($studentDetails["fatherCity"]);

            $student->setMotherName($studentDetails["motherName"]);
            $student->setMotherProfession($studentDetails["motherProfession"]);
            $student->setMotherPhoneNumber($studentDetails["motherPhoneNumber"]);
            $student->setMotherEmail($studentDetails["motherEmail"]);
            $student->setMotherCountry($studentDetails["motherCountry"]);
            $student->setMotherCity($studentDetails["motherCity"]);

            $student->setSponsorName($studentDetails["sponsorName"]);
            $student->setSponsorProfession($studentDetails["sponsorProfession"]);
            $student->setSponsorPhoneNumber($studentDetails["sponsorPhoneNumber"]);
            $student->setSponsorEmail($studentDetails["sponsorEmail"]);
            $student->setSponsorCountry($studentDetails["sponsorCountry"]);
            $student->setSponsorCity($studentDetails["sponsorCity"]);

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
