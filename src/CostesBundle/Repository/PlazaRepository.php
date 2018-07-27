<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CostesBundle\Repository;

/**
 * Description of PlazaRepository
 *
 * @author jluis_local
 */
class PlazaRepository extends \Doctrine\orm\EntityRepository {

    public function plazaSinCeco() {
        $fecha = new \DateTime();
        $fecha->setDate(date('Y'), date('m'), date('d'));
        $entityManager = $this->getEntityManager();
        $CecoCias_repo = $entityManager->getRepository("CostesBundle:CecoCias");
        $PlazaAll = $this->createQueryBuilder('u')
                        ->where('u.fAmortiza is null or u.fAmortiza > :fecha')
                        ->setParameter('fecha', $fecha)
                        ->getQuery()->getResult();
        $PlazasSinCeco = Array();
        foreach ($PlazaAll as $Plaza) {
            $CecoCias = $CecoCias_repo->createQueryBuilder('u')
                            ->where('u.plaza = :plaza')
                            ->setParameter('plaza', $Plaza)
                            ->getQuery()->getResult();
            if (!$CecoCias) {
                $PlazasSinCeco[] = $Plaza;
            }
        }
        return($PlazasSinCeco);
    }

    public function findPlazaByCias($cias) {
        $PlazaAll = $this->createQueryBuilder('u')
                        ->where('u.cias = :cias')
                        ->setParameter('cias', trim($cias))
                        ->getQuery()->getResult();
        if ($PlazaAll) {
            return $PlazaAll[0];
        } else {
            return null;
        }
    }

}
