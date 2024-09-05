<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Registration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;
use Violet\StreamingJsonEncoder\StreamJsonEncoder; 
use Violet\StreamingJsonEncoder\BufferJsonEncoder;

use Application\Entity\RegisteredStudentView;
use Application\Entity\RegisteredStudentForActiveRegistrationYearView;
use Application\Entity\User;
use Application\Entity\UserManagesClassOfStudy;

class StdFromPvController extends AbstractRestfulController
{
    private $entityManager;
    private $sessionContainer;
    
    public function __construct($entityManager,$sessionContainer) {
        
        $this->entityManager = $entityManager; 
        $this->sessionContainer = $sessionContainer;
    }
    
    public function get($id) {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {      
            $registeredStd = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findOneByMatricule($id);
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($registeredStd);
                $data['dateNaissance']=$data['dateNaissance']->format('Y-m-d');
                $data['dateInscription']=$data['dateInscription']->format('Y-m-d');

            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $data
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
            $userId = $this->sessionContainer->userId;
            $user = $this->entityManager->getRepository(User::class)->find($userId );
            if ($this->access('all.classes.view',['user'=>$user])||$this->access('global.system.admin',['user'=>$user])) 
                   $registeredStd = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findBy(array(),array("nom"=>"ASC"));
            else{
                $registeredStd = [];
                //Find clases mananged by the current user
                $userClasses = $this->entityManager->getRepository(UserManagesClassOfStudy::class)->findBy(Array("user"=>$user));
                
                if($userClasses)
                {
                    foreach($userClasses as $classe)
                    {
                        $registeredStd_1 = $this->entityManager->getRepository(RegisteredStudentForActiveRegistrationYearView::class)->findBy(array("class"=>$classe->getClassOfStudy()->getCode()),array("nom"=>"ASC"));
                        $registeredStd = array_merge($registeredStd,$registeredStd_1);
                        
                    }
                }                
            }
            $i= 0;
            foreach($registeredStd as $key=>$value)
            {
                $i++;
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $registeredStd[$key] = $data;
            }
            
            for($i=0;$i<sizeof($registeredStd);$i++)
            {
               // $registeredStd[$i]['nom']= utf8_encode($registeredStd[$i]['nom']);
                //$registeredStd[$i]['prenom']= utf8_encode($registeredStd[$i]['prenom']);
                
            }
            
           $this->entityManager->getConnection()->commit();
            $output = new JsonModel([
                    $registeredStd
            ]);
           
            return $output;       }
        catch(Exception $e)
        {
           $this->entityManager->getConnection()->rollBack();
            throw $e;
            
        }
    }
  private function char($text) { $text = htmlentities($text, ENT_NOQUOTES, "UTF-8"); $text = htmlspecialchars_decode($text); return $text; }

}
