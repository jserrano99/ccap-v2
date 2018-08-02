<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace MaestrosBundle\Repository;

/**
 * Description of PlazaRepository
 *
 * @author jluis_local
 */
class MoviPatRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->where(" u.enUso = 'S' ")
                        ->orderBy('u.descrip', 'ASC');
    }

}