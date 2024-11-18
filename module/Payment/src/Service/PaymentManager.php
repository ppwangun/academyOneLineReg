<?php
namespace Payment\Service;

use Application\Entity\Payment;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\UnitRegistration;

use g105b\phpcsv;



use Laminas\Http\Header\Date;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




class PaymentManager {
    
    private $entityManager;
    private $dossier_number;




    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
       
    }
   public function getCurrentYearCode()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr.getCode(); 
       
   }
   
   public function getCurrentYearID()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class).findOneByID(1);
       return $acadyr->getId();
       
   }
   public function getCurrentYear()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
       return $acadyr;
       
   }
   //this function takes as paremeter admin registration ID and returns the total amount of payments done
   public function feesSumOfPayments($id)
   {
       $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->find($id);
     
       $payments = $this->entityManager->getRepository(Payment::class)->findBy(array("adminRegistration"=>$adminRegistration,"academicYear"=>$this->getCurrentYear()));
       //var_dump($payments); exit;
       $sum = 0;
       if($payments)
       { 
          
            foreach ($payments as $pmt)
            {
                $amount = (int)$pmt->getAmount();
                $sum = $sum + $amount;
            }
       }
       $adminRegistration->setFeesPaid($sum );
       $this->entityManager->flush();
   }
   public function addStudent($data)
   {
       //check firs if the student already exists
       $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);
       $student = new Student();
       $student->setMatricule($data["matricule"]);
       $student->setNom($data["nom"]);
       $student->setPrenom($data["prenom"]);
       $student->setDateOfBirth(new \DateTime($data['date_naissance']));
       //$student->setAdmission();
       if($std)
       {
            $std->setMatricule($data["matricule"]);
            $std->setNom($data["nom"]);
            $std->setPrenom($data["prenom"]);
            $std->setDateOfBirth(new \DateTime($data['date_naissance']));
           //Update student
          // $this->entityManager->persist();   
            $this->entityManager->flush();
            
            $this->stdPedagogicRegistration($data['classe'],$std);
             
       }
       else
       {
           //create new student
           $this->entityManager->persist($student);
            $this->entityManager->flush();
            
            $this->stdPedagogicRegistration($data['classe'],$student);
            
       }
       
       
   }
   public function stdAdminRegistration($data)
   {
       $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["matricule"]);
       
              
       $isRegistered = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array('student'=>$std,'academicYear'=>$this->getCurrentYear()));
       
       $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["classe"]); 
      // $admission = $this->entityManager->getRepository(Admission::class)->findOneByCode($class_code);
     
       $currentDate = date_create(date('Y-m-d H:i:s'));
       $adminRegistration = new AdminRegistration();
       
       $adminRegistration->setAcademicYear($this->getCurrentYear());
       $adminRegistration->setClassOfStudy($class);
       $adminRegistration->setStudent($std);
       $adminRegistration->setRegisteringDate($currentDate);
       
       //check if student is already registered for the current year
       if($isRegistered)
       {
           
            $isRegistered->setAcademicYear($this->getCurrentYear());
            $isRegistered->setClassOfStudy($class);
            $isRegistered->setStudent($std);
            $isRegistered->setRegisteringDate($currentDate);
            $this->entityManager->flush();
        
       }
       else{
           $this->entityManager->persist($adminRegistration);
           $this->entityManager->flush();
                   
       }
   }
   public function stdPedagogicRegistration($classe,$student)
   {
       //collecting all teaching unit beloging to the classe enteress as parameter
       $classe_ue = $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findByClasse($classe);
       
       //Get semester of the current year
       
       
        foreach ($classe_ue as $key)
        {
            
            $ue = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($key->getId());
            $semester = $this->entityManager->getRepository(Semester::class)->findOneByCode($key->getSemester());
            $unit_registered = $this->entityManager->getRepository(UnitRegistration::class)->findOneBy(array("student"=>$student,
                    "teachingUnit"=>$ue,"semester"=>$semester));
            if($unit_registered)
            {
                $unit_registered->setStudent($student);
                $unit_registered->setTeachingUnit($ue);
                $unit_registered->setSemester($semester);
                
                $this->entityManager->flush();                
            }
            else{
            $unit_registration = new UnitRegistration();
            $unit_registration->setStudent($student);
            $unit_registration->setTeachingUnit($ue);
            $unit_registration->setSemester($semester);
            $this->entityManager->persist($unit_registration);
            $this->entityManager->flush();
            }
        }

       
   }
   
   public function importBalance($data)
   {
       $student = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->find($data["matricule"]);
       //Check 
       if($student)
       {
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($student->getId());
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std,"academicYear"=>$academicYear));
            if($adminRegistration)
            {
                 $currentDate  = date_create(date('Y-m-d H:i:s'));
                //check if balance was already registered
                $payment = $this->entityManager->getRepository(Payment::class)->findOneBy(array("adminRegistration"=>$adminRegistration,"academicYear"=>$academicYear,"fromBalance"=>1));
                if($payment)
                {
                    $payment->setAmount($data["encaissement"]);
                    $payment->setDateTransaction($currentDate);
                    $payment->setFromBalance(1);
                    $adminRegistration->setFeesPaid($data["encaissement"]);
                    $adminRegistration->setFeesBalanceFromPreviousYear($data["impaye"]);                    
                }
                else
                {
                   
                    $payment = new Payment();
                    $payment->setAcademicYear($academicYear);
                    $payment->setAdminRegistration($adminRegistration);
                    $payment->setAmount($data["encaissement"]);
                    $payment->setDateTransaction($currentDate);
                    $payment->setFromBalance(1);
                    $adminRegistration->setFeesPaid($data["encaissement"]);
                    $adminRegistration->setFeesBalanceFromPreviousYear($data["impaye"]);
                    $this->entityManager->persist($payment);
                }
                
                $this->entityManager->flush(); 
            }
       }

      
   }
   
   public function importPayments($data)
   {
       $student = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->find($data["matricule"]);
       //Check 
       if($student)
       {
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($student->getId());
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std,"academicYear"=>$academicYear));
            $time = date("H:i:s");
            if(isset($data['transactionDate']))
                $date = date('Y-m-d ', strtotime($data['transactionDate']));
            else $date = date("Y-m-d h:i:sa");
            
            if($adminRegistration)
            {
                (isset($data["transactionDate"]))? $currentDate=date_create(date('Y-m-d H:i:s', strtotime($date.''.$time))): $currentDate  = date_create(date('Y-m-d H:i:s'));

                    $payment = new Payment();
                    $payment->setAcademicYear($academicYear);
                    $payment->setAdminRegistration($adminRegistration);
                    $payment->setAmount($data["montant"]);
                    $payment->setDateTransaction($currentDate);
                    $this->entityManager->persist($payment);

                    $this->entityManager->flush(); 
                    $this->feesSumOfPayments($student->getRegistrationId());
            }
       }

      
   } 
   
      public function updatePymtAPI($data)
   {

        $url = "http://testagenla.lekef.net/web/session/authenticate";

        // Odoo database credentials
        $db = "testagenla";
        $username = "webservice";
        $password = "webservice";

        // Request data
        $payload = array(
            "jsonrpc" => "2.0",
            "method" => "call",
            "params" => array(
                "db" => $db,
                "login" => $username,
                "password" => $password,
            )
        );

        // Convert the payload to JSON
        $data = json_encode($payload);

        // Set cURL options
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Content-Length: " . strlen($data)
            )
        );

        // Initialize cURL
        $curl = curl_init();
        curl_setopt_array($curl, $options);

        // Send the request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            $error = curl_error($curl);
            return  "message: " . $error;
        } else {
         //Send the request
          
          
                
            // Handle the response
            $result = json_decode($response, true);

            // Check if the authentication was successful
            if (array_key_exists("result", $result)) {
                // Retrieve the session ID 
                $session_id = $result["result"]["session_id"];
                $data = ["partner_id"=> "64c7c85c85cfe720ceafaf1a",
                            "name"=> "Ann Blair",
                            "email"=> "annblair@xerex.com",
                            "phone"=> "+1 (904) 469-2398",
                            "street"=> "679 Sunnyspartner_ide Court, Warren, Palau, 8700"];
                // Convert the payload to JSON
                $url =" https://tesdetagenla.lekef.net/api/agenla/partner/create";
                $data = json_encode($data);
                $options = array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Content-Length: " . strlen($data),
                        "Set-Cookie: session_id".$session_id."; Expires=Wed, 15-May-2024 16:12:37 GMT; Max-Age=7776000; HttpOnly; Path=/",
                    )
                ); 
                
                curl_close($curl); 
                $curl = curl_init();
                curl_setopt_array($curl, $options);

                // Send the request
                $response = curl_exec($curl); 
                // Get information about the cURL request
                $info = curl_getinfo($curl);

                // Check for errors
                if ($response === false) {
                    $error = curl_error($curl);                   
                    echo "message: " . $error; exit;
                } 
              curl_close($curl);
              echo 'HTTP Code: ' . $info['http_code'] . '<br>';
                echo 'Total Time: ' . $info['total_time'] . ' seconds<br>';
                echo 'Content Type: ' . $info['content_type'] . '<br>';



            } 

            // Close cURL
        curl_close($curl); 
}

      
   }
   
   public function importDotations($data)
   {
       $student = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->find($data["matricule"]);
       //Check 
       if($student)
       {
            $std = $this->entityManager->getRepository(Student::class)->findOneByMatricule($student->getId());
            $academicYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            $adminRegistration = $this->entityManager->getRepository(AdminRegistration::class)->findOneBy(array("student"=>$std,"academicYear"=>$academicYear));
            if($adminRegistration)
            {
                    $adminRegistration->setFeesDotation($data["montant"]);

                    $this->entityManager->flush(); 
                    $this->feesSumOfPayments($student->getRegistrationId());
            }
       }

      
   }    
}


                   