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
use Application\Entity\TrainingCurriculum;
use Application\Entity\ClassOfStudy;
use Application\Entity\ClassOfStudyHasSemester;
use Laminas\Hydrator\Aggregate\AggregateHydrator;

class TeachingunitController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    //This function rturns either teachinUnit based on ID or lis of teaching uni base on Semester and class id
    public function get($id)
    {
        //Convert $id paramater into array or scalar
        //id is either a saclar (specific teaching unit ID or array havind classe_id and sem_id
        $id = json_decode($id,true);
                
        if(is_scalar($id))
        {
            
            
            $query = $this->entityManager->createQuery('SELECT  t.id, c.id as ue_class_id,t.name,t.code, t.numberOfSubjects as subjects, c1.id as class_id,c.credits, c.hoursVolume as hours_vol,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs,c.markCalculationStatus as mark_calculation_status  FROM Application\Entity\ClassOfStudyHasSemester c '
                    . 'JOIN c.classOfStudy c1 '
                    . 'JOIN c.teachingUnit t '
                    . 'WHERE t.id = ?1 and c.status = 1'
                    );  
            $query->setParameter(1, $id);
            $ue = $query->getResult()[0];

               // $ue['name']= utf8_encode($ue['name']);

       
            return new JsonModel([
                $ue
            ]);
        }
        
        $query = $this->entityManager->createQuery('SELECT  t.id, c.id as ue_class_id,t.name,t.code, t.numberOfSubjects as subjects, c1.id as class_id,c.credits, c.hoursVolume as hours_vol,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs,c.markCalculationStatus as mark_calculation_status  FROM Application\Entity\ClassOfStudyHasSemester c '
                . 'JOIN c.classOfStudy c1 '
                . 'JOIN c.teachingUnit t '
                . 'JOIN c.semester s '
                . 'WHERE c1.id = ?1 '
                . 'AND s.id = ?2 '
                . 'AND c.status = 1 '
                );
        //$query->setParameters(array("classe"=>$id->sem_id,"sem"=>$id->sem_id,));
        $query->setParameter(1, $id["classe_id"]);
        $query->setParameter(2, $id["sem_id"]);
        $ue = $query->getResult(); 

        for($i=0;$i<sizeof($ue);$i++)
        {
            //$ue[$i]['name']= utf8_encode($ue[$i]['name']);

        }

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
            //collect all the teaching unit not affected to any semester
            $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,t.name,t.code,t.numberOfSubjects as subjects, c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs FROM Application\Entity\ClassOfStudyHasSemester c JOIN c.classOfStudy c1 JOIN c.teachingUnit t'
         .' WHERE c.semester IS NULL'
         .' AND  c.status = 1');
            $ue= $query->getResult();
            for($i=0;$i<sizeof($ue);$i++)
            {
                $ue[$i]['name']= utf8_encode($ue[$i]['name']);
            }
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
            
            $teachingunit= new TeachingUnit();
            $teachingunit->setName($data['name']);
            $teachingunit->setCode($data['code']);
            $this->entityManager->persist($teachingunit);
            $this->entityManager->flush();
            
            $class_study_semester = new ClassOfStudyHasSemester();
            $class_study_semester->setTeachingUnit($teachingunit);
            
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data['class_id']);
            $class_study_semester->setClassOfStudy($class);
            $class_study_semester->setCredits($data['credits']);
            $class_study_semester->setHoursVolume($data['hours_vol']);
            $class_study_semester->setCmHours($data['cm_hrs']);
            $class_study_semester->setTdHours($data['td_hrs']);
            $class_study_semester->setTpHours($data['tp_hrs']);
            $this->entityManager->persist($class_study_semester);
            
          /*  $classe->setIsEndCycle($data['isEndCycle']);
            $classe->setIsEndDegreeTraining($data['isEndDegreeTraining']);
            $classe->setStudyLevel($data['studyLevel']);
 
            $cycle = $this->entityManager->getRepository(TrainingCurriculum::class)->findOneById($data['cycle_id']);
            //$degree = $this->entityManager->getRepository(Degree::class)->findOneById($data['cycle_id']);
            $classe->setCycle($cycle); 
            $classe->setDegree($cycle->getDegree());
            $this->entityManager->persist($classe);*/
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
            $ueClasse = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneById($id);
           $ue = $ueClasse->getTeachingUnit();
           $ueId = $ue->getId(); 
           $ue = $this->entityManager->getRepository(TeachingUnit::class)->findOneById($ueId);
            if($ueClasse )
            {
                
                $this->entityManager->remove($ueClasse );
                $this->entityManager->remove($ue );
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
        
            $ue =$this->entityManager->getRepository(TeachingUnit::class)->findOneById($data['ue_id']);
            $ue->setName($data['name']);
            $ue->setCode($data['code']);
            
            $ueClasse= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneById($id);
            $ueClasse->setCredits($data['credits']);
            $ueClasse->setHoursVolume($data['hours_vol']);
            $ueClasse->setCmHours($data['cm_hrs']);
            $ueClasse->setTdHours($data['td_hrs']);
            $ueClasse->setTpHours($data['tp_hrs']);
            $ueClasse->setClassOfStudy($this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data['class_id']));
            $ueClasse->setSemester($this->entityManager->getRepository(Semester::class)->find($data['sem_id']));
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
