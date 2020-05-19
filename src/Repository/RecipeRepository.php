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

    /**
     * Recherche des recettes
     */
    public function findBySearch(string $search, int $page = 1, int $countPerPage = 30): Paginator
    {
        $firstResult = ($page - 1) * $countPerPage;
        $query = $this->createQueryBuilder('r')
            ->addSelect('img, i, c, u, s')
            ->leftJoin('r.image', 'img')
            ->leftJoin('r.ingredients', 'i')
            ->leftJoin('r.meals', 'm')
            ->leftJoin('r.steps', 's')
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
            ->addSelect('img, i, c, u, s')
            ->leftJoin('r.image', 'img')
            ->leftJoin('r.ingredients', 'i')
            ->leftJoin('r.meals', 'm')
            ->leftJoin('r.steps', 's')
            ->leftJoin('r.user', 'u')
            ->orderBy('r.dateCreate', 'desc')
            ->setFirstResult($firstResult)
            ->setMaxResults($countPerPage)
            ->getQuery()
        ;

        return new Paginator($query);
    }
    
}
