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
use Application\Entity\Student;


class ExamRegistrationController extends AbstractRestfulController
{
    private $entityManager;
    
    public function __construct($entityManager) {
        
        $this->entityManager = $entityManager;   
    }
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentView::class)->findAll();
            $i= 0;
            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $registeredStd[$key] = $data;
            }
           /* $output['draw']=1;
            $output['recordsTotal']=sizeof($registeredStd);
            $output['recordsFiltered']=sizeof($registeredStd);
            $output['data'] = $registeredStd;*/
            $output = json_encode($registeredStd,$depth=10000000);
            //$output = array_slice($registeredStd,0,5);
            //$output = $registeredStd;
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $output
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
            //retrieve exam
            $exam = $this->entityManager->getRepository(Exam::class)->findOneByCode($data["examCode"]);
            //retrieve Student involved in the exam
            $student= $this->entityManager->getRepository(Student::class)->findOneByMatricule($data["details"]["matricule"]);

            $examRegistration = new ExamRegistration();
            $examRegistration->setExam($exam);
            $examRegistration->setStudent($student);
            $examRegistration->setAttendance($data["details"]["attendance"]);
            $examRegistration->setNumAnonymat($data["details"]["anonymat"]);

            
            $this->entityManager->persist($examRegistration);
            $this->entityManager->flush();
            
            //Associate students to the exam
            foreach($data["students"] as $std)
            {
                $stdExamRegistration = new ExamRegistration();
                $stdExamRegistration->setExam($examRegistration);
                $student = $this->entityManager->getRepository(Student::class)->findOneByMatricule($std["matricule"]);
                $stdExamRegistration->setStudent($student);
                $this->entityManager->persist($stdExamRegistration);
                $this->entityManager->flush();
            }
            
            
            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $code
            ]);
            
            return $output;
         
        } 
        catch (Exception $ex) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;

        }
    }
    
    public function update($id,$data)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try{
            $data= $data['data'];
            
            $ue =$this->entityManager->getRepository(TeachingUnit::class)->findOneById($data['ue_id']);
            $ue->setName($data['name']);
            $ue->setCode($data['code']);
            
            $ueClasse= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneById($id);
            $ueClasse->setCredits($data['credits']);
            $ueClasse->setHoursVolume($data['hours_vol']);
            $ueClasse->setCmHours($data['cm_hrs']);
            $ueClasse->setTdHours($data['td_hrs']);
            $ueClasse->setTpHours($data['tp_hrs']);
            $ueClasse->setClassOfStudy($this->entityManager->getRepository(ClassOfStudy::class)->findOneById($data['class_id']));

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
