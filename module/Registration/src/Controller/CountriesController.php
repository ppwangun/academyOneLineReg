<?php

namespace Registration\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Controller\AbstractRestfulController;
use Laminas\View\Model\JsonModel;
use Laminas\Hydrator\ReflectionHydrator;

use Application\Entity\Countries;
use Application\Entity\States;
use Application\Entity\Cities;

class CountriesController extends AbstractActionController
{
    private $entityManager;
    public function __construct($entityManager) {
        $this->entityManager = $entityManager;
    }
    
    public function countriesAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $countries = $this->entityManager->getRepository(Countries::class)->findAll();
            foreach($countries as $key=>$value)
            {
              
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $countries[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $countries
            ]); 
            
            return $output;
        }
        catch (Exception $ex) {

        }    
    }
    public function regionsAction()
    {
        $this->entityManager->getConnection()->beginTransaction();
        try
        {
            $countryCode = $this->params()->fromQuery('code', 'default_val');
            $country = $this->entityManager->getRepository(Countries::class)->findOneBySortname($countryCode);
            
            $regions = $this->entityManager->getRepository(States::class)->findByCountry($country);
            foreach($regions as $key=>$value)
            {
              
                $hydrator = new ReflectionHydrator();
                $data = $hydrator->extract($value);
                $regions[$key] = $data;
            }
            $this->entityManager->getConnection()->commit();
            
            //$output = json_encode($output,$depth=1000000); 
            $output = new JsonModel([
                    $regions
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
            $countryCode = $this->params()->fromQuery('id', 'default_val'); 
            $country = $this->entityManager->getRepository(Countries::class)->findOneBySortname($countryCode);

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
}
