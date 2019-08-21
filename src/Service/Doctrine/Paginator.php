<?php

namespace App\Service\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Request;

class Paginator
{
    /**
     * @param QueryBuilder $qb
     * @param Request $request
     * @return array
     */
    public static function packResponse(QueryBuilder $qb, Request $request)
    {
        $page = (int) $request->get('page', 1);
        $itemsPerPage = (int) $request->get('itemsPerPage', 5);

        $firstResult = ($page - 1) * $itemsPerPage;

        $qb->setFirstResult($firstResult)
            ->setMaxResults($itemsPerPage);

        $paginator = new DoctrinePaginator($qb->getQuery(),true);

        $itemsCount = count($paginator);
        $pagesCount = (int) ceil($itemsCount / $itemsPerPage);

        return [
            'paginator' => [
                'page' => $page,
                'pagesCount' => $pagesCount,
                'itemsPerPage' => $itemsPerPage,
                'itemsCount' => $itemsCount,
            ],
            'items' => $qb->getQuery()->getResult(),
        ];
    }
}
