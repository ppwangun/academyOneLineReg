<?php
namespace Exam;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\ExamReportsController::class => Controller\Factory\ExamReportsControllerFactory::class,
            Controller\ExamController::class => Controller\Factory\ExamControllerFactory::class,
            Controller\CalculNotesController::class => Controller\Factory\CalculNotesControllerFactory::class,
            Controller\ExamRegistrationController::class => Controller\Factory\ExamRegistrationControllerFactory::class,
            Controller\ExamTypeController::class => Controller\Factory\ExamTypeControllerFactory::class,
            Controller\GradeController::class => Controller\Factory\GradeControllerFactory::class,
            Controller\GradeRangeController::class => Controller\Factory\GradeRangeControllerFactory::class,
            Controller\DelibConfigController::class => Controller\Factory\DelibConfigControllerFactory::class,
            Controller\ParcourtController::class => Controller\Factory\ParcourtControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
        Service\ExamManager::class =>Service\Factory\ExamManagerFactory::class,
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
            'examlistpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/examlistpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'examlistpl',
                    ],
                ],
            ],
            'newgrade' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newgrade',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newgrade',
                    ],
                ],
            ],
            'gradelist' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/gradelist',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'gradelist',
                    ],
                ],
            ],
            'newexam' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newexam',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newexam',
                    ],
                ],
            ],
            'calculnotestpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/calculnotestpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'calculnotestpl',
                    ],
                ],
            ],
            'calculmpstpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/calculmpstpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'calculmpstpl',
                    ],
                ],
            ],
            'suiviparcourtpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/suiviparcourtpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'suiviparcourtpl',
                    ],
                ],
            ],
            'afficherparcourt' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/afficherparcourt[/:id]',
                    'defaults' => [
                    'controller' => Controller\ParcourtController::class,
                    'action' => 'getparcourtbyclass'
                    ],
                ],
            ],
            'afficheruenonvalides' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/afficheruenonvalides[/:id]',
                    'defaults' => [
                    'controller' => Controller\ParcourtController::class,
                    'action' => 'afficheruenonvalides'
                    ],
                ],
            ],
            'updateexamregistraion' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateexamregistraion',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'updateexamregistraion',
                    ],
                ],
            ],
            'examSearch' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/examSearch',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'examSearch',
                    ],
                ],
            ],  
            'noteDuplcation' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/noteDuplication',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'noteDuplication',
                    ],
                ],
            ],            
            'examtype' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/examtype[/:id]',
                    'defaults' => [
                        'controller' => Controller\ExamTypeController::class,
                       
                    ],
                ],
            ], 
            'exam' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/exam[/:id]',
                    'defaults' => [
                        'controller' => Controller\ExamController::class,
                       
                    ],
                ],
            ],
            'examregistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/examregistration[/:id]',
                    'defaults' => [
                        'controller' => Controller\ExamRegistrationController::class,
                       
                    ],
                ],
            ],
            'calculmp' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/calculmp[/:id]',
                    'defaults' => [
                        'controller' => Controller\CalculNotesController::class,
                       
                    ],
                ],
            ],
            'loadExamsPerModule' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/loadExamsPerModule[/:id]',
                    'defaults' => [
                        'controller' => Controller\CalculNotesController::class,
                       
                    ],
                ],
            ],            
            'gradeconfig' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/gradeconfig[/:id]',
                    'defaults' => [
                        'controller' => Controller\GradeController::class,
                       
                    ],
                ],
            ],
            'graderangeconfig' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/graderangeconfig[/:id]',
                    'defaults' => [
                        'controller' => Controller\GradeRangeController::class,
                       
                    ],
                ],
            ],
            'deliberation' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/deliberation[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'deliberation'
                       
                    ],
                ],
            ],
            'startDeliberation' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/startDeliberation[/:classeId][/:ueId][/:semId][/:subjectId]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'startDeliberation'
                       
                    ],
                ],
            ],  
            'applyDelibCondition' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/applyDelibCondition[/:classeId][/:ueId][/:semId]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'applyDelibCondition'
                       
                    ],
                ],
            ],            
            'delibConditions' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/delibConditions[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'delibConditions'
       
                       
                    ],
                ],
            ],            
            'delibConfig' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/delibConfig[/:id]',
                    'defaults' => [
                        'controller' => Controller\DelibConfigController::class,
       
                       
                    ],
                ],
            ],    
            'classesAssociatedToDelib' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/classesAssociatedToDelib[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'classesAssociatedToDelib'
       
                       
                    ],
                ],
            ], 
            'delibConditions' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/delibConditions[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'delibConditions'
       
                       
                    ],
                ],
            ],            
            'ueMarkTransactionLock' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/ueMarkTransactionLock[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'ueMarkTransactionLock'
                       
                    ],
                ],
            ],
                'ueMarkTransactionLock' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/ueMarkTransactionLock[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'ueMarkTransactionLock'
                       
                    ],
                ],
            ],        
            'semMarkTransactionLock' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/semMarkTransactionLock[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'semMarkTransactionLock'
                       
                    ],
                ],
            ],   
            'sendResultBySMS' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/sendResultBySMS[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'sendResultBySMS'
                       
                    ],
                ],
            ],            
            'printnotes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printnotes[/:exam_id]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printnotes'
                       
                    ],
                ],
            ],
            'printpvindiv' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printpvindiv[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printpvindiv'
                       
                    ],
                ],
            ],
            'printpvfailures' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printpvfailures[/:id][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printpvfailures'
                       
                    ],
                ],
            ], 
            'printSubjectMarkReport' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printSubjectMarkReport[/:ueID][/:subjectID][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printSubjectMarkReport'
                       
                    ],
                ],
            ], 
            'printModuleMarkReport' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printModuleMarkReport[/:ueID][/:classID][/:semID]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printModuleMarkReport'
                       
                    ],
                ],
            ],            
            'printmps' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printmps[/:classe_code][/:sem_id]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printmps'
                       
                    ],
                ],
            ],
            'printdetailedmps' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printdetailedmps[/:classe_code][/:sem_id][/:printinOption]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printdetailedmps'
                       
                    ],
                ],
            ],  
            'printFinalYrMps' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printFinalYrMps[/:classe_code][/:sem_id]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printFinalYrMps'
                    ],
                ],
            ],            
            'printstudentlist' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printstudentlist[/:exam_id]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printstudentlist'
                       
                    ],
                ],
            ],
            'printexametiquette' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printexametiquette[/:exam_id]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printexametiquette'
                       
                    ],
                ],
            ],
            'printTranscripts' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printTranscripts[/:classe_code][/:sem_id][/:acadYrId][/:duplicata][/:stdId]',
                    'defaults' => [
                        'controller' => Controller\ExamReportsController::class,
                        'action'     => 'printTranscripts'
                       
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
    'session_containers' => [
        'LoggedInUser'
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
            Controller\ParcourtController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => '*', 
                 'allow' => '@']
            ],
        ]
    ],


];
