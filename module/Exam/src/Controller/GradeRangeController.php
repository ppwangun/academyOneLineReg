<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\Semester;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Exam;
use Application\Entity\ExamRegistration;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\TrainingCurriculum;
use Application\Entity\Grade;
use Application\Entity\GradeValueRange;


class GradeRangeController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }

    public function get($id)
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $gradeRanges = $this->entityManager->getRepository(GradeValueRange::class)->findByGrade($id);
            
            foreach($gradeRanges as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $gradeRanges[$key] = $data;
            }
            $output = new JsonModel([
                    $gradeRanges
            ]);
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $grades = $this->entityManager->getRepository(Grade::class)->findAll();

            foreach($grades as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $grades[$key] = $data;
            }

            $this->entityManager->getConnection()->commit();
 
            $output = new JsonModel([
                    $grades
            ]);
            //var_dump($output); //exit();
            return $output;       }
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
            $msge =false;
            $grade = new GradeValueRange();
            $grade->setMinsur20($data["min20"]);
            $grade->setMaxsur20($data["max20"]);
            $grade->setMinsur100($data["min100"]);
            $grade->setMaxsur100($data["max100"]);
            $grade->setGradeValue($data["value"]);
            $grade->setGradePoints($data["points"]);
            $grade->setGrade($this->entityManager->getRepository(Grade::class)->findOneById($data["grade_id"]));
            $this->entityManager->persist($grade);
            $this->entityManager->flush();
            
            $hydrator = new ReflectionHydrator();
            $data = $hydrator->extract($grade);
            
            $msge=true;
            
            $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   $data
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
            $grade = $this->entityManager->getRepository(GradeValueRange::class)->findOneById($id);  
            $this->entityManager->remove($grade);
            $this->entityManager->flush();
            
           $this->entityManager->getConnection()->commit();
            
            return new JsonModel([
                   
            ]); 
        }
        catch(Exception $e)
        {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }        
    }
    

}
