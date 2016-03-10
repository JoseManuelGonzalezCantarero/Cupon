<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CiudadRepository extends EntityRepository
{
    public function findCercanas($ciudad_id)
    {
        $em = $this->getEntityManager();

        $consulta = $em->createQuery('
        SELECT c
        FROM AppBundle:Ciudad c
        WHERE c.id != :id
        ORDER BY c.nombre ASC');

        $consulta->setMaxResults(5);
        $consulta->setParameter('id', $ciudad_id);

        return $consulta->getResult();
    }
}
