<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class BlogPostRepository extends EntityRepository
{
    public function createFindAllQuery()
    {
        return $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            "
        );
    }

    public function createFindOneByIdQuery(int $id)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.id = :id
            "
        );

        $query->setParameter('id', $id);

        return $query;
    }

    public function createFindAllByIdSortByDateQuery()
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            ORDER BY bp.date DESC 
            "
        );

        return $query;
    }

    public function createFindAllByIdSortByTitleQuery()
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            ORDER BY bp.title DESC 
            "
        );

        return $query;
    }

    public function createFindAllByIdSortByDateAndTitleQuery()
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            ORDER BY bp.date,bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateAndTitleSortedByDateAndTitleQuery(string $filterByDate, string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
              AND bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.date,bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateAndTitleSortedByDateQuery(string $filterByDate, string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
              AND bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.date DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateAndTitleSortedByTitleQuery(string $filterByDate, string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
              AND bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateAndTitleQuery(string $filterByDate, string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
              AND bp.title LIKE '%{$filterByTitle}%'
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateSortedByDateAndTitleQuery(string $filterByDate)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
            ORDER BY bp.date,bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateSortedByDateQuery(string $filterByDate)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
            ORDER BY bp.date DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateSortedByTitleQuery(string $filterByDate)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
            ORDER BY bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByDateQuery(string $filterByDate)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.date LIKE '%{$filterByDate}%'
            "
        );

        return $query;
    }

    public function createFindAllFilteredByTitleSortedByDateAndTitleQuery(string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.date,bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByTitleSortedByDateQuery(string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.date DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByTitleSortedByTitleQuery(string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.title LIKE '%{$filterByTitle}%'
            ORDER BY bp.title DESC
            "
        );

        return $query;
    }

    public function createFindAllFilteredByTitleQuery(string $filterByTitle)
    {
        $query = $this->_em->createQuery(
            "
            SELECT bp
            FROM AppBundle:BlogPost bp
            WHERE bp.title LIKE '%{$filterByTitle}%'
            "
        );

        return $query;
    }
}