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

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\AcademicRanck;
use Application\Entity\Faculty;
use Application\Entity\Teacher;

use Application\Entity\FileDocument;
use Application\Entity\ContractFollowUp;
use Application\Entity\Contract;
use Application\Entity\ClassOfStudyHasSemester;


class ProgressionController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager;  
        $this->sessionContainer = $sessionContainer;
    }
    
    
 
    
    public function get($id)
    {   

             $contract= $this->entityManager->getRepository(Contract::class)->find($id);
            
            $progressions = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);
            $dataOutPut = []; 
        
                foreach($progressions as $key=>$value)
                {
 
                    $hydrator = new ReflectionHydrator();
                    $data = $hydrator->extract($value); 
                    $dataOutPut["id"]= $data["id"];
                    $dataOutPut["date"]= $data["date"]->format('Y-m-d H:i:s');
                    $dataOutPut["start_time"] = $data["startTime"]->format('H:i:s');
                    $dataOutPut["end_time"] = $data["endTime"]->format('H:i:s');
                    $dataOutPut["lectureType"] = $data["lectureType"];
                    $dataOutPut["description"] = $data["description"];
                    $progressions[$key] = $dataOutPut;
                }   
              


            
            return new JsonModel([
                $progressions
            ]);
        
        
        //return $faculties;
    }
    public function getList()
    {       
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
        $teachers = $this->entityManager->getRepository(Teacher::class)->findAll();
                
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
            $contract =$this->entityManager->getRepository(Contract::class)->find($data['contract_id']);
            $teacher =$this->entityManager->getRepository(Teacher::class)->find($data['teacher_id']);
            $progression = new ContractFollowUp();
                    $progression->setDate(new \DateTime($data["date"]));
                    $progression->setStartTime(new \DateTime ($data["start_time"]));
                    $progression->setEndTime(new \DateTime ($data["end_time"]));
                    $progression->setDescription($data["description"]);
                    $startTime = new \DateTime ($data["start_time"]);
                    $progression->setLectureType($data["target"]);
                    $endTime = new \DateTime ($data["end_time"]);
                    $timeDiff = $startTime->diff($endTime);  
                    $progression->setTotalTime($timeDiff->h);
                    $progression->setContract($contract);
                    


          
           // $coshs =$this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data['teaching_unit_id']); 
           // $progression->setClassOfStudyHasSemester($coshs );

            $this->entityManager->persist($progression);
 
            
            $this->entityManager->flush();
            $message = true;
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
            $progressions = $this->entityManager->getRepository(ContractFollowUp::class)->find($id);
            if($progressions )
            {
                
                $this->entityManager->remove($progressions );
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
          
            $rank =$this->entityManager->getRepository(AcademicRanck::class)->findOneById($id);
            $rank->setName($data['name']);
            $rank->setCode($data['code']);
            $rank->setPaymentRate($data['paymentRate']);

            
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
