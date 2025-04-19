<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Exam\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Exam\Service\ExamManager;


/**
 * This is the factory for PostManager. Its purpose is to instantiate the
 * service.
 */
class ExamManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                    $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
                
        // Instantiate the service and inject dependencies
        return new ExamManager($entityManager);
    }
}