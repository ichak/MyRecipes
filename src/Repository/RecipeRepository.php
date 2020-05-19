<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;


class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function findOneBySomeField($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.name = :val')
            ->setParameter(':val', $value)
            ->getQuery()
        ;
    }

    // /**
    //  * @return Recipe[] Returns an array of Recipe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        $firstResult = ($page - 1) * $countPerPage;
        $query = $this->createQueryBuilder('r')
            ->addSelect('img, ri, m, u, s')
            ->leftJoin('r.image', 'img')
            ->leftJoin('r.recipeIngredients', 'ri')
            ->leftJoin('r.meals', 'm')
            ->leftJoin('r.step', 's')
            ->leftJoin('r.user', 'u')
            ->where('r.content LIKE :search')
            ->setParameter(':search', '%'.trim($search).'%')
            ->orderBy('s.dateCreate', 'desc')
            ->setFirstResult($firstResult)
            ->setMaxResults($countPerPage)
            ->getQuery()
        ;

        return new Paginator($query);
    }

    
    public function findAll(int $page = 1, int $countPerPage = 30): Paginator
    {
        // Calcul du premier élément à afficher 
        $firstResult = ($page - 1) * $countPerPage;

        $query = $this->createQueryBuilder('r')
            ->addSelect('img, ri, m, u, s')
            ->leftJoin('r.image', 'img')
            ->leftJoin('r.recipeIngredients', 'ri')
            ->leftJoin('r.meals', 'm')
            ->leftJoin('r.step', 's')
            ->leftJoin('r.user', 'u')
            ->orderBy('r.dateCreate', 'desc')
            ->setFirstResult($firstResult)
            ->setMaxResults($countPerPage)
            ->getQuery()
        ;

        return new Paginator($query);
    }
    
}
