<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\ProspectiveStudent;
use Application\Entity\ProspectiveStudentView;
use Application\Entity\ClassOfStudy;
use Application\Entity\FieldOfStudy;
use Application\Entity\Degree;
use Application\Entity\AcademicYear;
use Application\Entity\ProspetiveRegistration;
use Application\Entity\Admission;


class ProspectsController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    private $examManager;
    
    public function __construct($entityManager,$sessionContainer,$examManager) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer= $sessionContainer;
        $this->examManager = $examManager;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $prospects = $this->entityManager->getRepository(ProspectiveStudentView::class)->find($id);
            $hydrator = new ReflectionHydrator();
            $prospects = $hydrator->extract($prospects);
            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $prospects
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

        try
        {  
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            //$prospects = $this->entityManager->getRepository(ProspetiveRegistration::class)->findByAcademicYear($acadYr);
            $prospects = $this->entityManager->getRepository(ProspectiveStudentView::class)->findAll();
           // $query = $this->entityManager->createQuery('SELECT c.id,c.nom,c.phoneNumber,c.numDossier,c.  FROM Application\Entity\ProspectiveStudent c');
           // $prospects = $query->getResult();
            
            foreach($prospects as $key=>$value)
            {
                
                $hydrator = new ReflectionHydrator();
                //$pros= $this->entityManager->getRepository(ProspectiveStudent::class)->find($value->getProspectiveStudent()->getId());
                $data = $hydrator->extract($value);

                $prospects[$key] = $data;
            }

            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $prospects
            ]);
           
            return $output;
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $admission = $this->entityManager->getRepository(Admission::class)->findOneByFileNumber($data['numDossier']);
            if(!$admission)
            {
            $student = $this->entityManager->getRepository(ProspectiveStudentView::class)->findOneByNumDossier($data['numDossier']);
            $prospect = $this->entityManager->getRepository(ProspetiveRegistration::class)->findOneByNumDossier($data['numDossier']);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByOnlineRegistrationDefaultYear(1);
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["classe"]);
           
            $degree = $classe->getDegree();
            $prospect->setStatus($data['status']);
            
            $admission = new Admission($classe);
            
            $admission->setFileNumber($data["numDossier"]);
            $admission->setClassOfStudy($classe);
            $admission->setAcademicYear($acadYr);
            $admission->setNom($student->getNom());
            $admission->setPrenom($student->getPrenom());
            $admission->setPhoneNumber($student->getPhoneNumber());
            $admission->setStatus(0);
            $admission->setDateAdmission(new \DateTime(date('Y-m-d H:i:s')));
            $admission->setDegree($degree);
            $admission->setProspectiveStudent($prospect->getProspectiveStudent());
            
            $this->entityManager->persist($admission);
            $this->entityManager->flush();
            
            $this->entityManager->getConnection()->commit();
            
            

            $msge_en_std = "Votre candidature d\'admission à l'institue Agenla est acceptée. veuillez proceder à votre inscription académique"; 
            //$this->examManager->sendWeb2sms237API($student->getPhoneNumber(),$msge_en_std);
             $phoneNumber = "+237".$student->getPhoneNumber();
            $this->examManager->sendAvylTextSMS($phoneNumber,$msge_en_std);
            
        
        // $to = $prospect->getEmail();
            $to = "wangunpp@yahoo.fr";
         $subject = "Votre admission à l'institut agenla";
         
         $message = "<b>This is HTML message.</b>";
         $message .= "<h1>Votre candidature d'admission a été acceptée.</h1>";
         $message .= "<h1>Veuillez procéder à votre inscription académique dans un de lais de ....</h1>";
         
         $header = "From:wangunpp@yahoo.fr \r\n";
         $header .= "Cc:wangunpp@yahoo.fr \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
            }
            $to = "wangunpp@yahoo.fr";
         $subject = "Votre admission à l'institut agenla";
         
         $message = "<b>This is HTML message.</b>";
         $message .= "<h1>Votre candidature d'admission a été acceptée.</h1>";
         $message .= "<h1>Veuillez procéder à votre inscription académique dans un de lais de ....</h1>";
         
         $header = "From:ppwangun@udm.aed-cm.org\r\n";
         $header .= "Cc:wangunpp@yahoo.fr \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";         
         //$retval = mail ($to,$subject,$message,$header);
         
        /* if( $retval == true ) {
            echo "Message sent successfully...";
         }else {
            echo "Message could not be sent...";
         } */           

            
            return new JsonModel([
               
            ]);  
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        
        
        
    }    
   
    

}
