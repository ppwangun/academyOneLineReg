<?php
namespace Application\Entity;

use Doctrine\ORM\EntityRepository;
use Application\Entity\ExamSession;
use Application\Entity\Semester;

class MyRepository extends EntityRepository
{
    public function findBySemester(ExamSession $cexamSessions)
    {
        $qb = $this->createQueryBuilder('Semester s') // 'u' is an alias for the User entity
            ->innerJoin('s.examSessions', 's') // 'categories' is the field name in the User entity
            ->andWhere(':category MEMBER OF u.categories')
           // ->setParameter('category', $category)
            ->getQuery()
            ->getResult();

        return $qb;
    }
}

