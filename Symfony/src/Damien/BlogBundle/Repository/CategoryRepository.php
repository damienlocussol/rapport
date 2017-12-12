<?php

namespace Damien\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CategoryRepository extends EntityRepository
{
    
    public function myFindAll()
    {
        return $this
            ->createQueryBuilder('a')
            ->getQuery()
            ->getResult()
        ;
        
    }   
    
    
    
}
