<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Teacher\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\Countries;
use Application\Entity\States;
use Application\Entity\Cities;
use Application\Entity\Faculty;
use Application\Entity\AcademicRanck;
use Application\Entity\AcademicYear;
use Application\Entity\Teacher;
use Application\Entity\ClassOfStudy;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\Semester;
use Application\Entity\Contract;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\User;
use Application\Entity\TeacherPaymentBill;
use Application\Entity\TeacherPaymentBillSumary;
use Application\Entity\TeacherPaymentRate;
use Application\Entity\PaymentTeachingAssignmentMethod;
use Application\Entity\CurrentYearUesAndSubjectsView;
use Application\Entity\ContractFollowUp;
use Application\Entity\AllContractsView;
use Application\Entity\CourseScheduled;
use Njine\Odoo\Synchronisation;
use Application\Entity\OdooSettings;
use Application\Entity\StudentAttendance;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\Student;
use Application\Entity\Resource;
use Application\Entity\DegreeHasCourseCategory;
use Application\Entity\CourseCategory;
use Application\Entity\Degree;
use Application\Entity\AcademicRanckPaymentRates;
use Application\Entity\School;

use ICanBoogie\DateTime;



use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Student\Service\StudentManager;
use PhpOffice\PhpSpreadsheet\Reader\Csv;




class IndexController extends AbstractActionController
{
    private $sessionContainer;
    private $entityManager;
    private $crtAcadYr;
    
    /**
     * Constructor.
     */    
    public function __construct($entityManager,$sessionContainer)
    {

        $this->sessionContainer = $sessionContainer;
        $this->entityManager = $entityManager;
        $this->crtAcadYr = $sessionContainer->currentAcadYr;
    }
    public function indexAction()
    {
        
        //redirect to the login action of authController
        return  $this->redirect()->toRoute('login');

    }
    
    public function teacherListAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }
    public function teacherFollowUpAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    } 
    public function programmingtplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }  
    
    public function vacationPaymentMethodAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }    
    
    public function teacherAssignedSubjectsTplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }   
    public function subjectBillingTplAction()
    {
        $view = new ViewModel([
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;        
    }    
    public function teacherAssignedSubjectsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            $ue = []; $ue_1 = [];
            
           // $acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            //$acadYearId = $acadYear->getId();  
            $acadYearId = $this->crtAcadYr->getId();
            
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {
                //collect all courses affected to any semester
                    $query = $this->entityManager->createQuery('SELECT c.id, c.semId as sem_id,c.semester as sem_code,c.nomUe as name,c.codeUe as code, c.classe as class,c.credits, c.totalHrs AS hoursVolume ,c.cmHrs as cm_hrs,c.tpHrs as tp_hrs, c.tdHrs as td_hrs, c.teacherName as lecturer FROM Application\Entity\AllContractsView c '
                            .'WHERE c.acadYrId = ?1'
                        );
                $query->setParameter(1,$this->crtAcadYr->getId());
                $ue= $query->getResult(); 
              
                //collect all courses affected to any semester
           /* $query = $this->entityManager->createQuery('SELECT con.id, c.id as ue_class_id,s.id as sem_id,s.code as sem_code,t.subjectName as name,t.subjectCode as code,c1.code as class,c.subjectWeight as credits, c.subjectHours as hoursVolume ,c.subjectCmHours  as cm_hrs,c.subjectTpHours  as tp_hrs, c.subjectTdHours  as td_hrs, teach.name as lecturer FROM Application\Entity\ClassOfStudyHasSemester c '
                        . 'JOIN c.classOfStudy c1 JOIN c.subject t JOIN c.semester s JOIN s.academicYear a JOIN t.contract con JOIN con.teacher teach    WHERE a.isDefault = 1 '
                        . 'AND c.status = 1 ');  
            $ue_1= $query->getResult();   */ 
             $ue = array_merge($ue,$ue_1);
               
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        //collect all courses affected to any semester
                        $query = $this->entityManager->createQuery('SELECT c.id, c.semId as sem_id,c.semester as sem_code,c.nomUe as name,c.codeUe as code, c.classe as class,c.credits, c.totalHrs AS hoursVolume ,c.cmHrs as cm_hrs,c.tpHrs as tp_hrs, c.tdHrs as td_hrs, c.teacherName as lecturer FROM Application\Entity\AllContractsView c '
                                . 'AND c.classe= ?1 AND c.academicYear = :acadYearId');
                        $query->setParameter(1, $classe->getClassOfStudy()->getCode());
                        $query->setParameter('acadYearId',$acadYearId);
                        $ue_1= $query->getResult(); 
                        $ue = array_merge($ue,$ue_1);
                        
                    }
                }
            }
            for($i=0;$i<sizeof($ue);$i++)
            {
               // $ue[$i]['name']= utf8_encode($ue[$i]['name']);

            }            

            $this->entityManager->getConnection()->commit();
            return new JsonModel([
                  $ue  
                
            ]);  
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
    }
    public function unitFollowUpAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data = $this->params()->fromPost(); // 
           
        
            $contract = $this->entityManager->getRepository(Contract::class)->find($data["contractId"]);    
            $progression = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);  
   
            foreach($progression as $key=>$value)
            {
                $hydrator = new ReflectionHydrator(); 
                $data = $hydrator->extract($value);
                //$countries[$key] = $data; 
            }     
            $totalCm = 0;
            $totalTd = 0;
            $totalTp = 0;
            foreach($progression as $value)
            { 
                if($value->getLectureType() == "CM") 
                    $totalCm += $value->getTotalTime();
                if($value->getLectureType() == "TD")
                    $totalTd+= $value->getTotalTime();
                if($value->getLectureType() == "TP")
                    $totalTp+= $value->getTotalTime();
                
                $total = $totalCm + $totalTd + $totalTp;
                
            }


            $dataOuput["cm"]["total"] = $contract->getCmHrs();
            $dataOuput["cm"]["progress"] = $totalCm; 
            $dataOuput["td"]["total"] = $contract->getTdHrs();
            $dataOuput["td"]["progress"] = $totalTd; 
            $dataOuput["tp"]["total"] = $contract->getTpHrs();
            $dataOuput["tp"]["progress"] = $totalTp;
            $total_prevu =  $contract->getCmHrs()+  $contract->getTdHrs() + $contract->getTpHrs() ;
            $total_real = $totalCm + $totalTd + $totalTp;  
            if($total_prevu==0) 
                $percentage = 0;
            else
                $percentage = ($total_real/$total_prevu)*100;
            $dataOuput["percentage"] = $percentage; 
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $dataOuput
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        } 

    }    
    public function acadranktplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }    
    public function newAcadRankAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }    
    
    public function newteachertplAction()
    {
        
        $view =  new ViewModel([

            'userName' => $this->sessionContainer->userName
        ]);
        
        $view->setTerminal(true);

        return $view;  

    }
    
    public function newTeacherFormAssetsAction()
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $countries = $this->entityManager->getRepository(Countries::class)->findAll(); 
            $faculties = $this->entityManager->getRepository(Faculty::class)->findBy([],array("name"=>"ASC"));
            $rank =      $this->entityManager->getRepository(AcademicRanck::class)->findBy([],array("name"=>"ASC")); 
            $diplomas = [["name"=>"AGGREGATION"],["name"=>"HDR"],["name"=>"DOCTORAT D'ETAT"],["name"=>"DOCTORAT 3ime CYCLE"],["name"=>"CES"],["name"=>"DES"],
                ["name"=>"PHD"],["name"=>"MSC"],["name"=>"DEA"],["name"=>"DIPES 2"],["name"=>"DIPET 2"],["name"=>"INGENIEUR"],["name"=>"MASTER PRO"],["name"=>"MASTER II"],["name"=>"TECHNICIEN SUPERIEUR"],["name"=>"CERTIFICATION"],["name"=>"VALIDATION DES ACQUIS PRO"]];
           
            
            foreach($countries as $key=>$value)
            {
                $hydrator = new ReflectionHydrator(); 
                $data = $hydrator->extract($value);
                $countries[$key] = $data; 
            }
            foreach($faculties as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $faculties[$key] = $data;
            }     
            foreach($rank as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $rank[$key] = $data;
            }             
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                'countries'=>$countries,
                'diplomas'=>$diplomas,
                'establishments'=>$faculties,
                'grades'=>$rank
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        } 

    } 
    public function citiesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $countryID= $this->params()->fromQuery('id', 'default_val'); 
            $country = $this->entityManager->getRepository(Countries::class)->find($countryID);

            $regions = $this->entityManager->getRepository(States::class)->findByCountry($country);
            foreach($regions as $region)
            {
                $cities_1 = $this->entityManager->getRepository(Cities::class)->findByState($region); 
                $cities = array_merge($cities,$cities_1);
            }
            
    
            foreach($cities as $key=>$value)
            {
              
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $cities[$key] = $data;
            }
               usort($cities, function($a, $b)
                    {
                        return strnatcmp($a['name'], $b['name']);
                    }
                ); 
              
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $cities
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }    

    public function assignSubjectToTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $data= $this->params()->fromPost();            
            $proceeByForce =(int) $data["proceedByForce"];              
            $flag = 0;
            //$data = json_decode($data,true);
     
            $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacherid']);
            $acadYear = $this->entityManager->getRepository(AcademicYear::class)->find($this->crtAcadYr->getId());
           // $acadYear = $this->crtAcadYr;
   
                foreach($data["subjects"] as $key=>$value)
                { 
                    $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->find($value["id"]); 
                    $unit = null;
                    $subject= null;
                    $contract = null;
                
                   if($coshs->getTeachingUnit()) 
                    {            
                        $unit = $coshs->getTeachingUnit();
                        $tpHrs = $coshs->getTpHours();
                        $cmHrs = $coshs->getCmHours();
                        $tdHrs = $coshs->getTdHours();                      
                        
                        $contract = $this->entityManager->getRepository(Contract::class)->findBy(["academicYear"=>$acadYear,"teachingUnit"=>$unit]);


                    }
                   if($coshs->getSubject()) 
                    {  
                        $unit = null;
                        $subject = $coshs->getSubject();
                        $tpHrs = $coshs->getSubjectTpHours();
                        $tdHrs = $coshs->getSubjectTDHours();
                        $cmHrs = $coshs->getSubjectCmHours();
                        
                        $contract = $this->entityManager->getRepository(Contract::class)->findBy(["academicYear"=>$acadYear,"subject"=>$subject]);


                    } 
                      
      
                        $totalHoursAffected = 0;
                        $courseHoursVolume = 0; 
                        ($coshs->getSubject())?$courseHoursVolume = $coshs->getSubjectHours():$courseHoursVolume = $coshs->getHoursVolume();  

                        
                        //calculate the total time already affected
                        foreach($contract as $c) $totalHoursAffected+=$c->getVolumeHrs();                         
                        //check whetehr or not the sbject is already 
                       
                        //non affectd time
                        $nonAffectedTime = $courseHoursVolume-$totalHoursAffected;
                           
                                $contractSize = sizeof($contract); 
                             /*   if($contractSize<10)
                                $contractSize= str_pad($contractSize,4,0,STR_PAD_LEFT);
                                else if ($contractSize<100)
                                    $contractSize = str_pad($contractSize,3,0,STR_PAD_LEFT);
                                else if ($contractSize<1000) 
                                    $contractSize = str_pad($contractSize,2,0,STR_PAD_LEFT);*/

                               
                              //  $faculty = $teacher->getFaculty()->getCode(); 
                               // $refNum = $acadYear->getCode()."/".$faculty."/".$contractSize; 
                               
                                $refNum =str_pad($contractSize, 6, "0", STR_PAD_LEFT)."/".date('Y');  
                               $hrToAffect = intval($value["totalHrs"]);
                               
                      
                        if(isset($data["partialAttribution"])&&$data["partialAttribution"]&&$nonAffectedTime>=intval($value["volumeHrs"]) )
                        {
                            $hrToAffect = intval($value["volumeHrs"]);
                            if($hrToAffect<=0) return  new JsonModel(["ERROR"=>true]);
                            
                           // foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                           // if(sizeof($contract)<=0) $contract = new Contract();
                            

                        }
                        elseif((isset($data["partialAttribution"])&&$data["partialAttribution"]&&$nonAffectedTime<=intval($value["volumeHrs"]) ))
                        {
                            $hrToAffect = $nonAffectedTime;
                            if($hrToAffect<=0) return  new JsonModel(["ERROR"=>true]);
                                                     
                           // foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                           /// if(sizeof($contract)<=0) $contract = new Contract();

                                                        
                        }

                        elseif($proceeByForce && !$data["partialAttribution"])
                        {
                            foreach($contract as $key=>$con){$this->entityManager->remove($con);array_splice($contract, $key);}
                            $this->entityManager->flush();
                           

                        }
                        elseif($hrToAffect<=0) return  new JsonModel(["ERROR"=>true]);
                        elseif(sizeof($contract)>0)
                        {
                            if(!$proceeByForce) return  new JsonModel([false]);
                        }                        
                        //elseif(!$proceeByForce) return  new JsonModel([false]);
                        
                        

                                $contract = new Contract();
                                $contract->setAcademicYear($acadYear);
                                $contract->setTeacher($teacher);
                                $contract->setTeachingUnit($unit);
                                $contract->setSubject($subject);
                                $contract->setSemester($coshs->getSemester());
                                
                             //   ($contract->getSubject())?$courseHoursVolume = $contract->getSubjectHours():$courseHoursVolume = $contract->getHoursVolume(); 
                                $contract->setVolumeHrs($hrToAffect);
                                $contract->setCmHrs($cmHrs);
                                $contract->setTdHrs($tdHrs);
                                $contract->setTpHrs($tpHrs);
                                //$contract->setClassOfStudyHasSemester($contract);
                                $contract->setRefNumber($refNum); 
                                if($flag) $this->entityManager->flush();
                                else
                                {
                                    $this->entityManager->persist($contract); 
                                    $this->entityManager->flush();
                                }


                        
      
                }
            
            

            //$this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    true
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }

    public function unAssignSubjectToTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $cities = [];
            $data= $this->params()->fromPost();           
      
  
            //$data = json_decode($data,true);
         
          /*  $teacher = $this->entityManager->getRepository(Teacher::class)->find($data['teacherId']);
            $acadYear = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);*/

            $contract = $this->entityManager->getRepository(Contract::class)->find($data["contractId"]); 
             $contractFollowUp = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);
             foreach($contractFollowUp as $con) $this->entityManager->remove($con);
                
                    $this->entityManager->remove($contract); 
                    $this->entityManager->flush();

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    true
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }    
    public function searchAllSubjectsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();  
            $id = $data["id"];
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId; 
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {  
                $query = $this->entityManager->createQuery('SELECT c.id,c.subjectId,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
                        .' WHERE c.codeUe LIKE :code AND c.acadYrId = :acadYrId');
                $query->setParameter('code', '%'.$id.'%');
                $query->setParameter('acadYrId', $this->crtAcadYr->getId());

                $subjects = $query->getResult();   

            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));  
                if($userClasses)
                {

                    foreach($userClasses as $classe)
                    {
                        $query = $this->entityManager->createQuery('SELECT c.id,c.subjectId,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\Application\Entity\CurrentYearUesAndSubjectsView c'
                                .' WHERE c.classe = :classe AND c.codeUe LIKE :code');
                        $query->setParameter('code', '%'.$id.'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());

                        $subjects_1 = $query->getResult();
                        $subjects= array_merge($subjects , $subjects_1);
                    }
                }                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $subjects
            ]);

            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }        
        
    }
    
    public function teacherUnitFollowUpAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromPost();  
            $id = $data["id"];
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId; 
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {            
                // retrieve subjects based on subject code

                //$rsm = new ResultSetMapping();
                // build rsm here

                $query = $this->entityManager->createQuery('SELECT c.id,c.contract,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs FROM Application\Entity\CurrentYearUesAndSubjectsView c'
                        .' WHERE c.codeUe LIKE :code');
                $query->setParameter('code', '%'.$id.'%');

                $subjects = $query->getResult();
            }
            else
            {
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));  
                if($userClasses)
                {

                    foreach($userClasses as $classe)
                    {
                        $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\Application\Entity\CurrentUesAndSubjectsView c'
                                .' WHERE c.classe = :classe AND c.codeUe LIKE :code');
                        $query->setParameter('code', '%'.$id.'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());

                        $subjects_1 = $query->getResult();
                        $subjects= array_merge($subjects , $subjects_1);
                    }
                }                
            }

            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $subjects
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }
    
    public function searchTeacherAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();  
            $id = $data["id"];
            $subjects=[];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
           
         
          //  if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) {
                
                $query = $this->entityManager->createQuery('SELECT t.id,t.name FROM Application\Entity\Teacher t'
                        .' WHERE t.name LIKE :name');
                $query->setParameter('name', '%'.$id.'%');
                //$query->setParameter('userId', $userId);
                $teachers = $query->getResult();  
                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $teachers
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    } 
    
    public function importTeacherAction()
    {
            $this->entityManager->getConnection()->beginTransaction();
            try
            {     

                /* Getting file name */
               $filename = $_FILES['file']['name'];
               /* Location */
               $location = './public/upload/';

               $csv_mimetypes = array(
                   'text/csv',
                   'application/csv',
                   'text/comma-separated-values',
                   'application/excel',
                   'application/vnd.ms-excel',
                   'application/vnd.msexcel',
                );
            // Check if fill type is allowed  
              if(!in_array($_FILES['file']['type'],$csv_mimetypes))
              {
                 $result = false;

                  $view = new JsonModel([
                    $result
                  ]);
                  return $view; 
              }

                /* Upload file */
                move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);


                $reader = new Csv(); 
                $spreadsheet = $reader->load($location.$filename);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                //set status to 0
                //Student is currently in draft mode
                $status = 0;
                if (!empty($sheetData)) {
                    for ($i=1; $i<count($sheetData); $i++) { //skipping first row
                       
                        $row["title"] = $sheetData[$i][0];
                        $row["name"] = $sheetData[$i][1];
                        $row["surname"] = $sheetData[$i][2];
                        $row["dateOfBirth"] = $sheetData[$i][3];
                        $row["placeOfBirth"] = $sheetData[$i][4];
                        $row["citizenship"] = $sheetData[$i][5];
                        $row["email"] = $sheetData[$i][6];
                        $row["phoneNumber"] = $sheetData[$i][7];
                        $row["faculty"] = $sheetData[$i][8];
                        $row["type"] = $sheetData[$i][9];
                        $row["grade"] = $sheetData[$i][10];
                        $row["highestDegree"] = $sheetData[$i][11];
                        $row["livingCity"] = $sheetData[$i][12];
                        $row["currentEmployer"] = $sheetData[$i][13];
                        
                       
                        $teacher = new Teacher();
                        
                        $teacher->setCivility($row["title"]);
                        $teacher->setName($row["name"]);
                        $teacher->setSurname($row["surname"]);
                        $teacher->setPhoneNumber($row["phoneNumber"]);
                        $teacher->setEmail($row["email"]);                         
                        $teacher->setBirthDate(new DateTime($row["dateOfBirth"]));
                        $teacher->setNationality($row["citizenship"]);
                        $teacher->setType($row["type"]);
                        $teacher->setHighDegree($row["highestDegree"]);
                        
                        $teacher->setLivingCountry($row["citizenship"]);
                        $teacher->setLivingCity($row["livingCity"]);
                        $academicRank = $this->entityManager->getRepository(AcademicRanck::class)->findOneByCode(trim($row["grade"]));
                        $teacher->setAcademicRanck($academicRank);
                        $faculty = $this->entityManager->getRepository(Faculty::class)->find(trim($row["faculty"]));
                        $teacher->setFaculty($faculty);
                        
                        $teacher->setStatus(1);
                        
                        $this->entityManager->persist($teacher);
                        $this->entityManager->flush();
        
                    }
                }



            $this->entityManager->getConnection()->commit();


            $arr = array("name"=>$filename);
            $result = true;

              $view = new JsonModel([
                  $result
             ]);

    // Disable layouts; `MvcEvent` will use this View Model instead
           // $view->setTerminal(true);

            return $view;      
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;

        }        
    }
    
    public function loadTeacherBillAction()
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        
      
            $data = $this->params()->fromRoute();   
               
            $subjects=[];
            $bills = [];

            //This program can generate bill for a signle teacher or many of them
            if($data['isBulkBilling']==0) 
                $teachers = $this->entityManager->getRepository(Teacher::class)->findBy(["id"=>$data["teacherID"]]); 
            else
                $teachers = $this->entityManager->getRepository(Teacher::class)->findAll([],array("name"=>"ASC"));
            
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->find($this->crtAcadYr->getId());
            $teacherInfo = [];
            $i = 0;
            
        $academicYr =  $this->crtAcadYr->getCode(); 
        $school =  $this->entityManager->getRepository(School::class)->findAll()[0]; 
        
        foreach($teachers as $teach)
        {
                
                $actualBilledTime = 0;

                $actualTimeToBill = 0; 
                $actualCmTimeToBill = 0;
                $actualTpTdTimeToBill = 0;                
                $overtime = 0;
                $totalAmount = 0;
                $transport = 0;
                $ir = 0;
                $vac = 0;
                
                $totalTimeBilled = 0; 
                $flagContractCount = 0;
            
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($teach->getId());
            
                        if($teacher->getAcademicRanck());
                            $academicRank = $teacher->getAcademicRanck()->getName();
                        if($teacher->getFaculty()) $faculty = $teacher->getFaculty()->getCode(); 
            //Seacrch contracts in which teacher is involved
            $contracts= $this->entityManager->getRepository(Contract::class)->findBy(array("teacher"=>$teacher,"academicYear"=>$this->crtAcadYr) );
            
            $acadYearId = $this->crtAcadYr->getId(); 
            $contracts = [];

            $query = $this->entityManager->createQuery('SELECT c.id as id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId,c.totalHrs,c.teacher   FROM Application\Entity\AllContractsView c '
                    .'WHERE c.teacher = :teacherID AND c.acadYrId = :acadYearId' );
            $query->setParameter('teacherID',$teach->getId());
            $query->setParameter('acadYearId',$acadYearId);
            //if($query->getResult())
            $contracts = $query->getResult();           
            
            //counting finished or overflow contracat
            $countFinishedContract=0;
            foreach($contracts as $contract)
            {

                $contract = $this->entityManager->getRepository(Contract::class)->find($contract["id"]);  
                
                $contractFollowUp = $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["contract"=>$contract,"teacherPaymentBill"=>NULL]);
                if(sizeof($contractFollowUp)> 0) $flagContractCount++; 
                
                $bills = $this->entityManager->getRepository(TeacherPaymentBill::class)->findBy(["teacher"=>$teacher,"contract"=>$contract]);
                $alreadyBilledTime = 0;
                $alreadyBilledCM = 0;
                $alreadyBilledTP = 0;
                //calculate the already billed time
                foreach($bills as $bill)
                {
                    $alreadyBilledTime += $bill->getTotalTimeCurrentlyBilled();
                    if($bill->getLectureType() == "CM")
                        $alreadyBilledCM += $bill->getTotalTimeCurrentlyBilled();
                    if($bill->getLectureType() == "TPTD")
                        $alreadyBilledTP += $bill->getTotalTimeCurrentlyBilled();                    
                    
                }
                
                //count the number of contract that are already completely billed for a teacher
                if($contract->getVolumeHrs()<= $alreadyBilledTime) $countFinishedContract++;                
               
            }
           
            // check if the number of contract is less or equal to the number of actual contract
            //if the contract is closed, go to the next
            if(sizeof($contracts)<=$countFinishedContract) continue;
            
            // Create a new bill
            $billSumary = new TeacherPaymentBillSumary();
            $billSumary->setPaymentAmount($totalAmount); 
            $billSumary->setDate(new \DateTime( date('Y-m-d'))); 
            $billSumary->setAcademicYear($acadYr);
            $billSumary->setTeacher($teacher);
            
            $this->entityManager->persist($billSumary); 
   
            //Asuming we did not find any item to bill
             $flag = 0;
             $cptOverTime=0;
             
             
             
         
            foreach($contracts as $contract)
            {
                $totalTimeScheduled= 0;
                $classe = $contract["classe"];
                
                $contract = $this->entityManager->getRepository(Contract::class)->find($contract["id"]);
                //looking for the payment rate associated with the subject
                ////////////////////////////////////////////////
                if($contract->getSubject() != NULL)
                    $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneBySubject($contract->getSubject());
                else $coshs = $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findOneByTeachingUnit($contract->getTeachingUnit());
                
                //if no rate is explicitely defined, the default one is used
                //payment rate can be based on teacher academic rank or just be a fixed predifined amount
                $isDefaultPymtGrid = 0;
                if($coshs->getPaymentTeachingAssignment() != NULL)
                { 
                    //check the payment method
                    $paymentMethod = $coshs->getPaymentTeachingAssignment()->getPaymentMethod();
                    //collecte  the payment rate
                    $pymtRate = $coshs->getPaymentTeachingAssignment()->getTeacherPaymentRate(); 
                    
                    if($paymentMethod == "FROFAIT")
                    {
                        $pymtPerHrAmount = $coshs->getTeacherPaymentAssignment()->getAmount(); 
                        $pymtPerHrTheoriticalAmount = $coshs->getTeacherPaymentAssignment()->getAmountTheoritical();
                        $pymtPerHrPracticalAmount = $coshs->getTeacherPaymentAssignment()->getAmountPractical();
                    }
                } 
                else
                { 
                    $paymentMethod = "ACADEMIC_RANK";
                    $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->findOneByIsDefaultPayment(1); 
                    $isDefaultPymtGrid = 1;
                }
           

                if(($paymentMethod == "ACADEMIC_RANK") && !$isDefaultPymtGrid)
                {
                    $pymtRate = $this->entityManager->getRepository(AcademicRanckPaymentRates::class)->findOneBy(["teacherPaymentRate"=>$pymtRate]); 
                    if($pymtRate)
                    {
                        $pymtPerHrAmount = $pymtRate->getAmount();
                        $pymtPerHrTheoriticalAmount = $coshs->getTeacherPaymentAssignment()->getAmountTheoritical();
                        $pymtPerHrPracticalAmount = $coshs->getTeacherPaymentAssignment()->getAmountPractical(); 
                       
                    }
                    
                }
                else if(($paymentMethod == "ACADEMIC_RANK") && $isDefaultPymtGrid)
                {
                    $pymtRate = $this->entityManager->getRepository(AcademicRanckPaymentRates::class)->findOneBy(["teacherPaymentRate"=>$pymtRate]); 
                    if($pymtRate)
                    {                    
                        $pymtPerHrAmount = $pymtRate->getAmount(); 
                        $pymtPerHrTheoriticalAmount = $pymtRate->getAmountTheoritical();
                        $pymtPerHrPracticalAmount = $pymtRate->getAmountPractical();
                    }
                }

                  


                ///////////////////////////////////////////////////
                
                $amount = 0;
                
                $totalTimeScheduled += $contract->getVolumeHrs(); 

                
                //lookind only to items not already paid
                $contractNotYetPaid= $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["contract"=>$contract,"teacherPaymentBill"=>NULL] );
                if (sizeof($contractNotYetPaid)>0) $flag = 1;
                if(sizeof($contractNotYetPaid)<=0) continue;
               
                //Check if other bills exist on this contract
                $bills = $this->entityManager->getRepository(TeacherPaymentBill::class)->findBy(["teacher"=>$teacher,"contract"=>$contract]);


                //count number of bill already paid 
                $cptBillAlreadyPaid = sizeof($bills);
                 
                //if other bill exist, calculate the over all time already billed
                $alreadyBilledTime = 0;
                $alreadyBilledCMTime = 0;
                $alreadyBilledTPTime = 0;
                $alreadyBilledTDTime = 0;
                $alreadyBilledTDTPTime = 0;
                foreach($bills as $bill) $alreadyBilledTime += $bill->getTotalTimeCurrentlyBilled();  
                foreach($bills as $bill) if($bill->getLectureType()=="CM") $alreadyBilledCMTime += $bill->getTotalTimeCurrentlyBilled();
                foreach($bills as $bill) if($bill->getLectureType()=="TD") $alreadyBilledTDTime += $bill->getTotalTimeCurrentlyBilled();
                foreach($bills as $bill) if($bill->getLectureType()=="TP") $alreadyBilledTPTime += $bill->getTotalTimeCurrentlyBilled();
                
                //Dont allowed the payment of items that exceed the time limit
                if($contract->getVolumeHrs()< $alreadyBilledTime) continue;
                if($contract->getVolumeHrs()< $alreadyBilledCMTime + $alreadyBilledTPTime + $alreadyBilledTDTime ) continue; 
                if($contract->getCmHrs()< $alreadyBilledCMTime && $contract->getTpHrs()  < $alreadyBilledTPTime && $contract->getTdHrs()  < $alreadyBilledTDTime) continue;
               
               
                

                $paymentDetails = [];
                $billedTime = 0;
                


                
                $pymtCMBill = new TeacherPaymentBill(); 
                $pymtCMBill ->setLectureType("CM");
                $pymtTDBill = new TeacherPaymentBill();
                $pymtTDBill ->setLectureType("TD");
                $pymtTPBill = new TeacherPaymentBill();
                $pymtTPBill ->setLectureType("TP");                
                
                $this->entityManager->persist($pymtCMBill); 
                $this->entityManager->persist($pymtTDBill);
                $this->entityManager->persist($pymtTPBill);
              
                $actualTimeToBill = 0; 
                $actualCmTimeToBill = 0;
                $actualTpTimeToBill = 0;
                $actualTdTimeToBill = 0;
                $overtime = 0;
                
                foreach($contractNotYetPaid as $con)
                {
                      
                        
                    $actualTimeToBill += $con->getTotalTime();
                    if($con->getLectureType()== "CM")
                    {
                        $actualCmTimeToBill += $con->getTotalTime();
                        $con->setTeacherPaymentBill($pymtCMBill); 
                    }
                    else if($con->getLectureType()== "TD")
                    {
                        $actualTdTimeToBill += $con->getTotalTime();
                        $con->setTeacherPaymentBill($pymtTDBill);
                    }
                    elseif($con->getLectureType()== "TP")
                    {
                        $actualTpTimeToBill += $con->getTotalTime();
                        $con->setTeacherPaymentBill($pymtTPBill);
                    }
  
                    
                  /*  if($contract->getVolumeHrs()< $alreadyBilledTime+$actualTimeToBill)
                    { 
                        $overtime  = ($alreadyBilledTime+$actualTimeToBill)-$contract->getVolumeHrs(); 
                        $actualTimeToBill = $actualTimeToBill-$overtime;
                    }*/

                }
           
                 $amount = $actualTimeToBill*$pymtPerHrAmount;
                
                 

       
                //Adding CM to bill only when the actual time to bill is more than 0 
                 if($actualCmTimeToBill>0)
                 {
                     if($contract->getCmHrs()<($alreadyBilledCMTime+$actualCmTimeToBill)) $overtime = $alreadyBilledCMTime+$actualCmTimeToBill - $contract->getCmHrs();
                     $actualCmTimeToBill-=$overtime;

                    $amountCM = $actualCmTimeToBill*$pymtPerHrTheoriticalAmount;
                    $totalAmount+=$amountCM;
                    $pymtCMBill->setOverTime($overtime); 
                    if($contract->getSubject()) $pymtDetails = $contract->getSubject()->getSubjectName()." "."<b>".$classe."</b>"; 
                    if($contract->getTeachingUnit()) $pymtDetails = $contract->getTeachingUnit()->getName()." "."<b>".$classe."</b>"; 
                    $pymtCMBill->setPaymentDetails("<b>CM:</b> ".$pymtDetails); 
                    $pymtCMBill->setDate(new \DateTime( date('Y-m-d'))); 
                    $pymtCMBill->setPaymentRate($pymtPerHrTheoriticalAmount);
                    $pymtCMBill->setPaymentAmount($amountCM);

                    $pymtCMBill->setTotalTime($contract->getCmHrs()); 
                    ($alreadyBilledCMTime === 0)? $pymtCMBill->setTotalTimePreviouslyBilled(0): $pymtCMBill->setTotalTimePreviouslyBilled($alreadyBilledCMTime) ; 
                    $pymtCMBill->setTotalTimeCurrentlyBilled($actualCmTimeToBill);
                    $pymtCMBill->setTeacher($teacher);
                    $pymtCMBill->setContract($contract); 
                    $pymtCMBill->setTeacherPaymentBillSumary($billSumary);
                 }
                
                
                //Adding TP to bill only when the actual time to bill is more than 0
                 if($actualTpTimeToBill>0) 
                 {
                     if(($contract->getTpHrs())<($alreadyBilledTPTime+$actualTpTimeToBill)) $overtime = $alreadyBilledTPTime+$actualTpTimeToBill - $contract->getTpHrs();
                     $actualTpTimeToBill-=$overtime;
                     $amountTP = $actualTpTimeToBill*$pymtPerHrPracticalAmount;
                     $totalAmount+=$amountTP;
                    $pymtTPBill->setOverTime($overtime); 
                    if($contract->getSubject()) $pymtDetails = $contract->getSubject()->getSubjectName()." "."<b>".$classe."</b>"; 
                    if($contract->getTeachingUnit()) $pymtDetails = $contract->getTeachingUnit()->getName()." "."<b>".$classe."</b>"; 
                    $pymtTPBill->setPaymentDetails("<b>TP:</b> ".$pymtDetails); 
                    $pymtTPBill->setDate(new \DateTime( date('Y-m-d'))); 
                    $pymtTPBill->setPaymentRate($pymtPerHrPracticalAmount);
                    $pymtTPBill->setPaymentAmount($amountTP); 
                    $pymtTPBill->setTotalTime($contract->getTpHrs()); 
                    ($alreadyBilledTPTime === 0)? $pymtTPBill->setTotalTimePreviouslyBilled(0): $pymtTPBill->setTotalTimePreviouslyBilled($alreadyBilledTPTime) ; 
                    $pymtTPBill->setTotalTimeCurrentlyBilled($actualTpTimeToBill);
                    $pymtTPBill->setTeacher($teacher);
                    $pymtTPBill->setContract($contract); 
                    $pymtTPBill->setTeacherPaymentBillSumary($billSumary); 
                 }
                //Adding TD to bill only when the actual time to bill is more than 0
                 if($actualTdTimeToBill>0) 
                 {
                     if(($contract->getTdHrs()+$contract->getTdHrs())<($alreadyBilledTDTime+$actualTdTimeToBill)) $overtime = $alreadyBilledTPTime+$actualTdTimeToBill - $contract->getTdHrs();
                     $actualTdTimeToBill-=$overtime;
                     $amountTD= $actualTdTimeToBill*$pymtPerHrPracticalAmount;
                     $totalAmount+=$amountTD;
                    $pymtTPBill->setOverTime($overtime); 
                    if($contract->getSubject()) $pymtDetails = $contract->getSubject()->getSubjectName()." "."<b>".$classe."</b>"; 
                    if($contract->getTeachingUnit()) $pymtDetails = $contract->getTeachingUnit()->getName()." "."<b>".$classe."</b>"; 
                    $pymtTDBill->setPaymentDetails("<b>TD:</b> ".$pymtDetails); 
                    $pymtTDBill->setDate(new \DateTime( date('Y-m-d'))); 
                    $pymtTDBill->setPaymentRate($pymtPerHrPracticalAmount);
                    $pymtTDBill->setPaymentAmount($amountTD); 
                    $pymtTDBill->setTotalTime($contract->getTdHrs()); 
                    ($alreadyBilledTDTime === 0)? $pymtTDBill->setTotalTimePreviouslyBilled(0): $pymtTDBill->setTotalTimePreviouslyBilled($alreadyBilledTDTime) ; 
                    $pymtTDBill->setTotalTimeCurrentlyBilled($actualTdTimeToBill);
                    $pymtTDBill->setTeacher($teacher);
                    $pymtTDBill->setContract($contract); 
                    $pymtTDBill->setTeacherPaymentBillSumary($billSumary); 
                 }                 
                
                $totalTimeBilled+= $actualTimeToBill;
               

            }
            

            if($actualCmTimeToBill >0 || $actualTdTimeToBill>0 ||$actualTpTimeToBill>0)
            {
                //Adding transport to the bill
                $transport = new TeacherPaymentBill(); 
                $transport ->setLectureType("XPORT");
                $transport->setOverTime(0); 
                 $pymtDetails = "Forfait transport"; 
                
                $transport->setPaymentDetails($pymtDetails); 
                $transport->setDate(new \DateTime( date('Y-m-d'))); 
                $transport->setPaymentRate($data["xportAmount"]);
                $transport->setPaymentAmount($data["xportnbStay"]*$data["xportAmount"]); 
                $transport->setTotalTime(0); 
               
                $transport->setTotalTimeCurrentlyBilled($data["xportnbStay"]);
                $transport->setTeacher($teacher);
                $transport->setContract($contract); 
                $transport->setTeacherPaymentBillSumary($billSumary);  
                $this->entityManager->persist($transport);

                //calculate transp ort amour 
                $transport = $data["xportnbStay"]*$data["xportAmount"];                
            
                
            
                

            
                if(!$data["isXportSupportingDocsAvailable"])
                {
                    $totalAmountHt = $totalAmount+ $transport;
                    //impot sur le revenu
                    $ir = $totalAmountHt*0.05;

                    //retenu sur vacation
                    $vac = $totalAmountHt*0.25;                
                    //actual mamount paid
                    $totalAmountPaid = $totalAmountHt - $ir -$vac;                
                }
                else 
                {
                    $totalAmountHt = $totalAmount;
                    //impot sur le revenu
                    $ir = $totalAmountHt*0.05;

                    //retenu sur vacation
                    $vac = $totalAmountHt*0.25;                 
                    //actual mamount paid
                    $totalAmountPaid = $totalAmountHt - $ir -$vac + $transport;                
                }                

                $billSumary->setPaymentAmountHt($totalAmountHt);
                $billSumary->setPaymentAmount($totalAmountPaid); 
                $billSumary->setTotalTimePaid($totalTimeBilled);
                $billSumary->setTotalTimeScheduled($totalTimeScheduled);
            }
            else $flag = 0;
                      
 
            
            if($flag === 0){$this->entityManager->remove($billSumary); continue;
                $output = new JsonModel([
                    ["error"=>1,"msge"=>"Absence d'heure à facturer"] //Absence d'heure de cours à facturer
                ]);
                return $output;                        
            } 
            if(sizeof($contracts) === $cptOverTime){ $this->entityManager->remove($billSumary);  continue;
                $output = new JsonModel([
                    ["error"=>2,"msge"=>"Volume horaire dépassé"] //Volume horaire dépassé
                ]);
                return $output;                        
            } 
           
            $this->entityManager->flush();
           
            $billDetails = $this->entityManager->getRepository(TeacherPaymentBill::class)->findByTeacherPaymentBillSumary($billSumary);
            $hydrator = new ReflectionHydrator();
            $billSumary = $hydrator->extract($billSumary);
           
            $billSumaryItems = [];
             
            foreach ($billDetails as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $billSumaryItems[$key] = $hydrator->extract($value);                
            }
            
            
            
            //$teacher['faculty'] = $teacher->getFaculty()->getCode();

            
            $hydrator = new ReflectionHydrator();
            $teacher = $hydrator->extract($teacher);
            $teacher["paymentRate"] = $pymtPerHrAmount;
            $i++;
            
            $teacherInfo[$i]['info']=$teacher;
            $teacherInfo[$i]['info']['academicRank']=$academicRank;
            $teacherInfo[$i]['info']['faculty']=$faculty;
            $teacherInfo[$i]['info']['school']=$school->getName();
            $teacherInfo[$i]["bill_items"] = $billSumaryItems;
            $teacherInfo[$i]['bill_sumary'] = $billSumary;
            
            
            $teacherInfo[$i]['acadYr'] = $academicYr;
            $teacher[$i]['logo'] = $school->getLogo();
            $teacher[$i]['school'] = $school->getName();
             
           
        }
         
      
        if($flagContractCount==0){
            $output = new JsonModel([
                ["error"=>3,"msge"=>"Absence d'huere de cours à facturer"] //Absence d'heure de cours à facturer
            ]);
            return $output;                        
        } 



             




             
       
         
        $this->entityManager->getConnection()->commit(); 
        
        
            $output = new ViewModel([
               "teachers"=>$teacherInfo,
                "isXportSupportingDocsAvalaible"=>$data["isXportSupportingDocsAvailable"],
                "ir"=>$ir,
                "vacation"=>$vac
               

            ]);
             $output->setTerminal(true);
            return $output; 
    } 
    
    public function setVacationPaymentMethodAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $data = $this->params()->fromQuery();             
            $subjects = []; 
            if(isset($data["selectedtraining"])) 
                $trainings  = $this->entityManager->getRepository(Degree::class)->findById($data["selectedtraining"]); 
            if(isset($data['trainingType']))
            {
                $i = 0;
                $cycle = $this->entityManager->getRepository(CourseCategory::class)->find($data["trainingType"]); 
                $dhccs = $this->entityManager->getRepository(DegreeHasCourseCategory::class)->findByCourseCategory($cycle);
                
                foreach($dhccs as $dhcc) $trainings[$i] = $dhcc->getDegree();
            }
                
                foreach($trainings as $training)               
                    $classes = $this->entityManager->getRepository(ClassOfStudy::class)->findByDegree($training);
                  
                    foreach($classes as $classe)
                    {
                        $semesters = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($this->crtAcadYr);
                        foreach($semesters as $sem)
                        {
                        
                            $coshs= $this->entityManager->getRepository(ClassOfStudyHasSemester::class)->findBy(["classOfStudy"=>$classe,"semester"=>$sem]); 
                            foreach($coshs as $subject)
                            {
                                //very if a payment method is assigned to the subject
                                if(!$subject->getPaymentTeachingAssignment()) 
                                {   //crate a new payment method
                                    $pymtMethod = new PaymentTeachingAssignmentMethod(); 
                                    //verifuy if the payment metgod is based on academic ranking
                                    if($data["paymentMethod"]==0)
                                    {
                                        $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($data["paymentGrid"]);
                                        $pymtMethod->setPaymentMethod("ACADEMIC_RANK");
                                        $pymtMethod->setTeacherPaymentRate($pymtRate);
                                        $pymtMethod->setAmount(NULL);
                                        $pymtMethod->setAmountTheoritical(NULL);
                                        $pymtMethod->setAmountPractical(NULL);
                                    }
                                    elseif($data["paymentMethod"]==1)
                                    {
                                        $pymtMethod->setPaymentMethod("FORFAIT");
                                        //$pymtMethod->setAmount($data["amount"]);
                                        $pymtMethod->setAmountTheoritical($data["amountTheoritical"]);
                                        $pymtMethod->setAmountPractical($data["amountPractical"]);                                        
                                    }
                                    $this->entityManager->persist($pymtMethod);
                                    $subject->setPaymentTeachingAssignment($pymtMethod);
  
                                }else //update an already existing payment method
                                {
                                    $pymtMethod = $subject->getPaymentTeachingAssignment(); 
                                    if($data["paymentMethod"]==0)
                                    {;
                                        $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($data["paymentGrid"]); 
                                        $pymtMethod->setPaymentMethod("ACADEMIC_RANK"); 
                                        $pymtMethod->setTeacherPaymentRate($pymtRate);
                                        $pymtMethod->setAmount(NULL);
                                        $pymtMethod->setAmountTheoritical(NULL);
                                        $pymtMethod->setAmountPractical(NULL);                                        
                                    }
                                    elseif($data["paymentMethod"]==1)
                                    {
                                        $pymtMethod->setPaymentMethod("FORFAIT");
                                        $pymtMethod->setAmountTheoritical($data["amountTheoritical"]);
                                        $pymtMethod->setAmountPractical($data["amountPractical"]);
                                    }                                    
                                    
                                }
                            }
                            
                            $this->entityManager->flush();
                        }
                    }
          
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
    
    
    public function searchBillAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();   
           // var_dump($data);
           
            $subjects=[];
            $bills = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1); 
            
            //$contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );
           
            $bills = $this->entityManager->getRepository(TeacherPaymentBillSumary::class)->findBy(["teacher"=>$teacher,"academicYear"=>$acadYr]);
            $hydrator = new ReflectionHydrator();
            foreach($bills as $key=>$value)
            $bills[$key]= $hydrator->extract($value);            
         
            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                    $bills
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }   
    
    public function cancelTeacherBillAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->getRequest()->getContent();   
            $data = json_decode($data,true);


           
            $userId = $this->sessionContainer->userId;
            $bill = $this->entityManager->getRepository(TeacherPaymentBillSumary::class)->find($data["id"]); 
            
            $billItems = $this->entityManager->getRepository(TeacherPaymentBill::class)->findByTeacherPaymentBillSumary($bill); 
            
           
            foreach($billItems as $key=>$value)
            {
                $contratProgressions = $this->entityManager->getRepository(ContractFollowUp::class)->findByTeacherPaymentBill($value); 
                foreach($contratProgressions as $progression)
                    $progression->setTeacherPaymentBill(NULL);
                
                
                $this->entityManager->remove($value);
 
            } 
            $this->entityManager->remove($bill);
            $this->entityManager->flush();
            
            $bills = $this->entityManager->getRepository(TeacherPaymentBillSumary::class)->findAll();  
            
            $this->entityManager->getConnection()->commit();
            
            $hydrator = new ReflectionHydrator();
            foreach($bills as $key=>$value)
            $bills[$key]= $hydrator->extract($value);               
            $output = new JsonModel([
                    $bills
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }    
    
    public function billDetailsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();                               
           
            $subjects=[];
            $billItems = [];
            $teacher =[];
            $subject = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
           /* $contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );*/
        
            $bill = $this->entityManager->getRepository(TeacherPaymentBill::class)->findOneBy(["refNumber"=>$data["numRef"]]);
            $teacher["id"]= $bill->getTeacher()->getId();
            $teacher["name"]= $bill->getTeacher()->getName();
            $contract = $bill->getContract();
            
            
            
            $pymtRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            if($contract->getTeachingUnit())
            {
                $subject["id"] =  $contract->getTeachingUnit()->getId();
                $subject["codeUe"] =  $contract->getTeachingUnit()->getCode();
                $subject["nomUe"] =  $contract->getTeachingUnit()->getName();
            }
            else if($contract->getSubject())
            {
                $subject["id"] =  $contract->getSubject()->getId();
                $subject["codeUe"] =  $contract->getSubject()->getCode();
                $subject["nomUe"] =  $contract->getgetSubject()->getName();              
            }

            $billItems = $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["teacherPaymentBill"=>$bill]);
           
            $hydrator = new ReflectionHydrator();
           // $teacher = $bill->getTeacher(); 
           // $teacher= $this->entityManager->getRepository(Teacher::class)->findBy($teacher->getId());
           // $data = $hydrator->extract($teacher);  
            //print_r($data); exit;
            $totalHrsDone = 0;
            foreach($billItems as $key=>$value)
            {
                $billItems[$key]= $hydrator->extract($value);
                $billItems[$key]["paymentRate"] = $pymtRate;
                $billItems[$key]["paymentAmount"] = $value->getTotalTime() * $pymtRate;  
                $totalHrsDone +=$value->getTotalTime();
            }
            $paymentRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            $overTime = $bill->getOverTime();
            $totalHrs["totalHrsPreviouslyBilled"] = $bill->getTotalTimePreviouslyBilled();
            $totalHrs["totalHrsCurrentlyBilled"] = $bill->getTotalTimeCurrentlyBilled();
            $totalHrs["vacationDeduction"] = $bill->getVacationDeduction();
            $totalHrs["totalHrsDone"] = $totalHrsDone;
            $bill = $hydrator->extract($bill); 
            $totalHrs["totalHrsAffected"] = $contract->getVolumeHrs(); 
            
            
            $totalHrs["overTime"] =  $overTime;
            
            
            $this->entityManager->getConnection()->commit();

            $output = new JsonModel([
               $billItems,
                $teacher,
                $subject,
                $bill,
                $paymentRate,
                $totalHrs
                
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }
    
    public function printBillAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromRoute();                                       
       
            $subjects=[];
            $billItems = [];
            $teacher =[];
            $subject = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
           /* $contract= $this->entityManager->getRepository(Contract::class)->find($data["contractID"] );
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );*/
        
            $bill = $this->entityManager->getRepository(TeacherPaymentBill::class)->findOneBy(["refNumber"=>$data["numRef"]]);
            $teacher["id"]= $bill->getTeacher()->getId();
            $teacher["name"]= $bill->getTeacher()->getName();
            $contract = $bill->getContract();
            
           
            
            $pymtRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            if($contract->getTeachingUnit())
            {
                $subject["id"] =  $contract->getTeachingUnit()->getId();
                $subject["codeUe"] =  $contract->getTeachingUnit()->getCode();
                $subject["nomUe"] =  $contract->getTeachingUnit()->getName();
            }
            else if($contract->getSubject())
            {
                $subject["id"] =  $contract->getSubject()->getId();
                $subject["codeUe"] =  $contract->getSubject()->getCode();
                $subject["nomUe"] =  $contract->getgetSubject()->getName();              
            }

            $billItems = $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["teacherPaymentBill"=>$bill]);
          
            $hydrator = new ReflectionHydrator();
           // $teacher = $bill->getTeacher(); 
           // $teacher= $this->entityManager->getRepository(Teacher::class)->findBy($teacher->getId());
           // $data = $hydrator->extract($teacher);  
            //print_r($data); exit;
            $totalHrsDone = 0;
            foreach($billItems as $key=>$value)
            {
                $billItems[$key]= $hydrator->extract($value);
                $billItems[$key]["paymentRate"] = $pymtRate;
                $billItems[$key]["paymentAmount"] = $value->getTotalTime() * $pymtRate;  
                $totalHrsDone +=$value->getTotalTime();
            }
            $paymentRate = $bill->getTeacher()->getAcademicRanck()->getPaymentRate();
            $overTime = $bill->getOverTime();
            $totalHrs["totalHrsPreviouslyBilled"] = $bill->getTotalTimePreviouslyBilled();
            $totalHrs["totalHrsCurrentlyBilled"] = $bill->getTotalTimeCurrentlyBilled();
            $totalHrs["vacationDeduction"] = $bill->getVacationDeduction();
            $totalHrs["totalHrsDone"] = $totalHrsDone;
            $bill = $hydrator->extract($bill); 
            $totalHrs["totalHrsAffected"] = $contract->getVolumeHrs(); 
            
            
            $totalHrs["overTime"] =  $overTime;
            
            
            $this->entityManager->getConnection()->commit();

            $output = new ViewModel([
               "billItems"=>$billItems,
                "teacher"=>$teacher,
                "subject"=>$subject,
                "bill"=>$bill,
                "paymentRate"=>$paymentRate,
                "totalHrs"=>$totalHrs
            ]);
             $output->setTerminal(true);
            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }    
    
    public function schedulingCourseAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();         
            $subject = null;
            $teacher =null;
            $resource = null;
            
            //$classroom= json_decode($data,true);
            
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
           // $teacher = $this->entityManager->getRepository(Teacher::class)->find($data["teacher"]);
            
            $classroom = $this->entityManager->getRepository(Resource::class)->find($data["classroom"]);
            
            $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue"]);
            if(isset($data["subject"]))
                $subject = $this->entityManager->getRepository(Subject::class)->find($data["subject"]);
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["sem"]);

            $contract = $this->entityManager->getRepository(Contract::class)->findOneBy(["teachingUnit"=>$teachingUnit,"subject"=>$subject,"semester"=>$semester]); 
            
            //----------
            //Check only subject that are assigned to a lecturer can be scheduled
            //-------------
            if($contract)
                $teacher = $contract->getTeacher();
             //insuring that only courses that are allocated can be scheduled
             else          return new JsonModel([    "contractNotFound"=>true ]);//contract not found  
             
             
             //CHecking voume houor done
             $contract_fup = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract); 
             
            //conveting geining and ending date to date
             
            $dateBegining  = new \DateTime( $data["dateBegining"]); 
            $dateEdnding  = new \DateTime( $data["dateEnding"]); 
           
            while($dateBegining <= $dateEdnding) 
            { 
                $times = json_decode($data["timeFrames"],true);               
                for($i=0;$i<sizeof($times);$i++)
                {
                    if(isset($times[$i]["status"]))
                            if($times[$i]["status"]==1)
                            {
                                
                                $dateScheduled = $dateBegining;
                                $startingTime = $dateBegining;
                                
                                $timeExtrated = explode("-",$times[$i]["time"]); 
                                
                                $list1 = explode(":",$timeExtrated[0]);                                
                               
                               
                                $dateScheduled->setTime((int)$list1[0],(int)$list1[1],(int)$list1[2]);  
                                
                                $startingTime->setTime((int)$list1[0],(int)$list1[1],(int)$list1[2]);
                                                        
                                $list = explode(":",$timeExtrated[1]);
                                
                                $date = $dateBegining->format("Y-m-d");
                                        $date = explode("-",$date);
                                $endingTime = new \DateTime();
                                $endingTime->setDate($date[0],$date[1],$date[2]);
                                $endingTime->setTime((int)$list[0],(int)$list[1],(int)$list[2]);
                                                                            
               
                                //----------
                                //Check Scheduling conflict in a classrom
                                //------------
                                if($this->checkTimeConflictByClass($data["classe"], $startingTime))
                                  return new JsonModel([ "timeConflict"=>true,"msge"=>$startingTime->format("d/m/Y H:i:s") ]); 

                                //----------
                                //Check Scheduling conflict in a classrom
                                //------------
                                if($this->checkClassromConflict($startingTime,$data["classroom"]))
                                  return new JsonModel([ "classroomConflict"=>true ]);             

                                //Planing in weekend only when user have chossen to do so
                                if(in_array($dateScheduled->format('w'),[0,6]) && !$data["planingForWeekend"]) continue;
                                
                                //Not allow course programing on sunday
                                if($dateScheduled->format('w')==0) continue;
                                

                                $courseScheduled = new CourseScheduled();

                                $courseScheduled->setClassOfStudy($classOfStudy);
                                $courseScheduled->setTeacher($teacher);
                                $courseScheduled->setTeachingUnit($teachingUnit);
                                $courseScheduled->setSubject($subject);
                                $courseScheduled->setSemester($semester);
                                $courseScheduled->setResource($classroom);


                                $courseScheduled->setDateScheduled($dateScheduled);
                                $courseScheduled->setStartingTime($startingTime);
                                $courseScheduled->setEndingTime($endingTime);
                                $courseScheduled->setScheduleType($data["scheduleType"]);

                                

                                $this->entityManager->persist($courseScheduled); 
                                $this->entityManager->flush();
                                
                             
                                
                            }
                }
                
                
                $dateBegining = $dateBegining->modify('+1 day');
            }
 
 
              

            
           
           $hydrator = new ReflectionHydrator();

         //   $data = $hydrator->extract($courseScheduled);
           // $data["eventName"] = $classOfStudy->getCode()." \n".$teachingUnit->getCode();
                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
               // $data
                    
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }         
        
    }  
    
    public function updateScheduledCourseAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromQuery();                 
            $subject = null;
            $teacher =null;
            $resource = null;
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
           // $teacher = $this->entityManager->getRepository(Teacher::class)->find($data["teacher"]);
            
            $teachingUnit = $this->entityManager->getRepository(TeachingUnit::class)->find($data["ue"]);
            if(isset($data["subject"]))
                $subject = $this->entityManager->getRepository(Subject::class)->find($data["subject"]);
            $semester = $this->entityManager->getRepository(Semester::class)->find($data["sem"]);
    
            $contract = $this->entityManager->getRepository(Contract::class)->findOneBy(["teachingUnit"=>$teachingUnit,"subject"=>$subject,"semester"=>$semester]); 
             if($contract)
                $teacher = $contract->getTeacher();
             //insuring that only courses that are allocated can be scheduled
             else          return new JsonModel([    "contractNotFound"=>true ]);//contract not found      
                 
             
              $dateScheduled  = new \DateTime( $data["date"]." ".$data["startingTime"]);
                        $startingTime =new \DateTime( $data["date"]." ".$data["startingTime"]);
            $endingTime = new \DateTime($data["date"]." ".$data["endingTime"]); 

            if($this->checkTimeConflictByClass($data["classe"], $startingTime))
              return new JsonModel([ "timeConflict"=>true ]); 
            
            
              
     
            $courseScheduled  = $this->entityManager->getRepository(CourseScheduled::class)->find($data["idScheduledCourse"]);;
            
            $courseScheduled->setClassOfStudy($classOfStudy);
            $courseScheduled->setTeacher($teacher);
            $courseScheduled->setTeachingUnit($teachingUnit);
            $courseScheduled->setSubject($subject);
            $courseScheduled->setSemester($semester);
            
            
            $courseScheduled->setDateScheduled($dateScheduled);
            $courseScheduled->setStartingTime($startingTime);
            $courseScheduled->setEndingTime($endingTime);
            $courseScheduled->setScheduleType($data["scheduleType"]);
            
            $courseScheduled->setResource($resource);
    
            //$this->entityManager->persist($courseScheduled);
            $this->entityManager->flush();
           
           $hydrator = new ReflectionHydrator();

            $data = $hydrator->extract($courseScheduled);
            $data["eventName"] = $classOfStudy->getCode()." \n".$teachingUnit->getCode();
                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                $data
                    
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
           print($e->getMessage());
            throw $e;
            
        }         
        
    }     
    
    
public function getSchedulingCoursesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $key = 0;
            $myCourse = [];
            $data= $this->params()->fromQuery(); 
           
            
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1); 
            $semester = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr); 
            $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
            foreach ($semester as $sem)
            {
                $courseScheduled = $this->entityManager->getRepository(CourseScheduled::class)->findBy(["classOfStudy"=>$classOfStudy,"semester"=>$sem]);
                

                
                foreach($courseScheduled as $course)
                {
                    $hydrator = new ReflectionHydrator();
                    $teachingUnit = $course->getTeachingUnit();
                    $scheduleType = $course->getScheduleType();
                    
                    $resource =  $course->getResource();
                    if(isset($resource))
                        $resource =$course->getResource()->getCode();
                    else $resource = "ND";
                    
                    if($course->getTeacher())$teacher = $course->getTeacher()->getCivility()." ".$course->getTeacher()->getName()." ".$course->getTeacher()->getSurname(); else $teacher = ""; 
                    $course = $hydrator->extract($course);
                   // $course["eventName"] = $classOfStudy->getCode()." ".$teachingUnit->getCode()." \n".$teacher;
                
                   $course["eventName"] = "(".$scheduleType.") ".$teachingUnit->getCode()."\n ".$teachingUnit->getName()."\n ".$teacher."\n ".$resource;
                    //$course["eventName"] .= "\n".$teacher;
                    
                    $myCourse[$key] = $course;
                    $key++;
                }
                
            }

                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                $myCourse
                    
            ]);

            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
           print( $e->getMessage());
            throw $e;
            
        }         
        
    }
public function getScheduledCourseAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $key = 0;
            $myCourse = [];
            $data= $this->params()->fromQuery(); 
            
           // $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1); 
           // $semester = $this->entityManager->getRepository(Semester::class)->findByAcademicYear($acadYr); 
           // $classOfStudy = $this->entityManager->getRepository(ClassOfStudy::class)->find($data["classe"]);
           // foreach ($semester as $sem)
           // {
                $classOfStudy = [];
                $semester = [];
                $teachingUnit = [];
                $subject = [];
                $course= $this->entityManager->getRepository(CourseScheduled::class)->find($data["id"]);  
                $classOfStudy["id"] = $course->getClassOfStudy()->getId(); 
                $classOfStudy["code"] = $course->getClassOfStudy()->getCode();
                $classOfStudy["name"] = $course->getClassOfStudy()->getName();
                $scheduleType = $course->getScheduleType();
                $isScheduleValidated = $course->getIsValidated(); 
                
                $semester["id"] = $course->getSemester()->getId();
                $semester["code"] = $course->getSemester()->getCode();
                $semester["name"] = $course->getSemester()->getName();
                
                $teachingUnit["id"] = $course->getTeachingUnit()->getId();
                $teachingUnit["code"] = $course->getTeachingUnit()->getCode();
                $teachingUnit["name"] = $course->getTeachingUnit()->getName();  
                
                $contract = $this->entityManager->getRepository(Contract::class)->findOneBy(["teachingUnit"=>$course->getTeachingUnit(),
                    "subject"=>$course->getSubject(),
                    "semester"=>$course->getSemester(),
                    "teacher"=>$course->getTeacher()]); 
                
                if($course->getSubject())
                { 
                    $subject["id"] = $course->getSubject()->getId(); 
                    $subject["subjectCode"] = $course->getSubject()->getSubjectCode(); 
                    $subject["subjectName"] = $course->getSubject()->getSubjectName();
                   
                }
                $dateScheduled = $course->getDateScheduled();
                $startingTime = $course->getStartingTime()->format('H:i:s');
                $endingTime = $course->getEndingTime()->format('H:i:s');
                $timeFrame =  $startingTime."-".$endingTime;
                
               
                //$classroom = $course->getResource(); 
                $classroom["id"]=$course->getResource()->getId();
                $classroom["name"]=$course->getResource()->getName();
                $classroom["code"]=$course->getResource()->getCode();
                $classroom["type"]=$course->getResource()->getType();
                
             
                 
          
                
                $description = null;
                $student = []; 
                
                $registeredStd = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findBy(array("class"=>$classOfStudy["code"])); 
                $hydrator = new ReflectionHydrator();   
                foreach($registeredStd as $key=>$value)
                {
                    $std = $this->entityManager->getRepository(Student::class)->find($value->getStudentId()); 
                    $stdAttendance = $this->entityManager->getRepository(StudentAttendance::class)->findOneBy(["courseScheduled"=>$course,"student"=>$std]);

                        
                        $student[$key]["matricule"] = $value->getMatricule();
                        $student[$key]["nom"] = $value->getNom();
                        $student[$key]["prenom"] = $value->getPrenom();
                        if($stdAttendance)
                            $student[$key]["attendance"] = $stdAttendance->getStatus();
                        else $student[$key]["attendance"]=0;
                }
    
                if($course->getContractFollowUp())
                { 
                    $description= $course->getContractFollowUp()->getDescription();
                      
                  
                    $startingTime = $course->getContractFollowUp()->getStartTime()->format('H:i:s');
                    $endingTime =   $course->getContractFollowUp()->getEndTime()->format('H:i:s');     

                }
                
                
               
                     
                    // $classOfStudy= $hydrator->extract($classOfStudy);
               /*     if($course->getTeacher())$teacher = $course->getTeacher()->getName()." ".$course->getTeacher()->getSurname(); else $teacher = ""; 
                    $course = $hydrator->extract($course);
                    $course["eventName"] = $classOfStudy->getCode()." \n".$teachingUnit->getCode();
                   
                    $course["eventName"] .= "\n".$teacher;
                    
                    $myCourse[$key] = $course;
                    $key++;
     */
                
           // }

                

           // }


            $this->entityManager->getConnection()->commit();
            
           
            $output = new JsonModel([
                [
                    "contractId"=>$contract->getId(),
                    "scheduledId"=>$course->getId(),
                    "classOfStudy"=>$classOfStudy,
                    "semester"=>$semester,
                    "teachingUnit"=>$teachingUnit,
                    "subject"=>$subject,
                    "dateScheduled"=>$dateScheduled,
                    "startingTime"=>$startingTime,
                    "endingTime"=>$endingTime,
                    "timeFrame"=>$timeFrame,
                    "scheduleType"=>$scheduleType,
                    "isScheduleValidated"=>$isScheduleValidated,
                    "description"=>$description,
                    "students"=>$student,
                    "classroom"=>$classroom
                ]
                    
            ]);

            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            print($e->getMessage());
            throw $e;
            
        }         
        
    }
    
    public function printScheduleAction()
    {
        
        try
        { 
            $this->entityManager->getConnection()->beginTransaction();
            
            $fromDate= $this->params()->fromRoute('fromDate', -1); 
            $toDate = $this->params()->fromRoute('toDate', -1); 
            $classe = $this->params()->fromRoute('classe', -1); 

            $classOfStudy= $this->entityManager->getRepository(ClassOfStudy::class)->find($classe);
           
            $days = [1=>'Lundy', 2=>'Mardi', 3=>'Mercredi', 4=>'Jeudi', 5=>'Vendredi',6=>'Samedi'];            
            $slots = [
              ['07:30', '09:30'],
              ['10:00', '12:00'],
              ['13:00', '15:00'],
              ['15:30', '17:30'],
              ['18:00', '20:00'],
              ['20:30', '22:30']
            ];

            // Start HTML
            $html = "<style>
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #555; padding: 8px; text-align: center; }
            th { background-color: #f2f2f2; }
            td { background-color: #fafafa; }
            .subject { font-weight: bold; color: #006699; }
            </style>'";

            $html .= '<h2 style="text-align:center;">Emploi de temps</h2>';
            $html .= '<table><tr><th>Heure</th>';
            foreach ($days as $day) 
                $html .= "<th>$day</th>";
            $html .= '</tr>';
            
                
          
            $toDate = new \DateTime($toDate);
 
            // Rows by time slot
            foreach ($slots as $slot) 
            { 
                list($start,$end) = $slot;
                $html .= "<tr><td>".$start."-".$end."</td>";
                foreach ($days as $key=>$value)
                {   $date = new \DateTime($fromDate);   
                    while($date <= $toDate)
                    {                       
                        if($key== date('N',strtotime($date->format("Y-m-d"))))
                        {
                            list($h,$m) = explode(":",$start);
                                    
                            $startingTime = new \DateTime();
                            list($year,$month,$day) = explode("-",$startingTime->format('Y-m-d'));
                            $startingTime->setDate($year, $month, $day);                            
                            $startingTime = $date->setTime($h,$m,0); 
                            list($h,$m) = explode(":",$end);
                            
                            $endingTime = new \DateTime();
                            list($year,$month,$day) = explode("-",$startingTime->format('Y-m-d'));
                            $endingTime->setDate($year, $month, $day);
                            $endingTime = $endingTime->setTime($h,$m,0); 
                            
                            
                            $course = $this->entityManager->getRepository(CourseScheduled::class)->findOneBy(["classOfStudy"=>$classOfStudy,
                              'startingTime'=>$startingTime,"endingTime"=>$endingTime]);
                            $subject = [];
                            if ($course) 
                            {
                                
                                if($course->getTeachingUnit())
                                {
                                    $subject["code"] = $course->getTeachingUnit()->getCode();
                                    $subject["name"] = $course->getTeachingUnit()->getName();

                                }

                                if($course->getSubject())
                                { 
                                    $subject["id"] = $course->getSubject()->getId(); 
                                    $subject["code"] = $course->getSubject()->getSubjectCode(); 
                                    $subject["name"] = $course->getSubject()->getSubjectName();

                                }
                                $subject["classroom"] = "";
                                if($course->getResource()){ 
                                    $classroom = $course->getResource();
                                    $subject["classroom"] = $classroom->getName();
                                    $building = $classroom->getResource();
                                    $campus = $building->getResource();
                                    $building = $building->getName();
                                    $campus = $campus->getName();
                                    $subject["classroom"] = "[".$campus."]"."[".$building."]"."[".$subject["classroom"]."]";
                                    
                                    
                                }
                                $subject["teacher"] = "";
                                if($course->getTeacher()) $subject["teacher"] = $course->getTeacher()->getCivility()." ".$course->getTeacher()->getName();
                                
                                else $subject["teacher"] = "";
                                
                                $subject["type"]= $course->getScheduleType();
                                

 
                              $html .= "<td>
                                <div class='subject'>".$subject['type']."</div>
                                <div class='subject'><br>".$subject['code']."<br> <small>".$subject['name']."</small>
                                    <br>".$subject["teacher"]."</div>
                                <small><br>".$subject["classroom"]."</small>            

                                </td>"; 
                            } 
                            else             $html .= "<td><div class='subject'>RAS</div></td>";
                        }
                        $date->modify('+1 day');                       
                    } 
                 }
              
                    $html .= '</tr>';
            }
            $html .= '</table>';
        
            
            $this->entityManager->getConnection()->commit();
            

            $output = new ViewModel([
                'html'=>$html
            ]);
            $output->setTerminal(true);

            return $output;             
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            print($e->getMessage());
            throw $e;
            
        }       
    }
    
    public function deleteScheduledCourseAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $key = 0;
            $myCourse = [];
            $data= $this->params()->fromQuery();    
            $idCourse = (int)$data["id"];

                $course= $this->entityManager->getRepository(CourseScheduled::class)->find($idCourse);  
               
                $this->entityManager->remove($course);
                $this->entityManager->flush();
                

            $this->entityManager->getConnection()->commit(); 
           
            
           
            $output = new JsonModel([
                [

                ]
                    
            ]);

            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            
           print( $e->getMessage());
           throw $e;
            
        }         
        
    }     
    
    public function printWorkloadFollowUpAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $data= $this->params()->fromRoute();                                       
       
            $subjects=[];
            $billItems = [];
            $teacher =[];
            $subject = [];
           
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            
            //retrive all current year contract
            $teacher = $this->entityManager->getRepository(Teacher::class)->findAll([],["name"=>"ASC"]);
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
            $contracts = $this->entityManager->getRepository(AllContractsView::class)->findAll(); 
            foreach($teacher as $key=>$teach)
            {
                $contracts = $this->entityManager->getRepository(Contract::class)->findBy(array("teacher"=>$teach,"academicYear"=>$this->crtAcadYr)); 
                
                $totalVolumeAllocated = 0;
                $totalVolumeDone = 0;
                if(sizeof($contracts)> 0)
                {
                
                    foreach($contracts as $con)
                    {
                        $totalVolumeAllocated += $con->getVolumeHrs();
                        $contractsFwu = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($con);
                        
                         
                        foreach($contractsFwu as $conFwu)
                            $totalVolumeDone += $conFwu->getTotalTime();


                    }
                    //Collecte the payment rate
                  /*  if($teach->getAcademicRanck()) 
                        $pymtRate = $teach->getAcademicRanck()->getPaymentRate();
                    else $pymtRate = 0;    */                
$pymtRate = 0;
                    $teachers[$key]["teacherName"]=$teach->getName()." ".$teach->getSurname();
                    $teachers[$key]["totalVolumeAllocated"] = $totalVolumeAllocated;
                    $teachers[$key]["totalVolumeDone"] = $totalVolumeDone;
                    $teachers[$key]["volumeGap"] = $totalVolumeAllocated - $totalVolumeDone;
                    $teachers[$key]["paymentRate"] = $pymtRate;
                    
                    
                    $totalVolumePaid = 0;
                    $totalAmountPaid = 0;  
                    
                    foreach($contracts as $con)
                    { 
                        $paymentBill = $this->entityManager->getRepository(TeacherPaymentBill::class)->findOneBy(array("teacher"=>$teach,"contract"=>$con)); 
 
                        if($paymentBill)
                        {
                            $totalVolumePaid += $paymentBill->getTotalTimePreviouslyBilled()+$paymentBill->getTotalTimeCurrentlyBilled(); 
                            $totalAmountPaid += $paymentBill->getPaymentAmount();
                            

                        }
                    }
                    $teachers[$key]["totalVolumePaid"] = $totalVolumePaid;
                    $teachers[$key]["amountPaid"] = $totalAmountPaid;                    
                }
            }

            //Sorting the $std array according to the key "nom"
            $tmp = Array();
            foreach($teachers as &$ma)
                $tmp[] = &$ma["teacherName"];
            array_multisort($tmp, $teachers);
            
            
            $this->entityManager->getConnection()->commit();

            $output = new ViewModel([
               "teachers"=>$teachers,

            ]);
             $output->setTerminal(true);
            return $output;       
            
        }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }   
    }
    
    public function generateBillAction()
    {
        
        $this->entityManager->getConnection()->beginTransaction();
        
            $subjects=[];
            $bills = [];
       
            $teachers = $this->entityManager->getRepository(Teacher::class)->findAll([],array("name"=>"ASC"));
            $teacherInfo = [];
            $i = 0;
            
            foreach($teachers as $teach)
            {
                $actualBilledTime = 0;
                $overtime = 0;
                $totalAmount = 0;
                $totalTimeBilled = 0; 

            //$teacher= $this->entityManager->getRepository(Teacher::class)->find($data["teacherID"] );
            
            $teacher= $this->entityManager->getRepository(Teacher::class)->find($teach->getId());
            
            //Collecte the payment rate
            if($teacher->getAcademicRanck())
                $pymtRate = $teacher->getAcademicRanck()->getPaymentRate();
            else $pymtRate = 0;            
            
            
            //Seacrch contracts in which teacher is involved
            $acadYr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1); 
            $contracts= $this->entityManager->getRepository(Contract::class)->findBy(array("teacher"=>$teacher,"academicYear"=>$acadYr) );

            
            $billSumary = new TeacherPaymentBillSumary();
            $billSumary->setPaymentAmount($totalAmount); 
            $billSumary->setDate(new \DateTime( date('Y-m-d'))); 
            $billSumary->setAcademicYear($acadYr);
            $billSumary->setTeacher($teacher);
            
            $this->entityManager->persist($billSumary); 
           
                      

            //Asuming we did not find any item to bill
             $flag = 0;
             $cptOverTime=0;
             $totalTimeScheduled= 0;
             
         
            foreach($contracts as $contract)
            {           
                $amount = 0;
                $actualTimeToBill = 0; 
                $totalTimeScheduled += $contract->getVolumeHrs(); 
                $contractFollowUp = $this->entityManager->getRepository(ContractFollowUp::class)->findByContract($contract);
                
                //Skip if there is notime to bill
                if(sizeof($contractFollowUp)<=0) continue; 
                
                //lookind only to items not already paid
                $contractNotYetPaid= $this->entityManager->getRepository(ContractFollowUp::class)->findBy(["contract"=>$contract,"teacherPaymentBill"=>NULL] );
                if (sizeof($contractNotYetPaid)>0) $flag = 1;
                
                if(sizeof($contractNotYetPaid)<=0) continue;
               
                //Check if other bills exist on this contract
                $bills = $this->entityManager->getRepository(TeacherPaymentBill::class)->findBy(["teacher"=>$teacher,"contract"=>$contract]);


                //count number of bill already paid 
                $cptBillAlreadyPaid = sizeof($bills);
                 
                //if other bill exist, calculate the over all time already billed
                $alreadyBilledTime = 0;
                foreach($bills as $bill) $alreadyBilledTime += $bill->getTotalTimePreviouslyBilled();  
                

                $paymentDetails = [];
                $billedTime = 0;
                

              
                
                $pymtBill = new TeacherPaymentBill(); 
                
                $this->entityManager->persist($pymtBill); 
                
                
                foreach($contractNotYetPaid as $con)
                {
                      
                        
                    $actualTimeToBill += $con->getTotalTime();


                    if($contract->getVolumeHrs()> $alreadyBilledTime+$actualTimeToBill)
                    {

                        
                        $con->setTeacherPaymentBill($pymtBill);
                        //$this->entityManager->flush();

                        /*$hydrator = new ReflectionHydrator();
                        $data_1 = $hydrator->extract($con);
                        $data_1["paymentRate"] = $pymtRate;
                        $data_1["paymentAmount"] = $billedTime * $pymtRate;
                        $paymentDetails[$k] = $data; $k++; 

                        $actualBilledTime = $billedTime;


                        $amount += $billedTime*$pymtRate;*/


                    }else
                    { 
                        $overtime  = ($alreadyBilledTime+$actualTimeToBill)-$contract->getVolumeHrs(); 
                        $actualTimeToBill = $actualTimeToBill-$overtime;
                       
                        

                /*        $hydrator = new ReflectionHydrator();
                        $data_1 = $hydrator->extract($con);
                        $data_1["paymentRate"] = $pymtRate;
                        $data_1["overtime"] = $overtime;
                        $data_1["paymentAmount"] = $billedTime * $pymtRate;
                        $paymentDetails[$k] = $data_1; $k++; */

                    }

                }
              
                 $amount += $actualTimeToBill*$pymtRate;
                 $totalAmount += $amount;
               
                $pymtBill->setOverTime($overtime); 
                if($contract->getSubject()) $pymtDetails = $contract->getSubject()->getSubjectName(); 
                if($contract->getTeachingUnit()) $pymtDetails = $contract->getTeachingUnit()->getName(); 
                $pymtBill->setPaymentDetails($pymtDetails); 
                $pymtBill->setDate(new \DateTime( date('Y-m-d'))); 
                $pymtBill->setPaymentAmount($amount); 
                $pymtBill->setTotalTime($contract->getVolumeHrs()); 
                ($cptBillAlreadyPaid === 0)? $pymtBill->setTotalTimePreviouslyBilled(0): $pymtBill->setTotalTimePreviouslyBilled($alreadyBilledTime+$actualTimeToBill) ; 
                $pymtBill->setTotalTimeCurrentlyBilled($actualTimeToBill);
                $pymtBill->setTeacher($teacher);
                $pymtBill->setContract($contract); 
                $pymtBill->setTeacherPaymentBillSumary($billSumary);
                
                $totalTimeBilled+= $actualTimeToBill;
               
                      
               
                
                
                //$this->entityManager->flush();
               

            }
            

          
            $billSumary->setPaymentAmount($totalAmount); 
            $billSumary->setTotalTimePaid($totalTimeBilled);
            $billSumary->setTotalTimeScheduled($totalTimeScheduled);
                      
            $this->entityManager->flush(); 
            if($flag === 0){$this->entityManager->remove($billSumary); continue;
                $output = new JsonModel([
                    ["error"=>1] //Absence d'heure de cours à facturer
                ]);
                return $output;                        
            } 
            if(sizeof($contracts) === $cptOverTime){ $this->entityManager->remove($billSumary);  continue;
                $output = new JsonModel([
                    ["error"=>2] //Volume horaire dépassé
                ]);
                return $output;                        
            } 
            

             
              
                
    
           
            
            $billDetails = $this->entityManager->getRepository(TeacherPaymentBill::class)->findByTeacherPaymentBillSumary($billSumary);
            $hydrator = new ReflectionHydrator();
            $billSumary = $hydrator->extract($billSumary);
           
            $billSumaryItems = [];
             
            foreach ($billDetails as $key=>$value)
            {
                $hydrator = new ReflectionHydrator();
                $billSumaryItems[$key] = $hydrator->extract($value);                
            }
            
            $hydrator = new ReflectionHydrator();
            $teacher = $hydrator->extract($teacher);
            $teacher["paymentRate"] = $pymtRate;
            $i++;
            
            $teacherInfo[$i]['info']=$teacher;
            $teacherInfo[$i]["bill_items"] = $billSumaryItems;
            $teacherInfo[$i]['bill_sumary'] = $billSumary;
           
         }
         
        $this->entityManager->getConnection()->commit(); 
        
        
            $output = new ViewModel([
               "teachers"=>$teacherInfo,
               

            ]);
             $output->setTerminal(true);
            return $output;          
    }
    
    private function checkTimeConflictByClass($classe,$startingTime)
    {
        
                    $query = $this->entityManager->createQuery('SELECT c.id  FROM Application\Entity\CourseScheduled c'
                    .' JOIN c.classOfStudy cl'
                    .' WHERE cl.id =:classe'
                    .' AND :startingTime BETWEEN c.startingTime AND  c.endingTime'
                    );
            $query->setParameter('classe', $classe);
            $query->setParameter('startingTime', $startingTime);
            $courseScheduled = $query->getResult();

            if(sizeof($courseScheduled)>0) return 1;
            return 0;
        
        
    }
    private function checkClassromConflict($startingTime,$classroom)
    {
        
                    $query = $this->entityManager->createQuery('SELECT c.id  FROM Application\Entity\CourseScheduled c'
                    .' JOIN c.resource r'        
                    .' WHERE r.id = :classroom'
                    .' AND :startingTime BETWEEN c.startingTime AND  c.endingTime'
                    );
            $query->setParameter('startingTime', $startingTime);
            $query->setParameter('classroom', $classroom);
            $courseScheduled = $query->getResult();

            if(sizeof($courseScheduled)>0) return 1;
            return 0;
        
        
    }    
    

    
    
    
}
