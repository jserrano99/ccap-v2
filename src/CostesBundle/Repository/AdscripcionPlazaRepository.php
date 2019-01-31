<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CostesBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of AdscripcionPlazaRepository
 *
 * @author jluis_local
 */
class AdscripcionPlazaRepository extends EntityRepository
{
	/**
	 * @param $plaza
	 * @return mixed
	 */
	public function selectAdscripcionByPlaza($plaza) {
		$CecoCias = $this->createQueryBuilder('u')
			->where("u.plaza = :plaza")
			->setParameter('plaza', $plaza)
			->getQuery()->getResult();

		return $CecoCias;
	}


}
