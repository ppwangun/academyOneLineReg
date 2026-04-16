<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Teacher\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\AcademicRanck;
use Application\Entity\AcademicYear;
use Application\Entity\Countries;
use Application\Entity\Cities;
use Application\Entity\Faculty;
use Application\Entity\Teacher;
use Application\Entity\Contract;
use Application\Entity\ContractFollowUp;
use Application\Entity\FileDocument;
use Application\Entity\AllContractsView;
use Application\Entity\AllContractsFollowUpView;
use Application\Entity\TeacherPaymentRate;
use Application\Entity\PaymentTeachingAssignmentMethod;
use Application\Entity\AcademicRanckPaymentRates;
use Application\Entity\Semester;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\OdooSettings;

use Njine\Odoo\Synchronisation;


class PaymentController extends AbstractActionController
{
    private $entityManager;
    private $teacherManager;
    private $sessionContainer;
    private $crtAcadYr;
    
    public function __construct($entityManager,$teacherManager,$sessionContainer) {
        
        $this->entityManager = $entityManager;  
        $this->teacherManager = $teacherManager;
        $this->sessionContainer = $sessionContainer;
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
    }
    
    
    public function paymentsgridlistAction()
    {
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;         
    }
    
    public function newPaymentGridAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        

                $view =  new ViewModel([

                    'userName' => $this->sessionContainer->userName
                ]);

                $view->setTerminal(true);

                return $view ;
            
               



          
           
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    
    public function allPaymentTypesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->getRequest()->getContent(); 
            $data = json_decode($data, true);
            
            //$pymtGrid = array([]);
            $allPaymentTypes =[];
            $PaymentRate = new TeacherPaymentRate();
             
            if(sizeof($data)<=0)
            {
                $allPaymentTypes = $this->entityManager->getRepository(TeacherPaymentRate::class)->findAll();
                foreach($allPaymentTypes as $key=>$value)
                {

                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $allPaymentTypes[$key] = $data;
                }
            }

      
                $PaymentRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->findOneBy($data); 
                $pymtGrid = $this->entityManager->getRepository(AcademicRanckPaymentRates::class)->findByTeacherPaymentRate($PaymentRate);
        
            $hydrator = new ReflectionHydrator();
            if($PaymentRate) $PaymentRate = $hydrator->extract($PaymentRate);
                    else  $PaymentRate = [];
             
            foreach($pymtGrid as $key=>$value)
            {

                $hydrator = new ReflectionHydrator(); 
                $pymtGrid[$key] = $hydrator->extract($value);
                $pymtGrid[$key]["grade"] = ["name"=>$value->getAcademicRanck()->getName(),"id"=>$value->getAcademicRanck()->getId()];
 
            } 

        

            $view =  new JsonModel([
               'allPaymentRate'=> $allPaymentTypes,
               'paymentRate'=> $PaymentRate,
               'paymentGrid'=>$pymtGrid
                
            ]);

            $view->setTerminal(true);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            
    }    
    
    public function setDefaultPymtGridAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->getRequest()->getContent(); 
            $data = json_decode($data, true);            
            //$pymtGrid = array([]);
            $allPaymentTypes =[];
            

            $allPaymentTypes = $this->entityManager->getRepository(TeacherPaymentRate::class)->findAll();
            foreach($allPaymentTypes as $key=>$value)
                $value->setIsDefaultPayment(false);
            
            $PaymentRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($data["id"]);
            $PaymentRate->setIsDefaultPayment(true); 
            
            $this->entityManager->flush();
            
            foreach($allPaymentTypes as $key=>$value)
            {

                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $allPaymentTypes[$key] = $data;
            }                
                    
            $PaymentRate = [];
            $pymtGrid = [];

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            $view =  new JsonModel([
               'allPaymentRate'=> $allPaymentTypes,
               'paymentRate'=> $PaymentRate,
               'paymentGrid'=>$pymtGrid
                
            ]);

            $view->setTerminal(true);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            
    }    
    
    public function createPymtGridAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        
        
            $data = $this->params()->fromPost();           
            $requestBody = $this->getRequest()->getContent();

            $data = json_decode($requestBody, true);            
            
            if(!$data['isUpdate'])
            {

                $pymtRate = new TeacherPaymentRate();
                $pymtRate->setDescription($data["title"]);
                $pymtRate->setIsDefaultPayment($data["isDefaultPaymentGrid"]);

                $this->entityManager->persist($pymtRate);
                

                $paymentGridId = $pymtRate->getId(); 
            
                $pymtGrid = $data["pymtGridDetails"];
                foreach($pymtGrid as $key=>$value)
                {
                    $grade = $this->entityManager->getRepository(AcademicRanck::class)->find($value["grade"]["id"]);
                    $pymtDetails = new AcademicRanckPaymentRates();
                    $pymtDetails->setAcademicRanck($grade);
                    $pymtDetails->setAmountTheoritical($value["amountTheoritical"]);
                    $pymtDetails->setAmountPractical($value["amountPractical"]);
                    $pymtDetails->setTeacherPaymentRate($pymtRate);
                    $this->entityManager->persist($pymtDetails);

                }

                
            }
            else
            {
                $paymentGridId = $data["id"];
                $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($data["id"]);
                $pymtRate->setDescription($data["title"]);
                $pymtRate->setIsDefaultPayment($data["isDefaultPaymentGrid"]);
                
               
                $pymtGrid = $this->entityManager->getRepository(AcademicRanckPaymentRates::class)->findByTeacherPaymentRate($pymtRate);
                
                //Delete all the existing payment associated 
                foreach($pymtGrid as $grid) $this->entityManager->remove($grid);
                $this->entityManager->flush();
                
                $pymtGrid = $data["pymtGridDetails"];
                foreach($pymtGrid as $key=>$value)
                { 
                    $grade = $this->entityManager->getRepository(AcademicRanck::class)->find($value["grade"]["id"]); 
                    $pymtDetails = new AcademicRanckPaymentRates();
                    $pymtDetails->setAcademicRanck($grade);
                    $pymtDetails->setAmountTheoritical($value["amountTheoritical"]);
                    $pymtDetails->setAmountPractical($value["amountPractical"]);
                    $pymtDetails->setTeacherPaymentRate($pymtRate); 
                    $this->entityManager->persist($pymtDetails); 
    
                }
                
                
                
            }
            
            //Set de payment method as paymentGrid 
            $paymentMethod = 0;
            $amount = NULL;
                        
            
            if(sizeof($data['subjects'])>0)
            {

                foreach($data['subjects'] as $sub)
                {

                    $subject= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($sub["id"]);

                    $this->teacherManager->setVacationPaymentMethod($subject,$paymentGridId ,$paymentMethod,$amount);

                }

            }
            if(sizeof($data['classes'])>0)
            { 
                $classes = $data['classes'];
                foreach($classes as $classe)
                {
                    $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($this->crtAcadYr); 
                    foreach($semesters as $sem)
                    { 
;
                        $coshs= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(["classOfStudy"=>$classe,"semester"=>$sem]);
                        foreach($coshs as $subject) 
                            $this->teacherManager->setVacationPaymentMethod($subject,$paymentGridId ,$paymentMethod,$amount);
 
                    }
                }
            }            

            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        

            $view =  new JsonModel([

                
            ]);

            $view->setTerminal(true);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            
    }    
 
    public function deletePymtGridAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {        
        
            $data = $this->params()->fromPost();           
            $requestBody = $this->getRequest()->getContent();

            $data = json_decode($requestBody, true);  

                $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($data["id"]);
                
                
                $pymtGrid = $this->entityManager->getRepository(AcademicRanckPaymentRates::class)->findByTeacherPaymentRate($pymtRate);
                
                foreach($pymtGrid as $grid) $this->entityManager->remove($grid);
                
                $this->entityManager->remove($pymtRate);
                $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
        

            $view =  new JsonModel([

                
            ]);

            $view->setTerminal(true);

            return $view;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }            
    }
    
    public function get($id)
    {  
        try{
        
        if(is_numeric($id))
        {
         
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($id); 
            $documents = $this->entityManager->getRepository(FileDocument::class)->findOneByTeacher($id);
            if($documents)
                foreach($documents as $key=>$value)
                {

                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value);
                    $documents[$key] = $data;
                }   
            else $documents = [];

                $hydrator = new ReflectionHydrator();
                $academic_rank_id = null;
                $requested_establishment_id = null;
                if($teacher->getAcademicRanck())
                    $academic_rank_id = $teacher->getAcademicRanck()->getId();
                if($teacher->getFaculty())
                    $requested_establishment_id = $teacher->getFaculty()->getId(); 
                $data = $hydrator->extract($teacher);
                $teacher = $data;
                $teacher["names"]=$data["name"];
              
                $country = $this->entityManager->getRepository(Countries::class)->findOneByName($data["livingCountry"]);
                $nationality = $this->entityManager->getRepository(Countries::class)->findOneByName($data["nationality"]);
                $city = $this->entityManager->getRepository(Cities::class)->findOneByName($data["livingCity"]);
                
                if($country)
                    $teacher["living_country"]=$hydrator->extract($country);
                if($city)
                    $teacher["living_city"]=$hydrator->extract($city);
                if($nationality)
                    $teacher["nationality"]= $nationality->getName() ; 
                
                $teacher["marital_status"]= $data["maritalStatus"] ;
                $teacher["phone"]= $data["phoneNumber"] ; 
                $teacher["highest_degree"]= $data["highDegree"] ;
                $teacher["grade_id"]= $academic_rank_id ;
                $teacher["actual_employer"]= $teacher["currentEmployer"] ;
                $teacher["requested_establishment_id"] = $requested_establishment_id;
              
                if($data["birthDate"])
                    $teacher["birthdate"]=$data["birthDate"]->format('Y-m-d');
                $teacher["documents"] = $documents;
                
                //$acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
                $acadYearId = $this->crtAcadYr->getId(); 
                $contracts = [];
              
                $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.teacher   FROM Application\Entity\AllContractsView c '
                        .'WHERE c.teacher = :teacher AND c.acadYrId = :acadYearId' );
                $query->setParameter('teacher',$id);
                $query->setParameter('acadYearId',$acadYearId);

                //if($query->getResult())
                    $contracts = $query->getResult();
              
                foreach ($contracts as $key=>$con) 
                {
                   
                    $query = $this->entityManager->createQuery('SELECT con.id as id,c.totalTime   FROM Application\Entity\ContractFollowUp c '
                        .'JOIN c.contract con '
                        . 'WHERE con.id = :contractID' );
                     $query->setParameter('contractID',$con["id"]);
                    // $query->setParameter('acdYearId',$acadYearId);
                     $contract_follow_up = $query->getResult(); 
                    $totalTime = 0;
                    foreach($contract_follow_up as $key1=>$cfu)
                    { 
                        $totalTime += $cfu["totalTime"];
                    }
                    
                    $contracts[$key]["totalHrsDone"] = $totalTime;
                    $contracts[$key]["ecart"] = $con["totalHrs"]-$totalTime;
                    
                }
                $birthDate = null;
                if(!empty($data["birthDate"])) $birthDate = $data["birthDate"]->format('Y-m-d');

                //$subjects_1 = $query->getResult();
                $teacher["teaching_units"] = $contracts;
            return new JsonModel([
                "date"=>$birthDate,
                $teacher
            ]);
            }
        
        }catch (\Doctrine\ORM\Exception $e) {
            throw $e;
        }
            
        


        
        //return $faculties;
    }
    public function getList()
    {       
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
            
            $q = $this->entityManager->createQuery("select t from Application\Entity\Teacher t where t.status = 1 ORDER BY t.name ASC");
            $teachers = $q->getResult();            
       // $teachers = $this->entityManager->getRepository(Teacher::class)->findAll([],array("name"=>"ASC"));
                
            foreach($teachers as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $teachers[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $teachers  
                
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }

    }    
    public function getFaculty($school)
    {
        $faculties = $this->entityManager->getRepository(Faculty::class)->findBySchool($school);
        foreach($faculties as $key=>$value)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($value);

            $faculties[$key] = $data;
        }
        return $faculties;
        
    }
    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            
            $message = false;

            $teacher= new Teacher();
            if(isset($data['civility']))$teacher->setCivility($data['civility']);
            if(isset($data['names']))$teacher->setName($data['names']);
            if(isset($data['birthdate']))$teacher->setBirthDate(new \DateTime($data['birthdate']));
            if(isset($data["living_country"]))$teacher->setLivingCountry($data["living_country"]["name"]);
            if(isset($data["living_city"]))$teacher->setLivingCity($data["living_city"]["name"]);
            if(isset($data["nationality"]))$teacher->setNationality($data["nationality"]);
            if(isset($data["marital_status"]))$teacher->setMaritalStatus($data["marital_status"]);
            if(isset($data["phone"]))$teacher->setPhoneNumber($data["phone"]);
            if(isset($data["contactInEmergency"]))$teacher->setContactInEmergency($data["contactInEmergency"]);
            if(isset($data["email"]))$teacher->setEmail($data["email"]);
            if(isset($data["speciality"]))$teacher->setSpeciality($data["speciality"]);
            if(isset($data["actual_employer"]))$teacher->setCurrentEmployer($data["actual_employer"]);
            if(isset($data["highest_degree"]))$teacher->setHighDegree($data["highest_degree"]); 
            if(isset($data["type"]))$teacher->setType($data["type"]);
            $teacher->setStatus(1);
            
            $grade =$this->entityManager->getRepository(AcademicRanck::class)->find($data['grade_id']); 
            $teacher->setAcademicRanck($grade);
            $faculty =$this->entityManager->getRepository(Faculty::class)->find($data['requested_establishment_id']);  
            
            $teacher->setFaculty($faculty);
           
            
           

            $this->entityManager->persist($teacher);
            if(isset($data["documents"]))
            {
       
                $documents = $data["documents"];
                //var_dump($data);
                
                foreach($documents as $key=>$value)
                {
                  if($value["type"] == "identity_document") 
                  {
                      $filename = $_FILES["documents"]["name"][$key]["file"];
                      $file = $_FILES["documents"]["tmp_name"][$key]["file"];
             
                      $destination = $_SERVER['DOCUMENT_ROOT'] .'/teacherDocs/'.$filename;
                      move_uploaded_file($file,$destination);
                      
                     
                  }
                }
             

               foreach ($_FILES["documents"]["name"] as $key=>$file)
               {  //echo $key." YES MAN ".$file["file"];
                /*   if($key="img_file")
                   {
                   $filename = $file["name"][0];  
                   $destination = $_SERVER['DOCUMENT_ROOT'] .'/paymentsproof/'.$filename;
                   move_uploaded_file($file['tmp_name'][0],$destination);
                   $pdf->addPDF($destination,'all');
                   }*/
               }
            }
           

 



            //$degree =$this->entityManager->getRepository(Degree::class)->findOneById($data['degreeId']);
            
            
            //$classe->setDegree($degree);  
            
            $this->entityManager->flush();
            $message = true;
            $data["id"] = $teacher->getId();
            
            
            $odooSettings = $this->entityManager->getRepository(OdooSettings::class)->findAll();
            if(sizeof($odooSettings)>0)
            {
                $odooSettings = $odooSettings[0];

                //Perform the odoo Sync only when it is activated
                if($odooSettings->getActivateStatus())
                {            

                    /***** Synchronisation des données avec Odoo - Ajout d'un enseignant *****/;
                    $odoo = new Synchronisation();
                    $info = $odoo->connexionOdoo();
                    if($info["resultat"] == "success")
                    { 
                                        $info = $odoo->ajouterEnseignant(
                                                strval($data["id"]),
                                                $data["names"],
                                                $data["email"],
                                                $data["phone"],
                                                $data["living_city"]["name"].", ".$data["living_country"]["name"]
                                        ); 
                                }
                    /***** Fin de la synchronisation *****/;  
                }
            }
            $this->entityManager->getConnection()->commit(); 
            
            
          
            
            return new JsonModel([
                 $message
            ]);  
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        
        
        
    }
  
    public function delete($id)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($id);
            if($teacher )
            {
                
                $this->entityManager->remove($teacher );
                $this->entityManager->flush();
                $this->entityManager->getConnection()->commit();
            }


        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;    
        }
        
        return new JsonModel([
               // $this->getFaculty($data["school_id"])
        ]);
    }
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
            $data = $data["data"];
          
            $teacher =$this->entityManager->getRepository(Teacher::class)->findOneById($id);
            if(isset($data['civility']))$teacher->setCivility($data['civility']);
            if(isset($data['names']))$teacher->setName($data['names']);
            if(isset($data['birthdate']))$teacher->setBirthDate(new \DateTime($data['birthdate']));
            if(isset($data["living_country"]))$teacher->setLivingCountry($data["living_country"]["name"]);
            if(isset($data["living_city"]))$teacher->setLivingCity($data["living_city"]["name"]);
            if(isset($data["nationality"]))$teacher->setNationality($data["nationality"]);
            if(isset($data["marital_status"]))$teacher->setMaritalStatus($data["marital_status"]);
            if(isset($data["phone"]))$teacher->setPhoneNumber($data["phone"]);
            if(isset($data["contactInEmergency"]))$teacher->setContactInEmergency($data["contactInEmergency"]);
            if(isset($data["email"]))$teacher->setEmail($data["email"]);
            if(isset($data["speciality"]))$teacher->setSpeciality($data["speciality"]);
            if(isset($data["actual_employer"]))$teacher->setCurrentEmployer($data["actual_employer"]);
            if(isset($data["highest_degree"]))$teacher->setHighDegree($data["highest_degree"]); 
            if(isset($data["type"]))$teacher->setType($data["type"]);
            if(isset($data["status"]))$teacher->setStatus($data["status"]);
            $grade =$this->entityManager->getRepository(AcademicRanck::class)->find($data['grade_id']); 
            $teacher->setAcademicRanck($grade);
            $faculty =$this->entityManager->getRepository(Faculty::class)->find($data['requested_establishment_id']);  
            
            $teacher->setFaculty($faculty);

            
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
