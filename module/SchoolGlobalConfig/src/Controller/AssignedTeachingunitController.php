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
use Application\Entity\AcademicYear;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\TrainingCurriculum;
use Application\Entity\ClassOfStudy;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\UnitRegistration;
use Application\Entity\Exam;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;

use Laminas\Hydrator\Aggregate\AggregateHydrator;

class AssignedTeachingunitController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
    }
    
    
 
    
    public function get($id)
    {
        $query = $this->entityManager->createQuery('SELECT  t.id, c.id as ue_class_id,t.name,t.code, t.numberOfSubjects as subjects, c1.id as class_id,c.credits, c.hoursVolume as hours_vol,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs,c.isPreviousYearSubject,t.isCompulsory  FROM Application\Entity\ClassOfStudyHasSemester c '
                . 'JOIN c.classOfStudy c1 '
                . 'JOIN c.teachingUnit t '
                . 'WHERE c.id = ?1 '
                . 'AND c.status = 1 '
                );
        $query->setParameter(1, $id);
        $ue = $query->getResult()[0];     
        //$ue['name']= utf8_encode($ue['name']);
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
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            $ue = [];
         
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {
                //collect all courses affected to any semester
                    $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.name,t.code,t.numberOfSubjects as subjects, c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs FROM Application\Entity\ClassOfStudyHasSemester c '
                        . 'JOIN c.classOfStudy c1 JOIN c.teachingUnit t JOIN c.semester s JOIN s.academicYear a WHERE a.isDefault = 1 '
                        . 'AND c.status = 1 ');
                $ue= $query->getResult();
               
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {  
                    foreach($userClasses as $classe)
                    {
                        //collect all courses affected to any semester
                        $query = $this->entityManager->createQuery('SELECT t.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.name,t.code,t.numberOfSubjects as subjects, c1.code as class,c.credits, c.hoursVolume ,c.cmHours as cm_hrs,c.tpHours as tp_hrs, c.tdHours as td_hrs FROM Application\Entity\ClassOfStudyHasSemester c '
                                . 'JOIN c.classOfStudy c1   JOIN c.teachingUnit t JOIN c.semester s JOIN s.academicYear a WHERE a.isDefault = 1 '
                                . 'AND c.status = 1 '
                                . 'AND c1.code = ?1 ');
                        $query->setParameter(1, $classe->getClassOfStudy()->getCode());
                        $ue_1= $query->getResult(); 
                        $ue = array_merge($ue,$ue_1);
                        
                    }
                }
            }
            for($i=0;$i<sizeof($ue);$i++)
            {
               // $ue[$i]['name']= utf8_encode($ue[$i]['name']);

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

    
    public function create($data)
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            

            $this->addteachingUnit($data);
           // $data['sem_id']= null;
            //$this->addteachingUnit($data);
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
           $ur = $this->entityManager->getRepository(UnitRegistration::class)->findByTeachingUnit($ue);
           //Considering that the course to be deletete can be foreign key to an other table
           //The deleting process consists to unactivate the course by setting the status to null 
            if($ueClasse )
            {
                $i=0;
                $ueClasse->setStatus(0);
                do
                {
                    if($ur)
                    $this->entityManager->remove($ur[$i]);
                    $this->entityManager->flush();
                    $i++;
                }
                while($i<sizeof($ur));
                //$this->entityManager->remove($ue );
                
                
            }

            $this->entityManager->getConnection()->commit();
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
            
            $ueOld =$this->entityManager->getRepository(TeachingUnit::class)->find($id);
            $sem =$this->entityManager->getRepository(Semester::class)->find($data['sem_id']);
            $ue = new TeachingUnit();
            $ue->setName($data["name"]);
            $ue->setCode($data['code']);
            $ue->setIsCompulsory($data['isCompulsory']);
            
            $this->entityManager->persist($ue);
            

            
            $ueClasse= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data["ue_class_id"]);
            
            $uniReg = $ue =$this->entityManager->getRepository(UnitRegistration::class)->findBy(["teachingUnit"=>$ueOld,"semester"=>$ueClasse->getSemester()]);
            foreach($uniReg as $u)
            {
                $u->setTeachingUnit($ue);
                $u->setSemester($sem);
            }

            $subjects = $this->entityManager->getRepository(Subject::class)->findBy(["teachingUnit"=>$ueOld]);
            foreach($subjects as $sub)
            {
                $sub->setTeachingUnit($ue);
            }            
            
            $ueClasse= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($data["ue_class_id"]);
            $ueClasse->setCredits($data['credits']);
            $ueClasse->setTeachingUnit($ue);
            $ueClasse->setHoursVolume($data['hours_vol']);
            $ueClasse->setCmHours($data['cm_hrs']);
            $ueClasse->setTdHours($data['td_hrs']);
            $ueClasse->setTpHours($data['tp_hrs']);
            $ueClasse->setIsPreviousYearSubject($data['isPreviousYearSubject']);
            $ueClasse->setClassOfStudy($this->entityManager->getRepository(ClassOfStudy::class)->find($data['class_id']));
            $ueClasse->setSemester($sem);

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
    private function addteachingUnit($data)
    {
            $teachingunit= new TeachingUnit();
            $teachingunit->setName($data["name"]);
            $teachingunit->setCode($data['code']);
            if(isset($data['isCompulsory']))
                $teachingunit->setIsCompulsory($data['isCompulsory']);
            $this->entityManager->persist($teachingunit);
            $this->entityManager->flush();
            
            $class_study_semester = new ClassOfStudyHasSemester();
            $class_study_semester->setTeachingUnit($teachingunit);
            
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data['class_id']);
            $semester = $this->entityManager->getRepository(Semester::class)->findOneById($data['sem_id']);
            $class_study_semester->setClassOfStudy($class);
            $class_study_semester->setSemester($semester);
            $class_study_semester->setCredits($data['credits']);
            $class_study_semester->setHoursVolume($data['hours_vol']);
            $class_study_semester->setCmHours($data['cm_hrs']);
            $class_study_semester->setTdHours($data['td_hrs']);
            $class_study_semester->setTpHours($data['tp_hrs']);
           // $class_study_semester->setIsPreviousYearSubject($data['isPreviousYrSubject']);
            $this->entityManager->persist($class_study_semester);
            
            $this->entityManager->flush();
    }
}
