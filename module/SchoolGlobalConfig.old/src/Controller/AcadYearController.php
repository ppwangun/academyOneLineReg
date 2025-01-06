<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;

use Application\Entity\AcademicYear;
use Laminas\Hydrator\ReflectionHydrator;
//use ICanBoogie\DateTime;


class AcadYearController extends AbstractRestfulController
{
     private $entityManager;
     private $acadyear;
    
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function notFound()
    {
        $this->getResponse()->setStatusCode(404);
        return new JsonModel([
            'message' => 'Not found'
        ]);
    }
    
    public function get($id)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
        $acadyear =  $this->entityManager->getRepository(AcademicYear::class)->findOneById($id);
       

            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($acadyear );
       
        return new JsonModel([
            $data
        ]);
        }  catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
        
    }
    
    public function getList() {
        $acadyrs =  $this->entityManager->getRepository(AcademicYear::class)->findAll(Array(),Array("id"=>'DESC'));
        
      
        
        foreach($acadyrs as $key=>$value)
        {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                
                $acadyrs[$key] = $data;
        }
        
        //$acadyrs = $acadyrs->exportTo(json);
        //$hydrator = new ReflectionHydrator();
        //$data = $hydrator->extract($acadyear);

        return new JsonModel([
            'acadyrs'=>$acadyrs
        ]);
    }
    
    public function create($data)
    {
        $this->acadyear = new AcademicYear(); 
        
        $this->acadyear->setCode($data['code']);
        $this->acadyear->setName($data['name']);
        $repo= $this->entityManager->getRepository(AcademicYear::class)->findAll();
        //As we should have only one record withe default value setted as true,
        //We firs set the defaul value of all record to 0 
        
       
        if($data['isDefault'])
        {
            foreach($repo as $year)
            {
                $year->setIsDefault(0);
                $this->entityManager->persist($year);
                $this->entityManager->flush();
           }
           $this->acadyear->setIsDefault(1);
            
        }
        else $this->acadyear->setIsDefault(0);
         
        $this->acadyear->setStartingDate(new \DateTime($data['startingDate']));
        $this->acadyear->setEndingDate(new \DateTime($data['endingDate']));
        $this->acadyear->setAdmissionStartingDate(new \DateTime($data['admissionStartingDate']));
        $this->acadyear->setAdmissionEndingDate(new \DateTime($data['admissionEndingDate']));
        $this->acadyear->setAdminRegistrationStartingDate(new \DateTime($data['adminRegistrationStartingDate']));
        $this->acadyear->setAdminRegistrationEndingDate(new \DateTime($data['adminRegistrationEndingDate']));
        $this->acadyear->setStatus($data['status']);
      
        $this->entityManager->persist($this->acadyear);
        $this->entityManager->flush();
        
        $data['acad_id'] = $this->acadyear->getId();
        
      // echo"je suis dedans"; exit;
        return new JsonModel([
            'acadyear' => $data
        ]);
    }
    
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
            $data= $data['data'];            
          
            $acadyear =$this->entityManager->getRepository(AcademicYear::class)->findOneById($id);
            $acadyear->setName($data['name']);
            $acadyear->setCode($data['code']);
            
            $repo= $this->entityManager->getRepository(AcademicYear::class)->findAll();

            //As we should have only one record withe default value setted as true,
            //We firs set the defaul value of all record to 0 
            if($data['isDefault'])
            {
                foreach($repo as $year)
                {
                    $year->setIsDefault(0);
                    $this->entityManager->persist($year);
                    $this->entityManager->flush();
               }
               $acadyear->setIsDefault(1);

            }else $acadyear->setIsDefault(0);
            
            $acadyear->setStartingDate(new \DateTime($data['startingDate']));
            $acadyear->setEndingDate(new \DateTime($data['endingDate']));
            $acadyear->setAdmissionStartingDate(new \DateTime($data['admissionStartingDate']));
            $acadyear->setAdmissionEndingDate(new \DateTime($data['admissionEndingDate']));
            $acadyear->setAdminRegistrationStartingDate(new \DateTime($data['adminRegistrationStartingDate']));
            $acadyear->setAdminRegistrationEndingDate(new \DateTime($data['adminRegistrationEndingDate']));
            
            
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
    
    public function delete($id)
    {

        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneById($id);

                
                $this->entityManager->remove($acadyr );
              
                $this->entityManager->flush();
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
}
