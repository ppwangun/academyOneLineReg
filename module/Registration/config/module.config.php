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
            Controller\RegistrationReportsController::class => Controller\Factory\RegistrationReportsControllerFactory::class,
            Controller\StdFromPvController::class => Controller\Factory\StdFromPvControllerFactory::class,
            Controller\StdRegistrationController::class => Controller\Factory\StdRegistrationControllerFactory::class,
            Controller\SubjectRegistrationController::class => Controller\Factory\SubjectRegistrationControllerFactory::class,
            Controller\SubjectController::class => Controller\Factory\SubjectControllerFactory::class,
            Controller\ProspectsController::class => Controller\Factory\ProspectsControllerFactory::class,
            Controller\StdRegisteredToSubjectController::class => Controller\Factory\StdRegisteredToSubjectControllerFactory::class,
            Controller\StdRegisteredToExamController::class => Controller\Factory\StdRegisteredToExamControllerFactory::class,
            Controller\StdAdmissionController::class => Controller\Factory\StdAdmissionControllerFactory::class,
            Controller\CountriesController::class => Controller\Factory\CountriesControllerFactory::class,
            Controller\StdRegistrationController::class => Controller\Factory\StdRegistrationControllerFactory::class,
        ],
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
            'students' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/students',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'students',
                    ],
                ],
            ],
            'searchStudent' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/searchStudent[/:matricule][/:acadYrId][/:classeId]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'searchStudent',
                    ],
                ],
            ],
             'prospects' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/prospects',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'prospects',
                       
                    ],
                ],
            ],  
             'getProspects' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getProspects[/:id]',
                    'defaults' => [
                        'controller' => Controller\ProspectsController::class,
                      
                    ],
                ],
            ],  
             'prospect' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/prospect[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'prospect',
                      
                    ],
                ],
            ],  
             'getProspectCursus' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getProspectCursus[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'getProspectCursus',
                      
                    ],
                ],
            ], 
             'showpaymentproof' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/showpaymentproof[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'showpaymentproof',
                      
                    ],
                ],
            ],            
             'admission' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/admission',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'admission',
                       
                    ],
                ],
            ],
            'stdAdmitted' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdAdmitted[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdAdmissionController::class,
                       
                    ],
                ],
            ], 
            'stdList' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdList[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'stdList'
                       
                    ],
                ],
            ],   
            'registrationStat' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/registrationStat[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'=>'registrationStat'
                       
                    ],
                ],
            ],            
             'importAdmittedStudent' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importAdmittedStudent',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'importAdmittedStudent',
                       
                    ],
                ],
            ],            
            'studentinfostpl' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/studentinfostpl[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'studentinfostpl',
                    ],
                ],
            ],
            'pedagogicalregtpl' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/pedagogicalregtpl[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'pedagogicalregtpl',
                    ],
                ],
            ],            
            'printStdList' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printStdList[/:classeCode][/:ueId]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'printStdList',
                    ],
                ],
            ], 
            'printRegistrationFile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printRegistrationFile[/:id][/:classe]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'printRegistrationFile',
                    ],
                ],
            ],
            'printStudentCard' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printStudentCard[/:id][/:classe]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'printStudentCard',
                    ],
                ],
            ],            
            'subjectregistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/subjectregistration[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubjectRegistrationController::class,
                        
                    ],
                ],
            ],
            'stdregistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregistration[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegistrationController::class,
                       
                    ],
                ],
            ],
            'stdFromPv' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdFromPv[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdFromPvController::class,
                       
                    ],
                ],
            ],            
             'stdregisteredbyclasse' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregisteredbyclasse[/:classe]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'stdregisteredbyclasse',
                       
                    ],
                ],
            ],            
            'furthersubjectregistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/furthersubjectregistration[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubjectRegistrationController::class,
                                               
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
            'stdregisteredtosubject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregisteredtosubject[/:id/:sem_id]',
                    'defaults' => [
                        'controller' => Controller\StdRegisteredToSubjectController::class,
                       
                    ],
                ],
            ], 
            'stdregisteredtoexam' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/stdregisteredtoexam[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegisteredToExamController::class,
                       
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
             'clearUnitRegistrationDuplicates' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/clearUnitRegistrationDuplicates',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'clearUnitRegistrationDuplicates',
                       
                    ],
                ],
            ],
            'studentsByClasse' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/studentsByClasse[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'studentsByClasse',
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
            'studentInfoDetails' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/studentInfoDetails[/:id]',
                    'defaults' => [
                        'controller' => Controller\StdRegistrationController::class,
                       // 'action'     => 'students',
                    ],
                ],
            ],
            'createOnlineRegistrationUserSession' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/createOnlineRegistrationUserSession[/:id]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'createOnlineRegistrationUserSession',
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
             'picturesGenerator' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/picturesGenerator',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'picturesGenerator',
                       
                    ],
                ],
            ],  
             'studentsDataGenerator' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/studentsDataGenerator',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'studentsDataGenerator',
                       
                    ],
                ],
            ], 
             'resetPedagogicRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/resetPedagogicRegistration[/:matricule]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'resetPedagogicRegistration',
                       
                    ],
                ],
            ], 
             'resetAdministrativeRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/resetAdministrativeRegistration[/:matricule]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'resetAdministrativeRegistration',
                       
                    ],
                ],
            ], 
             'suspendRegistration' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/suspendRegistration[/:matricule]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'suspendRegistration',
                       
                    ],
                ],
            ], 
             'leaveTraining' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/leaveTraining[/:matricule]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'leaveTraining',
                       
                    ],
                ],
            ],                         
             'importStudentPv' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importStudentPv',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'importStudentPv',
                       
                    ],
                ],
            ], 
             'importStudentMpc' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importStudentMpc',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'importStudentMpc',
                       
                    ],
                ],
            ],             
             'importStudentFinance' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/importStudentFinance',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'importStudentFinance',
                       
                    ],
                ],
            ],  
             'transcripts' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/transcripts',
                    'defaults' => [
                        'controller' => Controller\RegistrationReportsController::class,
                        'action'     => 'transcripts',
                       
                    ],
                ],
            ],  
             'scholarshipCertificates' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/scholarshipCertificates',
                    'defaults' => [
                        'controller' => Controller\RegistrationReportsController::class,
                        'action'     => 'scholarshipCertificates',
                       
                    ],
                ],
            ], 
             'generateTranscriptsReferences' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/generateTranscriptsReferences',
                    'defaults' => [
                        'controller' => Controller\RegistrationReportsController::class,
                        'action'     => 'generateTranscriptsReferences',
                       
                    ],
                ],
            ],            
             'getTranscriptReferenceGenerationStatus' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/getTranscriptReferenceGenerationStatus',
                    'defaults' => [
                        'controller' => Controller\RegistrationReportsController::class,
                        'action'     => 'getTranscriptReferenceGenerationStatus',
                       
                    ],
                ],
            ],            
             'printScholarshipCertificates' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/printScholarshipCertificates[/:classe_code][/:stdId]',
                    'defaults' => [
                        'controller' => Controller\RegistrationReportsController::class,
                        'action'     => 'printScholarshipCertificates',
                       
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
            Controller\RegistrationReportsController::class => [

                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*', 
                 'allow' => '@'],

            ],            
            Controller\CountriesController::class => [
                // Give access to "index", "add", "edit", "view", "changePassword" actions 
                // to users having the "user.manage" permission.
                ['actions' => '*','allow' => '@'],
            ],            
        ]
    ],
];
