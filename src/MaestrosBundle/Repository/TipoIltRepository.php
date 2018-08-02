
<?php
namespace MaestrosBundle\Repository;

/**
 * Description of TipoIltRepository
 *
 * @author jluis_local
 */
class TipoIltRepository extends \Doctrine\orm\EntityRepository {

    public function createAlphabeticalQueryBuilder() {
        return $this->createQueryBuilder('u')
                        ->orderBy('u.descrip', 'ASC');
    }

}
