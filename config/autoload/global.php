<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PDOMySqlDriver;
//use Doctrine\DBAL\Driver\PDO\MySql\Driver as PDOMySqlDriver;
use Laminas\Session\Storage\SessionArrayStorage;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Cache\Storage\Adapter\Filesystem;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '127.0.0.1', 
                    //'host'     => '172.16.5.12',
                    'user'     => 'udm_root',
                    'password' => 'wpp',
                    'dbname'   => 'udm_academy',
                    'charset'  => 'utf8',
                    'driverOptions' => [1002 => 'SET NAMES utf8'],
                ]
            ],            
        ],        
    ],
    
    // Session configuration.
    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60*60*1*12,     
        // Session data will be stored on server maximum for 30 days.
        'gc_maxlifetime'     => 60*60*24*30, 
    ],
    
    'session_containers' => [
        Laminas\Session\Container::class,
    ],
    // Session manager configuration.
   /* 'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],*/
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'caches' => [
    'FilesystemCache' => [
        'adapter' => \Laminas\Cache\Storage\Adapter\Filesystem::class,
        'options' => [
            // Store cached data in this directory.
            'cache_dir' => './data/cache',
            // Store cached data for 1 hour.
            'ttl' => 60 * 60 * 1
        ],
        'plugins' => [
            [
                'name' => 'serializer',
                'options' => []
            ]
        ]
    ]
    ],
];