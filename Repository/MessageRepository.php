<?php

namespace Ds\Bundle\CommunicationBundle\Repository;

use Ds\Bundle\CommunicationBundle\Entity\Communication;
use Ds\Bundle\EntityBundle\Repository\EntityRepository;

/**
 * Class MessageRepository
 */
class MessageRepository extends EntityRepository
{


    /**
     * @param Communication $communication
     *
     * @return array
     */
    public function getCommunicationStatus(Communication $communication)
    {
        $statuses = [
            'queued'    => 0,
            'sending'   => 0,
            'sent'      => 0,
            'open'      => 0,
            'cancelled' => 0,
            'failed'    => 0,
        ];

        $qb = $this->createQueryBuilder('m');
        $qb
            ->select('m.deliveryStatus, count(m) as cnt')
            ->where('m.communication = :communication_id')
            ->setParameter(':communication_id', $communication->getId())
            ->groupBy('m.deliveryStatus')//->orderBy('s.id', 'DESC')
        ;

        $results = $qb->getQuery()->getScalarResult();

        foreach ($results as $result)
        {
            $statuses[$result['deliveryStatus']] = intval($result['cnt']);
        }

        return $statuses;
    }

}
