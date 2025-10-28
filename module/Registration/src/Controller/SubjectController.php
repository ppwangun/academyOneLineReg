<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;


class SubjectController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer= $sessionContainer;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        { 
            $subjects=[];
            //retrive the current loggedIn User
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            $acadyrId = $this->sessionContainer->currentAcadYr->getId() ;
           
            //check first the user has global permission or specific permission to access exams informations
            if($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
            {            
                // retrieve subjects based on subject code

                //$rsm = new ResultSetMapping();
                // build rsm here

                $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId FROM Application\Entity\AllYearsTeachingUnitView c'
                        .' WHERE c.codeUe LIKE :code AND c.acadYrId = :year_id');
                $query->setParameter('code', '%'.$id.'%');
                $query->setParameter('year_id', $acadyrId);

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
                        $query = $this->entityManager->createQuery('SELECT c.id,c.codeUe,c.nomUe,c.classe,c.semester,c.semId  FROM Application\Entity\AllYearsTeachingUnitView c'
                                .' WHERE c.classe = :classe AND c.codeUe LIKE :code AND c.acadYrId = :year_id');
                        $query->setParameter('code', '%'.$id.'%')
                                ->setParameter('classe',$classe->getClassOfStudy()->getCode());
                        $query->setParameter('year_id', $acadyrId);

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
    
    public function getList()
    {
       $this->entityManager->getConnection()->beginTransaction();
        try
        {   
            $subjects= $this->entityManager->getRepository(CurrentYearTeachingUnitView::class)->findAll();
            $i= 0;
            foreach($subjects as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);

                $subjects[$key] = $data;
            }

            for($i=0;$i<sizeof($subjects);$i++)
            {
                $subjects[$i]['nomUe']= utf8_encode($subjects[$i]['nomUe']);
                $subjects[$i]['codeUe']= utf8_encode($subjects[$i]['codeUe']);
                //$ue[$i]['prenom']= mb_convert_encoding($ue[$i]['prenom'], 'UTF-8', 'UTF-8');
                
            }              

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
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
    
   
    

}
