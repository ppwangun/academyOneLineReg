<?php
namespace Application\MyRepository;

use Doctrine\ORM\EntityRepository;

use Application\Entity\Semester;
use Application\Entity\ExamSession;




class ExamSessionRepository extends EntityRepository {
    


    public function findSession($id)
    {
        
        $query = $this->createQueryBuilder('e')
                ->join('e.semesters', 's')
                ->addSelect('s')
            ->where('s.id = :semester')
            //->andWhere('u.isActive = :active')
            //->setParameter('role', $role)
            ->setParameter('semester', $id)
            ->getQuery();
        return $query->getResult();        
    }
    
    public function getSessionsBySemester(Semester $semester)
    { 
        $query = $this->createQueryBuilder('e')
                ->join('e.semesters', 's')
               // ->addSelect('s')
            ->where('s.id = :semester')
            //->andWhere('u.isActive = :active')
            //->setParameter('role', $role)
            ->setParameter('semester', $semester->getId())
            ->getQuery();
            //->getArrayResult(); 
        return $query->getResult();
    } 
    
}
                   