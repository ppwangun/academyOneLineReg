<?php
namespace User;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\RoleController::class => Controller\Factory\RoleControllerFactory::class,
            Controller\PermissionController::class => Controller\Factory\PermissionControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\CheckPermissionController::class => Controller\Factory\CheckPermissionControllerFactory::class,
            Controller\ClassesAssociatedToUserController::class => Controller\Factory\ClassesAssociatedToUserControllerFactory::class,
        ],
    ],
    // We register module-provided controller plugins under this key.
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\AccessPlugin::class => Controller\Plugin\Factory\AccessPluginFactory::class,
        ],
        'aliases' => [
            'access' => Controller\Plugin\AccessPlugin::class,
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type'    => 'Literal',
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/user',
                    'defaults' => [
                        'controller'    => Controller\UserController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                ],
            ],
               'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
               'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
               'usermanagementpl' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/usermanagementpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ], 
               'newusertpl' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/newusertpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newuser',
                    ],
                ],
            ],
               'adduser' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/adduser',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                    ],
                ],
            ],
               'groupoperation' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/groupoperation',
                    'defaults' => [
                        'controller' => Controller\RoleController::class,
                    ],
                ],
            ],
               'assignclasstouser' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/assignclasstouser',
                    'defaults' => [
                        'controller' => Controller\ClassesAssociatedToUserController::class,
                    ],
                ],
            ],
               'grouplist' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/grouplist',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'grouplist',
                    ],
                ],
            ],
               'newgrouptpl' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/newgrouptpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newgrouptpl',
                    ],
                ],
            ],
               'permissions' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/permissions',
                    'defaults' => [
                        'controller' => Controller\PermissionController::class,
                      
                    ],
                ],
            ],
               'checkpermission' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/checkpermission',
                    'defaults' => [
                        'controller' => Controller\CheckPermissionController::class,
                    ],
                ],
            ],
            'not-authorized' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/not-authorized',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'notAuthorized',
                    ],
                ],
            ],
            
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
    ],
    
    'service_manager' => [
        'factories' => [
            \Laminas\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\RbacManager::class => Service\Factory\RbacManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
            
        ],
    ],
    // We register module-provided view helpers under this key.
    'view_helpers' => [
        'factories' => [
            View\Helper\Access::class => View\Helper\Factory\AccessFactory::class,
            View\Helper\CurrentUser::class => View\Helper\Factory\CurrentUserFactory::class,
        ],
        'aliases' => [
            'access' => View\Helper\Access::class,
            'currentuser' => View\Helper\CurrentUser::class,
        ],
    ],

    'session_containers' => [
        'LoggedInUser'
        ],
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],

    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [

            Controller\IndexController::class => [

                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*', 
                 'allow' => '@'],

            ],
            Controller\ExamReportsController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => '*', 
                 'allow' => '@']
            ],

        ]
    ],    
    


  /* */

];
