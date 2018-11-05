<?php
namespace CostesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;
/**
 * Class PlazaRepository
 * @package CostesBundle\Repository
 */

class PlazaRepository extends EntityRepository {

    /**
     * @return array
     */
    public function plazaSinCeco() {
        $fecha = new DateTime();
        $fecha->setDate(date('Y'), date('m'), date('d'));
        $entityManager = $this->getEntityManager();
        $CecoCias_repo = $entityManager->getRepository("CostesBundle:CecoCias");
        $PlazaAll = $this->createQueryBuilder('u')
                        ->where('u.fAmortiza is null or u.fAmortiza > :fecha')
                        ->setParameter('fecha', $fecha)
                        ->getQuery()->getResult();
        $PlazasSinCeco = [];
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

    /**
     * @param $cias
     * @return null
     */
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
