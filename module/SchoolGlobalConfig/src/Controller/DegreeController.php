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
use Application\Entity\Degree;
use Application\Entity\FieldOfStudy;
use Application\Entity\Speciality;
use Application\Entity\SpecialityOption;
use Application\Entity\CourseCategory;
use Application\Entity\DegreeHasCourseCategory;

class DegreeController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    
 
    
    public function get($id)
    {
        $data["cycle_id"] = -1;
        
        $degree = $this->entityManager->getRepository(Degree::class)->findOneById($id);
        $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findOneBy(array("degree"=>$degree));
  
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($degree);
            
            ($degree->getFieldStudy())?$data["fil_id"]=$degree->getFieldStudy()->getId():$data["fil_id"]=-1;
            ($degree->getSpeciality())?$data["spe_id"]=$degree->getSpeciality()->getId():$data["spe_id"]=-1;
            ($degree->getSpecialityOption())?$data["opt_id"]=$degree->getSpecialityOption()->getId():$data["opt_id"]=-1;
            $degreeCategory?$data["cycle_id"]=$degreeCategory->getCourseCategory()->getId():$data["cycle_id"]=-1;

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
            
            $degrees = $this->entityManager->getRepository(Degree::class)->findAll(array('status'=>1));
            
           
            foreach($degrees as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $degrees[$key] = $data;
            }
           
            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $degrees
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
            
            $degree= new Degree();
            $degree->setName($data['name']);
            $degree->setCode($data['code']);
            $degree->setStatus($data['status']);
            $degree->setIsCoreCurriculum($data['isCoreCurriculum']);
            
 
            $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->findOneById($data['fil_id']);
            $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($data['cycle_id']);
            if(isset($data['spe_id']))
            {
                $spe = $this->entityManager->getRepository(Speciality::class)->findOneById($data['spe_id']);
                $degree->setSpeciality($spe);
            }
            if(isset($data['opt_id']))
            {
                $opt = $this->entityManager->getRepository(SpecialityOption::class)->findOneById($data['opt_id']);
                $degree->setSpecialityOption($opt);
            }
            
            $degree->setFieldStudy($filiere);
            
            $this->entityManager->persist($degree);
            $degreeCategory = new DegreeHasCourseCategory();
            $degreeCategory->setDegree($degree);
            $degreeCategory->setFieldOfStudy($filiere);
            $degreeCategory->setCourseCategory($cycle);
            
            $this->entityManager->persist($degree);
            $this->entityManager->persist($degreeCategory);
            $this->entityManager->flush();
            
            //$degrees = $this->getList();
            $degrees = $this->entityManager->getRepository(Degree::class)->findAll();
            foreach($degrees as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $degrees[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                $degrees
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
            $degree = $this->entityManager->getRepository(Degree::class)->findOneById($id);
            if($degree)
            {
                $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findOneBy(array("degree"=>$degree));
                if($degreeCategory) $this->entityManager->remove($degreeCategory);
                $this->entityManager->remove($degree);
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
            
            $degree =$this->entityManager->getRepository(Degree::class)->find($id);
            $filiere = $this->entityManager->getRepository(FieldOfStudy::class)->find($data['fil_id']);
            $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($data['cycle_id']);
            $degree->setName($data['name']);
            $degree->setCode($data['code']);
            $degree->setStatus($data['status']);
            $degree->setIsCoreCurriculum($data['isCoreCurriculum']);
            $degree->setFieldStudy($filiere);
            
            $degreeCategory = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findOneBy(array("degree"=>$degree));
            if($degreeCategory)
            {
                $degreeCategory->setFieldOfStudy($filiere);
                $degreeCategory->setCourseCategory($cycle);
                
            }
            else
            {
                $degreeCategory = new DegreeHasCourseCategory();
                $degreeCategory->setDegree($degree);
                $degreeCategory->setFieldOfStudy($filiere);
                $degreeCategory->setCourseCategory($cycle); 
                $this->entityManager->persist($degreeCategory);
            }
            if(isset($data['spe_id']))
            {
                $spe = $this->entityManager->getRepository(Speciality::class)->findOneById($data['spe_id']);
                $degree->setSpeciality($spe);
            }
            if(isset($data['opt_id']))
            {
                $opt = $this->entityManager->getRepository(SpecialityOption::class)->findOneById($data['opt_id']);
                $degree->setSpecialityOption($opt);
            }            
            
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
