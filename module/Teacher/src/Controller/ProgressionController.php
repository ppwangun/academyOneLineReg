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
use Application\Entity\CourseScheduled;
use Application\Entity\Contract;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Student;
use Application\Entity\StudentAttendance;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;


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
                    $dataOutPut["is_from_schedule"] = 0;
                    $dataOutPut["id"]= $data["id"];
                    $dataOutPut["date"]= $data["date"]->format('Y-m-d H:i:s');
                    $dataOutPut["start_time"] = $data["startTime"]->format('H:i:s');
                    $dataOutPut["end_time"] = $data["endTime"]->format('H:i:s');
                    $dataOutPut["lectureType"] = $data["lectureType"];
                    $dataOutPut["description"] = $data["description"];
                    //Check if the progression is issued from the schedules
                    //Progression commng from the schedule can not be deleted
                    
                   // if($value->getCourseScheduled())
                   //     $dataOutPut["is_from_schedule"] = 1;
                    $progr[$key] = $dataOutPut;
                }   
              


            
            return new JsonModel([
                $progr
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

    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $contract =$this->entityManager->getRepository(Contract::class)->find($data['contract_id']); 
            $courseScheduled = null;

              
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

                    
           $this->entityManager->persist($progression); 
           $this->entityManager->flush();
                    
            if(isset($data['scheduled_id']))
            {
                $courseScheduled =$this->entityManager->getRepository(CourseScheduled::class)->find($data['scheduled_id']);
                $courseScheduled->setIsValidated(1);
                $courseScheduled->setContractFollowUp($progression);
                $this->entityManager->flush();
                
            }

            if(isset($data['fromSchedule']))
            {
                //Save attendance
                foreach($data['students'] as $stud)
                {
                    $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($stud["matricule"]);
                    $attendance = new StudentAttendance();
                    $attendance->setStatus($stud["attendance"]);
                    $attendance->setStudent($student);
                    $attendance->setCourseScheduled($courseScheduled);
                    $this->entityManager->persist($attendance);
                }
                
            }
           // $coshs =$this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data['teaching_unit_id']); 
           // $progression->setClassOfStudyHasSemester($coshs );

            
 
            
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
            print($e->getMessage());
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
