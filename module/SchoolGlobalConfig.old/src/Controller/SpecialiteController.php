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
use Application\Entity\FieldOfStudy;
use Application\Entity\Faculty;
use Application\Entity\Department;
use Application\Entity\Speciality;

class SpecialiteController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        $spe= $this->entityManager->getRepository(Speciality::class)->find($id);

            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($spe);
           
            $filiere = $spe->getFieldOfStudy();
            $data["fieldOfStudy"] = null;
            $data['fil_id'] = $filiere->getId();
            $data['fac_id'] = $filiere->getFaculty()->getId();
            ($filiere->getDepartment())? $data['dpt_id'] = $filiere->getDepartment()->getId():$data['dpt_id']=-1;            

        return new JsonModel([
                $data
        ]);
        
        //return $faculties;
    }
    public function getList()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $spes = $this->entityManager->getRepository(Speciality::class)->findBy([],array("name"=>"ASC"));
            
            foreach($spes as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $spes[$key] = $data;
                $filiere = $value->getFieldOfStudy();
                $spes[$key]["fieldOfStudy"] = null;
                $spes[$key]['fil_id'] = $filiere->getId();
               $spes[$key]['fac_id'] = $filiere->getFaculty()->getId();
               ($filiere->getDepartment())? $spes[$key]['dpt_id'] = $filiere->getDepartment()->getId():$spes[$key]['dpt_id']=-1;
            }
            $this->entityManager->getConnection()->commit();

            return new JsonModel([
                    $spes
            ]);
            }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
        //return $faculties;
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
            
            $spe= new Speciality();
            $spe->setName($data['name']);
            $spe->setCode($data['code']);
            (isset($data['status']))?$spe->setStatus($data['status']):$spe->setStatus(0);

            $fil = $this->entityManager->getRepository(FieldOfStudy::class)->find($data['fil_id']);
            $spe->setFieldOfStudy($fil);

            $this->entityManager->persist($spe);
            $this->entityManager->flush();
            
            $filieres = $this->getList();
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $filieres
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
            $spe = $this->entityManager->getRepository(Speciality::class)->find($id);
            if($spe)
            {
                
                $this->entityManager->remove($spe);
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
        try
        {
            $spe = $this->entityManager->getRepository(Speciality::class)->find($id);
            if($spe)
            {

                $spe->setName($data['name']);
                $spe->setCode($data['code']);
                $spe->setStatus($data["status"]); 
                $fil = $this->entityManager->getRepository(FieldOfStudy::class)->find($data['fil_id']);
  
                $spe->setFieldOfStudy($fil); 
                $this->entityManager->flush();
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
}
