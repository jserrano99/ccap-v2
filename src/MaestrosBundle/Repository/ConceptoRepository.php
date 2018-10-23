<?php

namespace MaestrosBundle\Repository;

/**
 * Description of ConceptoRepository
 *
 * @author jluis_local
 */
class ConceptoRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->orderBy('u.descripcion', 'ASC');
    }

}
