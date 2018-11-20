<?php
namespace MaestrosBundle\Repository;

/**
 * Description of TurnoRepository
 *
 * @author jluis_local
 */
class TurnoRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->orderBy('u.descripcion', 'ASC');
    }

}
