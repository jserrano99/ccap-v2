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
class CecoRepository extends \Doctrine\orm\EntityRepository {

    public function findCecoByCodigo($codigo) {
        $Ceco = $this->createQueryBuilder('u')
                        ->where("u.codigo = :codigo")
                        ->setParameter('codigo', $codigo)
                        ->getQuery()->getResult();
        if ($Ceco) {
            return $Ceco[0];
        } else {
            return null;
        }
    }

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->orderBy('u.descripcion', 'ASC')
                        ->where("u.enuso = 'S' ");
    }

}
