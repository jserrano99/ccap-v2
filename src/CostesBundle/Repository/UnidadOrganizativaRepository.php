<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CostesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of UnidadOrganizativaRepository
 *
 * @author jluis_local
 */
class UnidadOrganizativaRepository extends EntityRepository
{
	public function createAlphabeticalQueryBuilder() {
		return $this->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC');
	}

}
