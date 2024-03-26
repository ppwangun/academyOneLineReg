<?php
namespace UserSpace;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;



return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,

        ],
    ],
    // We register module-provided controller plugins under this key.
    'controller_plugins' => [

    ],
    'router' => [
        'routes' => [
            
               'goLecturer' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/goLecturer',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'goLecturer',
                    ],
                ],
            ],            

        ]
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout_1/layout_1'           => __DIR__ . '/../view/layout/layout.phtml',
         
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __NAMESPACE__ . '/../view',
        ],
    ],
    
    'service_manager' => [
        'factories' => [

            
        ],
    ],
    // We register module-provided view helpers under this key.
    'view_helpers' => [
        'factories' => [

        ],
        'aliases' => [

        ],
    ],

    'session_containers' => [

        ],
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [

    ],

    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [

            Controller\IndexController::class => [

                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*', 
                 'allow' => '*'],

            ],

        ]
    ],    
    


  /* */

];
