<?php

namespace MaestrosBundle\Repository;

/**
 * Description of GrupoProfRepository
 *
 * @author jluis_local
 */
class GrupoProfRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->orderBy('u.codigo', 'ASC');
    }

}
