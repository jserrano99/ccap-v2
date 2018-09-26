<?php

namespace MaestrosBundle\Repository;

/**
 * Description of PlazaRepository
 *
 * @author jluis_local
 */
class AusenciaRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->where("u.enuso = 'S' ")
                        ->orderBy('u.descrip', 'ASC');
    }

}
