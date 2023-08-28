<?php
namespace Registration;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\CountriesController::class => Controller\Factory\CountriesControllerFactory::class,
            Controller\OnlineRegController::class => Controller\Factory\OnlineRegControllerFactory::class,
            Controller\StdRegistrationController::class => Controller\Factory\StdRegistrationControllerFactory::class,
            Controller\SubjectRegistrationController::class => Controller\Factory\SubjectRegistrationControllerFactory::class,
            Controller\PreRegistrationController::class => Controller\Factory\PreRegistrationControllerFactory::class,
            Controller\SubjectController::class => Controller\Factory\SubjectControllerFactory::class,
            Controller\StdRegisteredToSubjectController::class => Controller\Factory\StdRegisteredToSubjectControllerFactory::class,
            Controller\StdRegisteredToExamController::class => Controller\Factory\StdRegisteredToExamControllerFactory::class,
            Controller\StdAdmissionController::class => Controller\Factory\StdAdmissionControllerFactory::class,
            Controller\PaymentController::class => Controller\Factory\PaymentControllerFactory::class,
        ],
    ],
    'session_containers' => [
        'onlineLoggedInUser'
        ],    
    'service_manager' => [
        'factories' => [
        Service\StudentManager::class =>Service\Factory\StudentManagerFactory::class,
        ],
    ],
// We register module-provided view helpers under this key.
'view_helpers' => [
    'factories' => [
        View\Helper\CheckUserAccess::class => View\Helper\Factory\CheckUserAccessFactory::class,
    ],
    'aliases' => [
        'checkUserAccess' => View\Helper\CheckUserAccess::class,
    ],
],
    'router' => [
        'routes' => [
            'registration' => [
                'type'    => 'Literal',
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/index',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                ],
            ],
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'preRegistration' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/preRegistration',
                    'defaults' => [
                        'controller' => Controller\PreRegistrationController::class,
                        'action'     => 'preRegistration',
                    ],
                ],
            ],   
            'savePreRegistration' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/savePreRegistration',
                    'defaults' => [
                        'controller' => Controller\PreRegistrationController::class,
                        'action'     => 'savePreRegistration',
                    ],
                ],
            ],            
            'chkPayment' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/chkPayment',
                    'defaults' => [
                        'controller' => Controller\PaymentController::class,
                        'action'     => 'chkPayment',
                    ],
                ],
            ],            
            'chkNewStudentPayment' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/chkNewStudentPayment',
                    'defaults' => [
                        'controller' => Controller\PaymentController::class,
                        'action'     => 'chkNewStudentPayment',
                    ],
                ],
            ],
            'studentRegistration' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/studentRegistration',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'studentRegistration',
                    ],
                ],
            ],
            'newStudentRegistration' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newStudentRegistration',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newStudentRegistration',
                    ],
                ],
            ],
            'saveNewStudentRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/saveNewStudentRegistration[/:student]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'saveNewStudentRegistration',
                    ],
                ],
            ],
            'students' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/students[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegistrationController::class,
                        'action'     => 'students',
                    ],
                ],
            ],
            'countries' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/countries[/:id]',
                    'defaults' => [
                        'controller' => Controller\CountriesController::class,
                        'action'     => 'countries',
                    ],
                ],
            ],
            'regions' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/regions[/:id]',
                    'defaults' => [
                        'controller' => Controller\CountriesController::class,
                        'action'     => 'regions',
                        
                    ],
                ],
            ],
            'cities' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cities[/:id]',
                    'defaults' => [
                        'controller' => Controller\CountriesController::class,
                        'action'     => 'cities',
                       
                    ],
                ],
            ],
            'searchFilByFaculty' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchFilByFaculty[/:id]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'searchFilByFaculty',
                       
                    ],
                ],
            ],   
            'searchCycleFormation' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchCycleFormation[/:id]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'searchCycleFormation',
                       
                    ],
                ],
            ],  
            'searchDegree' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchDegree[/:id]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'searchDegree',
                       
                    ],
                ],
            ],
            '/' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'index',
                    ],
                ],
            ],             
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'index',
                    ],
                ],
            ],            
            'apply' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/apply',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'apply',
                    ],
                ],
            ],             
             'submitRegistrationForm' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/submitRegistrationForm[/:classe]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'submitregistrationform',
                       
                    ],
                ],
            ],  
             'searchapplicationfile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchapplicationfile[/:classe]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'searchapplicationfile',
                       
                    ],
                ],
            ],            
             'endApplication' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/endApplication[/:classe]',
                    'defaults' => [
                        'controller' => Controller\OnlineRegController::class,
                        'action'     => 'endApplication',
                       
                    ],
                ],
            ],            
             'saveRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/saveRegistration[/:classe]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'saveRegistration',
                       
                    ],
                ],
            ],            
            'insPedagogique' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/insPedagogique[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'insPedagogique',
                                               
                    ],
                ],
            ],
            'saveInsPedagogique' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/saveInsPedagogique[/:student]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'saveInsPedagogique',
                                               
                    ],
                ],
            ],   
            'endRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/endRegistration[/:student]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'endRegistration',
                                               
                    ],
                ],
            ],
            'subjectsearch' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/subjectsearch[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubjectController::class,
                       
                    ],
                ],
            ], 
            'admittedStudent' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admittedStudent[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdAdmissionController::class,
                       
                    ],
                ],
            ],             
            'stdregisteredtosubject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregisteredtosubject[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegisteredToSubjectController::class,
                       
                    ],
                ],
            ], 
            'stdregisteredtosubject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregisteredtosubject[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegisteredToSubjectController::class,
                       
                    ],
                ],
            ],             
            'subjectsByClasse' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/subjectsByClasse[/:classe]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'subjectsByClasse'
                       
                    ],
                ],
            ],
            'registeredSubjectsByStudent' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/registeredSubjectsByStudent[/:matricule]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'registeredSubjectsByStudent'
                       
                    ],
                ],
            ],            
             'importstudents' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importstudents',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'importstudents',
                       
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'registration/online-reg/index' => __DIR__ .'/../view/Registration/onlinereg/index.phtml',
            'registration/online-reg/apply' => __DIR__ .'/../view/Registration/onlinereg/apply.phtml',
            'registration/online-reg/submitregistrationform' => __DIR__ .'/../view/Registration/onlinereg/submitregistrationform.phtml',
            'registration/online-reg/searchapplicationfile' => __DIR__ .'/../view/Registration/onlinereg/searchapplicationfile.phtml',
            'registration/online-reg/end-application' => __DIR__ .'/../view/Registration/onlinereg/endapplication.phtml',
   
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
       'strategies' => [
            'ViewJsonStrategy',
        ],

    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'controllers' => [

            Controller\IndexController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*','allow' => '@'],
            ],
            Controller\PaymentController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*','allow' => '@'],
            ],            
            Controller\PreRegistrationController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => ['preRegistration','savePreRegistration'],'allow' => '*'],
               // ['actions' => '*','allow' => '@'],
            ],            
            Controller\CountriesController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*','allow' => '@'],
            ],
        ]
    ],
];
