<?php
namespace Teacher\Service;

use Application\Entity\AcademicYear;
use Application\Entity\Admission;
use Application\Entity\AdminRegistration;
use Application\Entity\TeachingUnit;
use Application\Entity\Subject;
use Application\Entity\ClassOfStudy;
use Application\Entity\Student;
use Application\Entity\Semester;
use Application\Entity\RegisteredStudentView;
use Application\Entity\CurrentYearUeExamsView;
use Application\Entity\CurrentYearSubjectExamsView;
use Application\Entity\CurrentYearTeachingUnitView;
use Application\Entity\ClassOfStudyHasSemester;
use Application\Entity\UnitRegistration;
use Application\Entity\StudentSemRegistration;
use Application\Entity\UserManagesClassOfStudy;
use Application\Entity\Degree;
use Application\Entity\Faculty;
use Application\Entity\SubjectRegistrationView;
use Application\Entity\GradeValueRange;
use Application\Entity\TeacherPaymentRate;
use Application\Entity\PaymentTeachingAssignmentMethod;

use Patrickmaken\Web2Sms\Client as W2SClient;
use Patrickmaken\AvlyText\Client as AVTClient;

use Laminas\Http\Request;
use Laminas\Http\Client;

use Laminas\Hydrator\ReflectionHydrator;


use Laminas\Http\Header\Date;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */




class TeacherManager {
    
    private $entityManager;



    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
       
    }
   public function getCurrentYearCode()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr.getCode(); 
       
   }
   
   public function getCurrentYearID()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class).findOneByID(1);
       return $acadyr->getId();
       
   }
   public function getCurrentYear()
   {
       $acadyr = $this->entityManager->getRepository(AcademicYear::class)->findOneByIsDefault(1);
       return $acadyr;
       
   } 
   
   public function setVacationPaymentMethod($subject,$paymentgridId,$paymentMethod,$amount)
   {

            //very if a payment method is assigned to the subject
            if(!$subject->getPaymentTeachingAssignment()) 
            {   //crate a new payment method
                
                $pymtMethod = new PaymentTeachingAssignmentMethod(); 
                //verifuy if the payment metgod is based on academic ranking
                if($paymentMethod==0)
                {
                    $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($paymentgridId);
                    $pymtMethod->setPaymentMethod("ACADEMIC_RANK");
                    $pymtMethod->setTeacherPaymentRate($pymtRate);
                    $pymtMethod->setAmount(NULL);
                }
                elseif($paymentMethod==1)
                {
                    $pymtMethod->setPaymentMethod("FORFAIT");
                    $pymtMethod->setAmount($amount);
                }
                $this->entityManager->persist($pymtMethod);
                $subject->setPaymentTeachingAssignment($pymtMethod);

            }else //update an already existing payment method
            {
                $pymtMethod = $subject->getPaymentTeachingAssignment(); 
                if($paymentMethod==0)
                {;
                    $pymtRate = $this->entityManager->getRepository(TeacherPaymentRate::class)->find($paymentgridId); 
                    $pymtMethod->setPaymentMethod("ACADEMIC_RANK"); 
                    $pymtMethod->setTeacherPaymentRate($pymtRate);
                    $pymtMethod->setAmount(NULL);
                }
                elseif($paymentMethod==1)
                {
                    $pymtMethod->setPaymentMethod("FORFAIT");
                    $pymtMethod->setAmount($amount);
                }                                    

            }
    
   }

  
   
   }
                   