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
class UfRepository extends \Doctrine\orm\EntityRepository
{
	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function createAlphabeticalQueryBuilder()
	{
		return $this->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->where("u.enuso = 'S'");
	}

	public function findUfByOficial($oficial)
	{
		$UfAll = $this->createQueryBuilder('u')
			->where('u.oficial = :oficial')
			->setParameter('oficial', trim($oficial))
			->getQuery()->getResult();
		if ($UfAll) {
			return $UfAll[0];
		} else {
			return null;
		}
	}

}
