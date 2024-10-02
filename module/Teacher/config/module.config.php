<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Teacher;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'searchAllSubjects' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchAllSubjects',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchAllSubjects',
                    ],
                ],
            ],
            'teacherList' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teacherList',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teacherList',
                    ],
                ],
            ],  
            'searchTeacher' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchTeacher',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchTeacher',
                    ],
                ],
            ],  
            'generateBill' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/generateBill',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'generateBill',
                    ],
                ],
            ],  
            'searchBill' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchBill',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchBill',
                    ],
                ],
            ],   
            'billDetails' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/billDetails[/:numRef]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'billDetails',
                    ],
                ],
            ], 
            'printBill' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printBill[/:numRef]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'printBill',
                    ],
                ],
            ],   
            'printWorkloadFollowUp' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printWorkloadFollowUp[/:numRef]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'printWorkloadFollowUp',
                    ],
                ],
            ],             
            'teacherUnitFollowUp' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teacherUnitFollowUp',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teacherUnitFollowUp',
                    ],
                ],
            ], 
            'unitFollowUp' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/unitFollowUp',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'unitFollowUp',
                    ],
                ],
            ],  
            'teacherAssignedSubjectsTpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teacherAssignedSubjectsTpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teacherAssignedSubjectsTpl',
                    ],
                ],
            ], 
            'subjectBillingTpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/subjectBillingTpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'subjectBillingTpl',
                    ],
                ],
            ],            
            'teacherAssignedSubjects' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teacherAssignedSubjects',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teacherAssignedSubjects',
                    ],
                ],
            ],             
            'acadranktpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/acadranktpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'acadranktpl',
                    ],
                ],
            ], 
            'newAcadRank' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newAcadRank',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newAcadRank',
                    ],
                ],
            ],            
            'teacherFollowUp' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teacherFollowUp',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teacherFollowUp',
                    ],
                ],
            ],  
            'assignSubjectToTeacher' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/assignSubjectToTeacher[/:teacherID][/:subjectIDs]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'assignSubjectToTeacher',
                    ],
                ],
            ],  
            'unAssignSubjectToTeacher' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/unAssignSubjectToTeacher[/:teacherID][/:subjectIDs]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'unAssignSubjectToTeacher',
                    ],
                ],
            ],            

            'new-teacher-form-assets' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/new-teacher-form-assets',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newTeacherFormAssets',
                    ],
                ],
            ],
            'newteachertpl' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/newteachertpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newteachertpl',
                    ],
                ],
            ], 
            'cities' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cities[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'cities',
                       
                    ],
                ],
            ],
            'teacherGrade' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/teacherGrade[/:id]',
                    'defaults' => [
                        'controller' => Controller\GradeController::class,
                    ],
                ],
            ],  
            'teachers' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/teachers[/:id]',
                    'defaults' => [
                        'controller' => Controller\TeacherController::class,
                    ],
                ],
            ], 
            'unitProgression' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/unitProgression[/:id]',
                    'defaults' => [
                        'controller' => Controller\ProgressionController::class,
                    ],
                ],
            ],  
            'programmingtpl' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/programmingtpl[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'programmingtpl'
                    ],
                ],
            ],  
            'schedulingCourse' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/schedulingCourse[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'schedulingCourse'
                    ],
                ],
            ],  
            'getSchedulingCourses' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getSchedulingCourses[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'getSchedulingCourses'
                    ],
                ],
            ],   
            'getScheduledCourse' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getScheduledCourse[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'getScheduledCourse'
                    ],
                ],
            ],            
             /*  'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/home',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'home',
                    ],
                ],
            ],*/

        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\GradeController::class => Controller\Factory\GradeControllerFactory::class,
            Controller\TeacherController::class => Controller\Factory\TeacherControllerFactory::class,
            Controller\ProgressionController::class => Controller\Factory\ProgressionControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
           // 'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
           // 'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
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
            Controller\AuthController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['login'], 
                 'allow' => '*']

            ],
            Controller\RoleController::class => [
                // Allow access to authenticated users having the "role.manage" permission.
                ['actions' => '*', 'allow' => '+role.manage']
            ],
            Controller\PermissionController::class => [
                // Allow access to authenticated users having "permission.manage" permission.
                ['actions' => '*', 'allow' => '+permission.manage']
            ],
        ]
    ],
];
