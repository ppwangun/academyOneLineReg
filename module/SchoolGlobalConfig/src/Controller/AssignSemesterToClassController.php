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
use Application\Entity\ClassOfStudy;
use Application\Entity\SemesterAssociatedToClass;
use Application\Entity\AcademicYear;
use Application\Entity\ClassOfStudyHasSemester;



class AssignSemesterToClassController extends AbstractRestfulController
{
    private $entityManager;
    public function __construct($entityManager,$sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        
        
    }
    
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = json_decode($id,true);            
            if(json_last_error() === JSON_ERROR_NONE) { 
               // if(isset($data['acadYrId'])) $acadYr = $this->entityManager->getRepository(AcademicYear::class)->find($data['acadYrId']);
                //else 
                $acadYr = $this->sessionContainer->currentAcadYr;
                $id = $data['classeCode'];
            // JSON is valid
            }
            else{

                $acadYr = $this->sessionContainer->currentAcadYr;
            }

            
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($id);
            $assignedSemToClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("classOfStudy"=>$class,"academicYear"=>$acadYr ));
            $semesters= []; $i =0;
            foreach($assignedSemToClass as $key=>$value)
            {
                $semesters[$i]["id"] = $value->getSemester()->getId();
                $semesters[$i]["code"] = $value->getSemester()->getCode();
                $semesters[$i]["name"] = $value->getSemester()->getName();
                $semesters[$i]["ranking"] = $value->getSemester()->getRanking();
                $semesters[$i]["markCalculationStatus"] = $value->getMarkCalculationStatus();
                $semesters[$i]["sendSmsStatus"] = $value->getSendSmsStatus();
                $i++;

            }

            return new JsonModel([
                $semesters
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }

        
    }

    public function create($data)
    {
       
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            
            $semester = $this->entityManager->getRepository(Semester::class)->findOneById($data["sem_id"]);
            $class = $this->entityManager->getRepository(ClassOfStudy::class)->findOneByCode($data["class_id"]);
            $acadYr = $semester->getAcademicYear();
            
            //Check if the sem is already assigned to class for the current year
             $isSemAssignedToClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findBy(array("semester"=>$semester,"classOfStudy"=>$class,"academicYear"=>$acadYr ));
            
            if($isSemAssignedToClass) 
            {
                $msge ="DATA_ALREADY_EXISTS";
            }
            else{
                $assignSemToClass = new SemesterAssociatedToClass();
                $assignSemToClass->setSemester($semester);
                $assignSemToClass->setClassOfStudy($class);
                $assignSemToClass->setAcademicYear($acadYr);
                $this->entityManager->persist($assignSemToClass);
                $this->entityManager->flush();
                $msge = "DATA_SAVED";
            }
            
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $msge
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }        
        
    }
    
    
    public function delete($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = json_decode($id,true);           
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array("isDefault"=>1));
            $sem = $this->entityManager->getRepository(Semester::class)->findOneBy(array("code"=>$data["sem"],"academicYear"=>$acadYr)); 
            $classe = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["class_id"]);
            $assignedSemToClass = $this->entityManager->getRepository(SemesterAssociatedToClass::class)->findOneBy(array("semester"=>$sem,"classOfStudy"=>$classe,"academicYear"=>$acadYr ));

            if($assignedSemToClass)
            {
                $this->entityManager->remove($assignedSemToClass);
                $this->entityManager->flush();
              
            }
            
            $this->entityManager->getConnection()->commit();  

            return new JsonModel([
               // $semesters
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
        
    }
    
}

