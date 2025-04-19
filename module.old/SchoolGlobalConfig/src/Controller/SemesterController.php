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
use Application\Entity\AcademicYear;



class SemesterController extends AbstractRestfulController
{
    private $entityManager;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
           
            $query = $this->entityManager->createQuery('SELECT c.id,c.code,c.name FROM Application\Entity\Semester c'
                    .' JOIN c.academicYear a'
                    .' WHERE c.id LIKE :id'
                    .' AND a.isDefault = 1'
                    );
            $query->setParameter('id', '%'.$id.'%');
            $sem = $query->getResult()[0];

           

            return new JsonModel([
                $sem
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
            $academic_year = $this->entityManager->getRepository(AcademicYear::class)->findOneBy(array('isDefault'=>'1'));
            $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($academic_year);
           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($semesters as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $semesters[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $semesters
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    }
    public function create($data)
    {
        $semester = new Semester();
        $semester->setCode($data['code']);
        $semester->setName($data['name']);
        $semester->setStartingDate(new \DateTime($data['startingDate']));
        $semester->setEndingDate(new \DateTime($data['endingDate']));
        $acadyear =  $this->entityManager->getRepository(AcademicYear::class)->findOneById($data['acad_id']);
        $semester->setAcademicYear($acadyear);
        
        $this->entityManager->persist($semester);
        $this->entityManager->flush();
       
        return new JsonModel([
            $data
        ]);       
        
        
    }
    
}

