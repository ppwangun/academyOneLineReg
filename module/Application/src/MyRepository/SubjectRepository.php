<?php
namespace Application\MyRepository;

use Doctrine\ORM\EntityRepository;

use Application\Entity\Semester;
use Application\Entity\ExamSession;




class SubjectRepository extends EntityRepository {
    


    public function getRegistrationsByModule($data)
    {
        
            $query= $this->createQueryBuilder('ur')
           
            ->select('urpet','et','urps','ur','std','ue','sub','unit')
            ->join('ur.teachingUnit','ue')
            ->join('ur.unitReportPerSession','urps')
            ->join('urps.unitReportPerExamType','urpet')
            ->leftjoin('urpet.subject','sub')
            ->leftjoin('urpet.teachingUnit','unit')        
            ->leftJoin('urpet.examType','et')
            ->leftjoin('ur.student','std')
            ->leftjoin('ur.semester','sem')
            ->where('ur.teachingUnit = :ue')
            ->andwhere('ur.semester = :semester')
            ->andwhere('urps.examSession = :session')
            ->setParameter('ue',$data['ue_id'])
            ->setParameter('session',$data['session_id'])
            ->setParameter('semester',$data['sem_id'])
            ->getQuery();
            return $query->getArrayResult();        
    }
    
    public function getRegistrationsBySubject($data)
    {
        
            $query= $this->createQueryBuilder('ur')
           
            ->select('urpet','et','urps','ur','std','ue','sub','unit')
            ->join('ur.teachingUnit','ue')
           // ->join('ue.subject','sub')
            ->join('ur.unitReportPerSession','urps')
            ->join('urps.unitReportPerExamType','urpet')
            ->leftjoin('urpet.subject','sub')
            ->leftjoin('urpet.teachingUnit','unit')
            ->leftJoin('urpet.examType','et')
            ->leftjoin('ur.student','std')
            ->leftjoin('ur.semester','sem')
            ->where('ur.teachingUnit = :ue')
            ->andwhere('ur.semester = :semester')
            ->andwhere('urps.examSession = :session')
            ->andwhere('sub = :subject')        
            ->setParameter('ue',$data['ue_id'])
            ->setParameter('session',$data['session_id'])
            ->setParameter('semester',$data['sem_id'])
            ->setParameter('subject',$data['subject_id'])
            ->getQuery();
            return $query->getArrayResult();        
    } 
    
}
                   