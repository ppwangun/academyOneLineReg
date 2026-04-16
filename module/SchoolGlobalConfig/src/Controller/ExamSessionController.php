<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\Semester;
use Application\Entity\ExamSession;
use Application\Entity\AcademicYear;



class ExamSessionController extends AbstractRestfulController
{
    private $entityManager;
    private $crtAcadYr;
    
    public function __construct($entityManager,$sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
        
        
    }
    
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $examSessions = [];
            $query = $this->entityManager->createQuery('SELECT c.id,c.sessionType,c.sessionCode,c.sessionName FROM Application\Entity\ExamSession c'
                    .' JOIN c.academicYear a'
                   // .' WHERE c.id LIKE :id'
                    .' WHERE a.id = :acadYr'
                    );
            //$query->setParameter('id', '%'.$id.'%');
            $query->setParameter('acadYr', $id);
            
            $examSessions = $query->getResult();

           

            return new JsonModel([
                $examSessions
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }

        
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
           // $academic_year = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array('isDefault'=>'1'));
            $examSessions = $this->entityManager->getRepository(ExamSession::class)->findByAcademicYear($this->crtAcadYr);
           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($examSessions as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $examSessions[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $examSessions
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    }
    public function create($data)
    {

            $session = new ExamSession();
            $session->setSessionType($data['sessionCode']);
            $session->setSessionCode($data['sessionCode']);
            $session->setSessionName($data['sessionName']);


            $acadyear =  $this->entityManager->getRepository(AcademicYear::class)->find($data["acadYrId"]);

            $session->setAcademicYear($acadyear);

            $this->entityManager->persist($session);
            $this->entityManager->flush();

       
        return new JsonModel([
            $data
        ]);       
        
        
    }
    
    public function update($id,$data)
    { 
        $examSession = $this->entityManager->getRepository(ExamSession::class)->find($id);
        $examSession->setSessionType($data['sessionType']);
        $examSession->setSessionCode($data['sessionCode']);
        $examSession->setSessionName($data['sessionName']);
        $this->entityManager->flush();
        
        return new JsonModel([
            $data
        ]);        
        
    }
    public function delete($id)
    { 
        $examSession = $this->entityManager->getRepository(ExamSession::class)->find($id);
        $this->entityManager->remove($examSession);
        $this->entityManager->flush();
        
        return new JsonModel([
           
        ]);        
        
    }  
    
    
   
}

