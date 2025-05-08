<?php
namespace Payment;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\PaymentController::class => Controller\Factory\PaymentControllerFactory::class,
            Controller\PaymentJournalController::class => Controller\Factory\PaymentJournalControllerFactory::class,
            Controller\AdmissionPaymentController::class => Controller\Factory\AdmissionPaymentControllerFactory::class,
            Controller\MoratoriumController::class => Controller\Factory\MoratoriumControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
        Service\PaymentManager::class =>Service\Factory\PaymentManagerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'Payment' => [
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

            'payment' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/payment[/:id]',
                    'defaults' => [
                        'controller' => Controller\PaymentController::class,
                       
                    ],
                ],
            ],
            'admissionPayment' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/admissionPayment[/:id]',
                    'defaults' => [
                        'controller' => Controller\AdmissionPaymentController::class,
                       
                    ],
                ],
            ],
            'paymentlist' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/paymentlist',
                    'defaults' => [
                        'controller' => Controller\PaymentController::class,
                       
                    ],
                ],
            ],
            'paymentdetailstpl' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/paymentdetailstpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'paymentdetailstpl',
                       
                    ],
                ],
            ],  
            'savePymtTransaction' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/savePymtTransaction',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'savePymtTransaction',
                       
                    ],
                ],
            ],            
            'payments' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/payments',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'payments',
                    ],
                ],
            ],
            'updateStdPymt' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/updateStdPymt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'updateStdPymt',
                    ],
                ],
            ],            
             'newStdRegistrationFeesMgt' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newStdRegistrationFeesMgt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newStdRegistrationFeesMgt',
                       
                    ],
                ],
            ],            
            'moratoriums' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/moratoriums',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'moratoriums',
                    ],
                ],
            ],
            'newmoratorium' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/newmoratorium',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newmoratorium',
                    ],
                ],
            ],
            'journaltier' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/journaltier',
                    'defaults' => [
                        'controller' => Controller\PaymentJournalController::class,
                        
                    ],
                ],
            ],
            'moratorium' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/moratorium',
                    'defaults' => [
                        'controller' => Controller\MoratoriumController::class,
                        
                    ],
                ],
            ],
            'importBalance' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/importBalance',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'importBalance',
                    ],
                ],
            ], 
            'importPayments' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/importPayments',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'importPayments',
                    ],
                ],
            ], 
            'importDotations' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/importDotations',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'importDotations',
                    ],
                ],
            ],            
        ],
    ],
    'view_manager' => [
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
                ['actions' => '*', 
                 'allow' => '@'],

            ],
        ]
    ],
];
