<?php

namespace Webkul\UVDesk\AutomationBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\Criteria;

/**
 * PreparedResponsesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PreparedResponsesRepository extends EntityRepository
{
    const LIMIT = 10;

	public $safeFields = ['page','limit','sort','order','direction'];

    public function getPreparesResponses(\Symfony\Component\HttpFoundation\ParameterBag $obj = null, $container)
    {
        $userService = $container->get('user.service');

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('DISTINCT pr.id, pr.name, pr.status, u.id as agentId')
            ->from($this->getEntityName(), 'pr')
            ->leftJoin('pr.user', 'ud')
            ->leftJoin('ud.user', 'u');

        $data = $obj->all();
        $data = array_reverse($data);

        foreach ($data as $key => $value) {
            switch ($key) {
                case 'name':
                case 'description':
                case 'type':
                case 'status':
                    $qb
                        ->andWhere("pr.$key = :$key")
                        ->setParameter($key, $value);
                    break;
                case 'search':
                    $qb
                        ->andWhere('pr.name LIKE :name')
                        ->setParameter('name', '%' . urldecode(trim($value)) . '%');
                    break;
                default:
                    break;
            }
        }

        if (! isset($data['sort'])) {
            $qb->orderBy('pr.id',Criteria::DESC);
        }

        $paginator  = $container->get('knp_paginator');

        $newQb = clone $qb;
        $newQb->select('COUNT(DISTINCT pr.id)');

        $results = $paginator->paginate(
            $qb->getQuery()->setHydrationMode(Query::HYDRATE_ARRAY)->setHint('knp_paginator.count', $newQb->getQuery()->getSingleScalarResult()),
            isset($data['page']) ? $data['page'] : 1,
            self::LIMIT,
            array('distinct' => false)
        );

        $paginationData = $results->getPaginationData();
        $queryParameters = $results->getParams();

        $paginationData['url'] = '#'.$container->get('uvdesk.service')->buildPaginationQuery($queryParameters);

        $data = $results->getItems();

        foreach ($data as $key => $row) {
            $data[$key]['user'] = $userService->getAgentDetailById($row['agentId']);
        }

        return [
            'preparedResponses' => $data,
            'pagination_data'   => $paginationData,
        ];
    }

    public function getPreparedResponse($id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('DISTINCT pr')->from($this->getEntityName(), 'pr')
            ->leftJoin('pr.user', 'ud')
            ->leftJoin('ud.user', 'u')
            ->andWhere('pr.id'.' = :id')
            ->setParameter('id', $id)
            ->groupBy('pr.id');

        return $qb->getQuery()->getOneOrNullResult();
    }
}
