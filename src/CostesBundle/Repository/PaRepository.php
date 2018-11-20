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
class PaRepository extends \Doctrine\orm\EntityRepository
{
	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function createAlphabeticalQueryBuilder()
	{
		return $this->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->where("u.enuso = 'S' ");
	}

	/**
	 * @param $oficial
	 * @return null
	 */
	public function findPaByOficial($oficial)
	{
		$PaAll = $this->createQueryBuilder('u')
			->where('u.oficial = :oficial')
			->setParameter('oficial', trim($oficial))
			->getQuery()->getResult();
		if ($PaAll) {
			return $PaAll[0];
		} else {
			return null;
		}
	}

}
