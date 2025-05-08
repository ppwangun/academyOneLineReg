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

class FiliereController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        $filiere= $this->entityManager->getRepository(FieldOfStudy::class)->find($id);

            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($filiere);
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
            $filieres = $this->entityManager->getRepository(FieldOfStudy::class)->findBy([],array("name"=>"ASC"));
            
            foreach($filieres as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $filieres[$key] = $data;
                $filieres[$key]["faculty"] = null;
                $filieres[$key]["department"] = null;
                $filieres[$key]['fac_id'] = $value->getFaculty()->getId();
               ($value->getDepartment())? $filieres[$key]['dpt_id'] = $value->getDepartment()->getId():$filieres[$key]['dpt_id']=-1;
            }
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
            
            $filiere= new FieldOfStudy();
            $filiere->setName($data['name']);
            $filiere->setCode($data['code']);
            (isset($data['status']))?$filiere->setStatus($data['status']):$filiere->setStatus(0);
            if(isset($data['fac_id']))
                $faculty = $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
            if (isset($data['dpt_id']))
            {
                $dpt = $this->entityManager->getRepository(Department::class)->find($data['dpt_id']);
                $filiere->setDepartment($dpt);
            }
            $filiere->setFaculty($faculty);
            
            $this->entityManager->persist($filiere);
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
            $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->findOneById($id);
            if($filiere)
            {
                
                $this->entityManager->remove($filiere);
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
            $fil = $this->entityManager->getRepository(FieldOfStudy::class)->findOneById($id);
            if($fil)
            {

                $fil->setName($data['name']);
                $fil->setCode($data['code']);
                $fil->setStatus($data["status"]); 
                $faculty = $this->entityManager->getRepository(Faculty::class)->find($data['fac_id']);
         
                $dpt = $this->entityManager->getRepository(Department::class)->find($data['dpt_id']);


                $fil->setDepartment($dpt);   
                $fil->setFaculty($faculty); 
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
