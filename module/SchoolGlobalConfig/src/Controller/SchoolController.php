<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;

use Laminas\View\Model\JsonModel;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Application\Entity\School;

use Laminas\Hydrator\ReflectionHydrator;


class SchoolController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
      $this->entityManager = $entityManager;  
    }
    
    public function create($data)
    {
        $school = new School();
        $school->setName($data['name']);
        $school->setCode($data['code']);
        
        $this->entityManager->persist($school);
        $this->entityManager->flush();
        
        $data['id']=$school->getId();
        return new JsonModel([
            $data
        ]);        
    }
    
        
    public function getList()
    {
        $school = $this->entityManager->getRepository(School::class)->findAll();
        
        if(sizeof($school)>0)
        {
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($school[0] );
            $school = $data;
        }
        
               
        //$acadyrs = $acadyrs->exportTo(json);
        //$hydrator = new ReflectionHydrator();
        //$data = $hydrator->extract($acadyear);

        return new JsonModel([
           $school
        ]);
        
    }
 
}

