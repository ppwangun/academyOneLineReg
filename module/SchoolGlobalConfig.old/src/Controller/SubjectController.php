<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\DBAL\Types\Type;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Application\Entity\TeachingUnit;
use Application\Entity\Semester;
use Application\Entity\TrainingCurriculum;
use Application\Entity\ClassOfStudy;
use Application\Entity\Subject;
use Application\Entity\ClassOfStudyHasSemester;
use Laminas\Hydrator\Aggregate\AggregateHydrator;

class SubjectController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        //ID is sent as json object
        //We need to decode and convert it to array
        //$ids = json_decode($id,true); 
        $ue=[];
        $query = $this->entityManager->createQuery('SELECT  s.id,s.subjectName,s.subjectCode,c1.code as classCode,c.subjectCredits,'
                . 'c.subjectHours,c.subjectCmHours,c.subjectTdHours,c.subjectTpHours  FROM Application\Entity\ClassOfStudyHasSemester c '
                . 'JOIN c.classOfStudy c1 '
                . 'JOIN c.subject s '
                . 'WHERE s.id = ?1 '
                );
        $query->setParameter(1, $id);
        if($query->getResult())
            $ue = $query->getResult()[0];     

        return new JsonModel([
                $ue
        ]);
        
        //return $faculties;
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        
            
       
        try
        {     
            $query = $this->entityManager->createQuery('SELECT t.id,t.name,t.code,t.numberOfSubjects as subjects, c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs FROM Application\Entity\ClassOfStudyHasSemester c JOIN c.classOfStudy c1 JOIN c.teachingUnit t');
            $ue= $query->getResult();

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $ue  
                
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
            $subject= new Subject();
            $subject->setSubjectName($data['name']);
            $subject->setSubjectCode($data['code']);
            $teachingunit = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($data['ue_id']);
            $subject->setTeachingUnit($teachingunit);
            
            $this->entityManager->persist($subject);
           
            //increment the number of subject inside the teaching unit
            $n = $teachingunit->getNumberOfSubjects(); 
            $teachingunit->setNumberOfSubjects($n+1);
            
            

            $ue_class = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneById($data['ue_classe_id']);
            
            $class_study_semester = new ClassOfStudyHasSemester();
            $class_study_semester->setClassOfStudy($ue_class->getClassOfStudy());
            $class_study_semester->setSemester($ue_class->getSemester());
            /*$class_study_semester->setTeachingUnit($ue_class->getTeachingUnit());
            $class_study_semester->setCredits($ue_class->getCredits());
            $class_study_semester->setHoursVolume($ue_class->getHoursVolume());
            $class_study_semester->setCmHours($ue_class->getCmHours());
            $class_study_semester->setTdHours($ue_class->getTdHours());
            $class_study_semester->setTpHours($ue_class->getTpHours());*/
           
            $class_study_semester->setSubjectWeight($data["credits"]);
            $class_study_semester->setSubjectCredits($data["credits"]);
            $class_study_semester->setSubjectHours($data['hours_vol']);
            $class_study_semester->setSubjectCmHours($data['cm_hrs']);
            $class_study_semester->setSubjectTdHours($data['td_hrs']);
            $class_study_semester->setSubjectTpHours($data['tp_hrs']);
            $class_study_semester->setSubject($subject);
            
            $this->entityManager->persist($class_study_semester);
            $this->entityManager->flush();
           

            

            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                //$classes
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
           $subject = $this->entityManager->getRepository(Subject::class)->findOneById($id);
           $ueClasse = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBySubject($subject);

            if($ueClasse )
            {
              
                $this->entityManager->remove($ueClasse );
                $this->entityManager->remove($subject );
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
            $data= $data['data'];
            $subject = $this->entityManager->getRepository(Subject::class)->findOneById($id);
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["sem"]);
            $class_study_semester = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBy(array("subject"=>$subject,'semester'=>$semester));
            
            $class_study_semester->setSubjectWeight($data["credits"]);
            $class_study_semester->setSubjectCredits($data["credits"]);
            $class_study_semester->setSubjectHours($data['hours_vol']);
            $class_study_semester->setSubjectCmHours($data['cm_hrs']);
            $class_study_semester->setSubjectTdHours($data['td_hrs']);
            $class_study_semester->setSubjectTpHours($data['tp_hrs']);
            
            
            $subject->setSubjectName($data['name']);
            $subject->setSubjectCode($data['code']);
            
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
