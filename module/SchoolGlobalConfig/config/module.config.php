<?php
namespace SchoolGlobalConfig;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\SettingsController::class => Controller\Factory\SettingsControllerFactory::class,
            Controller\AcadYearController::class => Controller\Factory\AcadYearControllerFactory::class,
            Controller\SemesterController::class => Controller\Factory\SemesterControllerFactory::class,
            Controller\FacultyController::class => Controller\Factory\FacultyControllerFactory::class,
            Controller\SchoolController::class => Controller\Factory\SchoolControllerFactory::class,
            Controller\DepartmentController::class => Controller\Factory\DepartmentControllerFactory::class,
            Controller\SpecialiteController::class => Controller\Factory\SpecialiteControllerFactory::class,
            Controller\FiliereController::class => Controller\Factory\FiliereControllerFactory::class,
            Controller\CycleFormationController::class => Controller\Factory\CycleFormationControllerFactory::class,
            Controller\DegreeController::class => Controller\Factory\DegreeControllerFactory::class,
            Controller\CycleController::class => Controller\Factory\CycleControllerFactory::class,
            Controller\ClassesController::class => Controller\Factory\ClassesControllerFactory::class,
            Controller\TeachingunitController::class => Controller\Factory\TeachingunitControllerFactory::class,
            Controller\AssignedTeachingunitController::class => Controller\Factory\AssignedTeachingunitControllerFactory::class,
            Controller\SubjectController::class => Controller\Factory\SubjectControllerFactory::class,
            Controller\AssignSemesterToClassController::class => Controller\Factory\AssignSemesterToClassControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'schoolglobalconfig' => [
                'type'    => Literal::class,
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/anneeacad',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'anneedetails',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                ],
            ],
            'newacadyr' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newacadyr',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'newacadyr',
                    ],
                ],
            ],
            'dashboard' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/dashboard',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'dashboard',
                    ],
                ],
            ], 

            'acadyear' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/acadyear[/:id]',
                    'defaults' => [
                        'controller' => Controller\AcadYearController::class,
                    ],
                ],
            ],
            'searchAcademicYear' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchAcademicYear[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => "searchAcademicYear"
                    ],
                ],
            ],  
            'currentAcademicYear' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/currentAcademicYear[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => "currentAcademicYear"
                    ],
                ],
            ],             
            'academicYrDataMigration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/academicYrDataMigration[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => "academicYrDataMigration"
                    ],
                ],
            ],            
            'semester' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/semester[/:id]',
                    'defaults' => [
                        'controller' => Controller\SemesterController::class,
                    ],
                ],
            ],
            'facultytpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/facultytpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'facultytpl',
                    ],
                ],
            ],
            'departmentpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/departmentpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'departmentpl',
                    ],
                ],
            ],   
            'newDept' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newDept',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newDept',
                    ],
                ],
            ], 
            'updateDept' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateDpt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'updateDpt',
                    ],
                ],
            ],            
            'filieretpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/filieretpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'filieretpl',
                    ],
                ],
            ], 
            'newFil' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newFil',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newFil',
                    ],
                ],
            ], 
            'updateFil' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateFil',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'updateFil',
                    ],
                ],
            ],   
            'specialitetpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/specialitetpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'specialitetpl',
                    ],
                ],
            ], 
            'newSpe' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newSpe',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newSpe',
                    ],
                ],
            ], 
            'updateSpe' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateSpe',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'updateSpe',
                    ],
                ],
            ],  
            'cycletpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/cycletpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'cycletpl',
                    ],
                ],
            ],  
            'newCycle' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newCycle',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newCycle',
                    ],
                ],
            ],   
            'updateCycle' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateCycle',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'updateCycle',
                    ],
                ],
            ],            
            'degreetpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/degreetpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'degreetpl',
                    ],
                ],
            ],
            'newdegreetpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newdegreetpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newdegreetpl',
                    ],
                ],
            ],
            'classetpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/classetpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'classetpl',
                    ],
                ],
            ],
            'newclassetpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newclassetpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newclassetpl',
                    ],
                ],
            ],
            'teachingunitpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/teachingunitpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'teachingunitpl',
                    ],
                ],
            ],
            'assignedteachingunitpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/assignedteachingunitpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'assignedteachingunitpl',
                    ],
                ],
            ],
            'newteachingunitpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/newteachingunitpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'newteachingunitpl',
                    ],
                ],
            ],
            'assignnewteachingunitpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/assignnewteachingunitpl',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'assignnewteachingunitpl',
                    ],
                ],
            ],
            'filfrmdegree' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/filfrmdegree',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'filfrmdegree',
                    ],
                ],
            ],
            'cyclebydegree' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/cyclebydegree',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'cyclebydegree',
                    ],
                ],
            ],
            'subjectbyue' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/subjectbyue',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'subjectbyue',
                    ],
                ],
            ],
            'semesterbyacademicyear' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/semesterbyacademicyear',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'semesterbyacademicyear',
                    ],
                ],
            ],
            'findsemester' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/findsemester',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'findsemester',
                    ],
                ],
            ],
            'searchDptByFaculty' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchDptByFaculty',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchDptByFaculty',
                    ],
                ],
            ],    
            'searchFilByDpt' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchFilByDpt',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchFilByDpt',
                    ],
                ],
            ],  
            'searchFilByFaculty' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchFilByFaculty',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchFilByFaculty',
                    ],
                ],
            ],
            'searchDegreeBySpeciality' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchDegreeBySpeciality',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchDegreeBySpeciality',
                    ],
                ],
            ],            
            'importSubject' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importSubject',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'importSubject',
                    ],
                ],
            ],             
            'searchSpeByFil' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchSpeByFil',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchSpeByFil',
                    ],
                ],
            ],  
            'searchCommonCoreTraining' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/searchCommonCoreTraining',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'searchCommonCoreTraining',
                    ],
                ],
            ],             
            'school' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/school[/:id]',
                    'defaults' => [
                        'controller' => Controller\SchoolController::class,
                        
                    ],
                ],
            ],
            'faculty' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/faculty[/:id]',
                    'defaults' => [
                        'controller' => Controller\FacultyController::class,
                    ],
                ],
            ],
            'department' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/department[/:id]',
                    'defaults' => [
                        'controller' => Controller\DepartmentController::class,
                    ],
                ],
            ],            
            'filiere' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/filiere[/:id]',
                    'defaults' => [
                        'controller' => Controller\FiliereController::class,
                    ],
                ],
            ],
            'cycleFormation' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cycleFormation[/:id]',
                    'defaults' => [
                        'controller' => Controller\CycleFormationController::class,
                    ],
                ],
            ],            
            'specialite' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/specialite[/:id]',
                    'defaults' => [
                        'controller' => Controller\SpecialiteController::class,
                    ],
                ],
            ],            
            'degree' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/degree[/:id]',
                    'defaults' => [
                        'controller' => Controller\DegreeController::class,
                    ],
                ],
            ],
            'cycle' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/cycle[/:id]',
                    'defaults' => [
                        'controller' => Controller\CycleController::class,
                    ],
                ],
            ],
            'classes' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/classes[/:id]',
                    'defaults' => [
                        'controller' => Controller\ClassesController::class,
                    ],
                ],
            ],
            'teachingunit' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/teachingunit[/:id]',
                    'defaults' => [
                        'controller' => Controller\TeachingunitController::class,
                    ],
                ],
            ],
            'assignedteachingunit' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/assignedteachingunit[/:id]',
                    'defaults' => [
                        'controller' => Controller\AssignedTeachingunitController::class,
                    ],
                ],
            ],
            'assignnewteachingunit' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/assignnewteachingunit[/:id]',
                    'defaults' => [
                        'controller' => Controller\AssignedTeachingunitController::class,
                    ],
                ],
            ],
            'subject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/subject[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubjectController::class,
                    ],
                ],
            ],
            'assignsemtoclass' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/assignsemtoclass[/:id]',
                    'defaults' => [
                        'controller' => Controller\AssignSemesterToClassController::class,
                    ],
                ],
            ],
            'changeOnlineRegistrationDefault' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/changeOnlineRegistrationDefault',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'changeOnlineRegistrationDefault',
                    ],
                ],
            ], 
            'onlineRegistrationDefault' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/onlineRegistrationDefault',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'        => 'onlineRegistrationDefault',
                    ],
                ],
            ], 
            'settingstpl' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/settingstpl',
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action'        => 'settings',
                    ],
                ],
            ], 
            'updateOdooSettings' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/updateOdooSettings',
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action'        => 'updateOdooSettings',
                    ],
                ],
            ],  
            'getOdooSettings' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/getOdooSettings',
                    'defaults' => [
                        'controller' => Controller\SettingsController::class,
                        'action'        => 'getOdooSettings',
                    ],
                ],
            ],             
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'school-global-config/index/anneedetails' => __DIR__.'/../view/schoolglobalconfig/Index/anneedetails.phtml',
            'school-global-config/index/newacadyr' => __DIR__.'/../view/schoolglobalconfig/Index/newacadyr.phtml',
            'school-global-config/index/assignedteachingunitpl' => __DIR__.'/../view/schoolglobalconfig/Index/assignedteachingunitpl.phtml',
            'school-global-config/index/assignnewteachingunitpl' => __DIR__ .'/../view/schoolglobalconfig/Index/assignnewteachingunitpl.phtml',
            'school-global-config/index/facultytpl' => __DIR__.'/../view/schoolglobalconfig/Index/facultytpl.phtml',
            'school-global-config/index/departmentpl' => __DIR__ .'/../view/schoolglobalconfig/Index/departmentpl.phtml',
            'school-global-config/index/new-dept' => __DIR__.'/../view/schoolglobalconfig/Index/newdept.phtml',
            'school-global-config/index/update-dpt' => __DIR__.'/../view/schoolglobalconfig/Index/updatedpt.phtml',
            'school-global-config/index/filieretpl' => __DIR__.'/../view/schoolglobalconfig/Index/filieretpl.phtml',
            'school-global-config/index/new-fil' => __DIR__.'/../view/schoolglobalconfig/Index/newfil.phtml', 
            'school-global-config/index/update-fil' => __DIR__.'/../view/schoolglobalconfig/Index/updatefil.phtml',
            'school-global-config/index/specialitetpl' => __DIR__.'/../view/schoolglobalconfig/Index/specialitetpl.phtml',
            'school-global-config/index/new-spe' => __DIR__.'/../view/schoolglobalconfig/Index/newspe.phtml', 
            'school-global-config/index/update-spe' => __DIR__.'/../view/schoolglobalconfig/Index/updatespe.phtml',
            'school-global-config/index/cycletpl' => __DIR__.'/../view/schoolglobalconfig/Index/cycletpl.phtml',
            'school-global-config/index/new-cycle' => __DIR__.'/../view/schoolglobalconfig/Index/newcycle.phtml',
            'school-global-config/index/update-cycle' => __DIR__.'/../view/schoolglobalconfig/Index/updatecycle.phtml',
            'school-global-config/index/degreetpl' => __DIR__.'/../view/schoolglobalconfig/Index/degreetpl.phtml', 
            'school-global-config/index/newdegreetpl' => __DIR__.'/../view/schoolglobalconfig/Index/newdegreetpl.phtml',
            'school-global-config/index/classetpl' => __DIR__.'/../view/schoolglobalconfig/Index/classetpl.phtml',
            'school-global-config/index/newclassetpl' => __DIR__.'/../view/schoolglobalconfig/Index/newclassetpl.phtml',
            'school-global-config/index/teachingunitpl' => __DIR__.'/../view/schoolglobalconfig/Index/teachingunitpl.phtml',
            'school-global-config/index/newteachingunitpl' => __DIR__.'/../view/schoolglobalconfig/Index/newteachingunitpl.phtml',
            'school-global-config/index/dashboard' => __DIR__ .'/../view/schoolglobalconfig/Index/dashboard.phtml',
            'school-global-config/settings/settings' => __DIR__ .'/../view/schoolglobalconfig/Settings/settings.phtml',
        ],
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
            Controller\SettingsController::class => [

                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*', 
                 'allow' => '@'],

            ],            
        ]
    ],
];
