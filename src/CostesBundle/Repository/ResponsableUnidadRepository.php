<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace CostesBundle\Repository;

use DateTime;
use Doctrine\ORM\EntityRepository;

/**
 * Description of ResponsableUnidadRepository
 *
 * @author jluis_local
 */
class ResponsableUnidadRepository extends EntityRepository
{
	/**
	 * @param $UnidadOrganizativa
	 * @param $Plaza
	 * @return \CostesBundle\Entity\ResponsableUnidad|null
	 */
	public function findResponsable($UnidadOrganizativa, $Plaza)
	{
		$ResponsableUnidadAll = $this->createQueryBuilder('u')
			->where('u.plaza = :plaza')
			->andWhere('u.unidadOrganizativa = :unidadOrganizativa')
			->setParameter('plaza', $Plaza)
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult();
		if ($ResponsableUnidadAll) {
			return $ResponsableUnidadAll[0];
		} else {
			return null;
		}
	}

	/**
	 * @param $UnidadOrganizativa
	 * @return \CostesBundle\Entity\ResponsableUnidad|null
	 * @throws \Exception
	 */
	public function findResponsableActual($UnidadOrganizativa)
	{
		$fecha = new DateTime();

		$ResponsableUnidadAll = $this->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa')
			->andWhere('u.fInicio <= :fecha')
			->andWhere('u.fFin is null or u.fFin >= :fecha')
			->setParameter('fecha', $fecha)
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult();

		if ($ResponsableUnidadAll) {
			return $ResponsableUnidadAll[0];
		} else {
			return null;
		}
	}

	/**
	 * @param $UnidadOrganizativa
	 * @param $fcCambio
	 * @return \CostesBundle\Entity\ResponsableUnidad|null
	 * @throws \Exception
	 */
	public function findSolapados($UnidadOrganizativa, $fcCambio)
	{
		$fecha = new DateTime($fcCambio);

		$ResponsableUnidadAll = $this->createQueryBuilder('u')
			->where('u.unidadOrganizativa = :unidadOrganizativa')
			->andWhere('u.fInicio <= :fecha')
			->andWhere('u.fFin >= :fecha')
			->setParameter('fecha', $fecha)
			->setParameter('unidadOrganizativa', $UnidadOrganizativa)
			->getQuery()->getResult();

		if ($ResponsableUnidadAll) {
			return $ResponsableUnidadAll;
		} else {
			return null;
		}

	}
}
