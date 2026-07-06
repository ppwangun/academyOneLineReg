<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace SchoolGlobalConfig\Controller;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\Resource;
use Application\Entity\AcademicYear;
use Application\Entity\Faculty;
use Application\Entity\FacultyHasResource;
use Application\Entity\ClassOfStudy;
use Application\Entity\FieldOfStudy;
use Application\Entity\ResourceCategory;



class CampusResourcesController extends AbstractActionController
{
    private $entityManager;
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    public function newcampusAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery();
            $data = json_decode($data["campus"],true);

       
            $campus = new Resource();
            $campus->setCode($data["campusCode"]);
            $campus->setName($data["campusName"]);
            $campus->setType("CAMPUS");
            $this->entityManager->persist($campus);
                    
            $this->entityManager->flush();       
            
            $campuses = $this->entityManager->getRepository(Resource::class)->findByType(array("campus"));

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($campuses as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $campuses[$key] = $data;
            }

            
           $this->entityManager->getConnection()->commit();
            

           

            return new JsonModel([
              $campuses
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
       
    }
    public function newbuildingAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery(); 
            $campus= json_decode($data["campus"],true); 
            $build= json_decode($data["building"],true);
            
            $campus = $this->entityManager->getRepository(Resource::class)->find($campus["id"]); 
            
       
            $building = new Resource();
            $building->setCode($build["code"]);
            $building->setName($build["name"]);
            $building->setType("BUILDING");
            $building->setResource($campus);
            $this->entityManager->persist($building);
                    
            $this->entityManager->flush();       
            
            $campuses = $this->entityManager->getRepository(Resource::class)->findByType(array("campus"));

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($campuses as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $campuses[$key] = $data;
            }

            
           $this->entityManager->getConnection()->commit();
            

           

            return new JsonModel([
              $campuses
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
    }    
    
 public function newclassroomAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery(); 
            $building= json_decode($data["building"],true); 
            $class= json_decode($data["classroom"],true);
         
            $building = $this->entityManager->getRepository(Resource::class)->find($building["id"]); 
            
      
            $classroom = new Resource();
            $classroom->setCode($class["code"]);
            $classroom->setName($class["name"]);
            $classroom->setType("CLASSROOM");
            $classroom->setResource($building);
            
            $this->entityManager->persist($classroom);
                    
            $this->entityManager->flush();       
            $this->entityManager->getConnection()->commit();
            $classrooms = $this->entityManager->getRepository(Resource::class)->findByType(array("classroom"));

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($classrooms as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $classrooms[$key] = $data;
            }

            
           
            

           

            return new JsonModel([
              $classrooms
             ]);

        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $ex;
        }
    }        
    public function getBuildingsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        $data = $this->params()->fromQuery();
        try
        {           
        $campus = $this->entityManager->getRepository(Resource::class)->find($data["campusId"]); 
        $buildings = $this->entityManager->getRepository(Resource::class)->findBy(['resource'=>$campus,'type'=>"BUILDING"]);

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($buildings as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $buildings[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $buildings 
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    }
    
    public function getClassroomsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        $data = $this->params()->fromQuery();
        try
        {           
        $building = $this->entityManager->getRepository(Resource::class)->find($data["buildingId"]); 
        $classromms = $this->entityManager->getRepository(Resource::class)->findBy(['resource'=>$building,'type'=>"CLASSROOM"]);

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($classromms as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $classromms[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $classromms 
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    }

    
    public function getCampusesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $campuses = $this->entityManager->getRepository(Resource::class)->findByType(array("campus"));

           // $semesters = $this->entityManager->getRepository(FieldOfStudy::class)->findAll();
            foreach($campuses as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $campuses[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();    
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $campuses
                ]);
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    } 
    
    public function getBuildingAssignedToFacultiesAction()
    {
        try{
            $this->entityManager->getConnection()->beginTransaction();
            $data = $this->params()->fromQuery();            
            
            $faculties = $this->entityManager->getRepository(Faculty::class)->findAll(); 
            $building = $this->entityManager->getRepository(Resource::class)->find($data["buildingId"]);
            
            foreach($faculties as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $faculties[$key] = $data;
            }            
            
            foreach($faculties as $key=>$value)
            {                
                $faculty = $this->entityManager->getRepository(Faculty::class)->find($value["id"]);
                $buildingAssignedToFacuty = $this->entityManager->getRepository(FacultyHasResource::class)->findBy(["faculty"=>$faculty,"resource"=>$building]);
                ($buildingAssignedToFacuty)? $faculties[$key]["status"]=1 : $faculties[$key]["status"]=0 ;
            }
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
                $faculties
                ]);           
            
            $this->entityManager->getConnection()->comit();
        }
        catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }        
        
    }    
    
    public function getClassroomsAssignedToClassAction()
    {
        try{
            $this->entityManager->getConnection()->beginTransaction();
            $data = $this->params()->fromQuery(); 
            
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["id"]);
            $faculty= $classOfStudy->getDegree()->getFieldStudy()->getFaculty(); 

            $buildingAssignedToFacuty = $this->entityManager->getRepository(FacultyHasResource::class)->findBy(["faculty"=>$faculty]);
            

            $classrooms =[]; 
            $i=0;
            foreach($buildingAssignedToFacuty as $key=>$value)
            { 
                $classroom = $this->entityManager->getRepository(Resource::class)->findBy(["resource"=>$value->getResource(),"type"=>'CLASSROOM']); 
                foreach($classroom as $class)
                { 
                    $hydrator = new ReflectionHydrator(); 
                    $data = $hydrator->extract($class);
                    $data['campus'] = "";

                    $classrooms[$i] = $data; $i++;
                }
            }            
            
    //$categories = $this->entityManager->getRepository(ResourceCategory::class)->findBy([],["id"=>"ASC"]);
    //$tree = $this->buildTree($categories);
    
         

            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
               // $tree
                $classrooms
                ]);           
            
            $this->entityManager->getConnection()->comit();
        }
        catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }        
        
    }
    
    public function assignBuildingToFacultiesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromQuery();   
            $data = json_decode($data["data"],true); 
            
            $building= $this->entityManager->getRepository(Resource::class)->find($data["buildingId"]);

            foreach($data["faculties"] as $faculty)
            {
                
                $fac = $this->entityManager->getRepository(Faculty::class)->find($faculty["id"]);            
                if(isset($faculty["status"]))
                {
                    $facResource = $this->entityManager->getRepository(FacultyHasResource::class)->findBy(["faculty"=>$fac,"resource"=>$building]);
                    if($faculty["status"] && !$facResource)
                    {
                        $assignBuildingTofactuly = new FacultyHasResource();
                        $assignBuildingTofactuly->setFaculty($fac);
                        $assignBuildingTofactuly->setResource($building); 
                        $this->entityManager->persist($assignBuildingTofactuly); 
                    }
                }
                
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit(); 
            
            
           
   
            
            return new JsonModel([
               // $this->getFaculty($data["school_id"])
             
                ]);
         
        } catch (Exception $ex) {
           $this->entityManager->getConnection()->rollBack();
           throw $e;
        }
        
        
    } 
    
public function buildTree(array $elements, $parentId = null, $level=0): array {
    $branch = [];
    

    foreach ($elements as $element)
    { 
    
        $elementParent = $element->getParent();
        $elementParentId = $elementParent ? $elementParent->getId() : null;
      
        if ($elementParentId ===$parentId) {
            $children =$this->buildTree($elements,$element->getId(),$level+1);
            $branch[] = [
                'id' =>$element->getId(),
                'name' => $element->getName(),
                'children' =>$children,
                'isLeaf' => empty($children),
                'level' => $level
            ];
        }
    }
        

        return $branch;

        
}    

    
}

