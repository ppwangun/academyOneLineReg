#!/usr/bin/env php
<?php

require_once "vendor/autoload.php";


use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

// replace with path to your own project bootstrap file

$paths = ['module/Application/src/Entities2'];
$isDevMode = false;

// the connection configuration
$dbParams = [
    'driver'   => 'pdo_mysql',
    'user'     => 'udm_root',
    'password' => 'wpp',
    'dbname'   => 'udm_academy',
];

//$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
// Create a simple "default" Doctrine ORM configuration for Attributes
$config = ORMSetup::createAnnotationMetadataConfiguration($paths,$isDevMode);

// configuring the database connection
$connection = DriverManager::getConnection($dbParams, $config);



// obtaining the entity manager
$entityManager = new EntityManager($connection, $config);


$entityManager->getConfiguration()->setMetadataDriverImpl(
    new Doctrine\ORM\Mapping\Driver\DatabaseDriver(
        $entityManager->getConnection()->getSchemaManager()
    )
);

/*$cmf = new Doctrine\ORM\Tools\DisconnectedClassMetadataFactory();
$cmf->setEntityManager($entityManager);
$metadata = $cmf->getAllMetadata();*/

/*$cme = new Doctrine\ORM\Tools\Export\ClassMetadataExporter();
$exporter = $cme->getExporter('xml', 'module/Application/src/Entities2/yml');
$exporter->setMetadata($metadata);
$exporter->export();*/

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);