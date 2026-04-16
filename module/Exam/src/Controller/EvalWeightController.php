<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\CalculationRule;
use Application\Entity\CalculationRulesWeight;
use Application\Entity\ExamType;



class EvalWeightController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }

    public function evalWeightMgtAction()
    {

          $view = new ViewModel([
             "rules"=>$this->listAllRules()
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    } 
    
    
    public function addRuleAction()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {  
            $request = $this->getRequest();
            $data = $request->getContent(); 
            $data = json_decode($data,true); 
            
            
            $rule = new CalculationRule();
            $rule->setCombination($data["ruleName"]);
            $rule->setIsDefault(1);
            
            
            
            
            foreach($data["weights"] as $key=>$value)
            {
                $examType = $this->entityManager->getRepository(ExamType::class)->findOneByCode($value["examType"]);
                $weight = new CalculationRulesWeight();
                $weight->setCalculationRule($rule);
                $weight->setExamType($examType);
                $weight->setRuleweightvalue($value["weightValue"]);
                $rule->addCalculationRulesWeight($weight);
                                    
            }
          /*  $grades = $this->entityManager->getRepository(Grade::class)->findAll();

            foreach($grades as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $grades[$key] = $data;
            }*/
            $this->entityManager->persist($rule);
            $this->entityManager->flush();

           // $this->entityManager->commit();
 
            $output = new JsonModel(
                    $this->listAllRules()
            );
            //var_dump($output); //exit();
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
    
    private function listAllRules()
    {
           // $listOfRules = $this->entityManager->getRepository(Calculationrule::class)->find($id);
           return  $ueExam = $this->entityManager->createQueryBuilder()->select( 'cr','cosh','crw','et')
                    ->from('Application\Entity\CalculationRule','cr')
                   ->leftjoin('cr.classOfStudyHasSemester','cosh')
                    ->leftjoin('cr.calculationRulesWeight','crw')
                   ->leftjoin('crw.examType','et')
                   

                    ->getQuery()
            ->getArrayResult();
        
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
            $grade->setResultStatus($data["resultStatus"]);
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

    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {   $data = $data['grade'];
            $msge =false;
            $grade = $this->entityManager->getRepository(GradeValueRange::class)->find($id);
            $grade->setMinsur20($data["min20"]);
            $grade->setMaxsur20($data["max20"]);
            $grade->setMinsur100($data["min100"]);
            $grade->setMaxsur100($data["max100"]);
            $grade->setGradeValue($data["value"]);
            $grade->setGradePoints($data["points"]);
            $grade->setResultStatus($data["resultStatus"]);

       
            $this->entityManager->flush($grade);
            
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
