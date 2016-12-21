<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
    public function findDuplicates(\AppBundle\Entity\Tag $tag)
    {
        $em = $this->getEntityManager();

        $query = $em
            ->createQueryBuilder()
            ->select('t')
            ->from('\AppBundle\Entity\Tag', 't')
            ->where('t.title = :title')
            ->andWhere('t.id != :id')
            ->setParameter('title', $tag->getTitle())
            ->setParameter('id', $tag->getId())
            ->getQuery()
        ;
        return $query->getResult();
    }
}
