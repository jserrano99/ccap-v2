<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class CategDatatable
 *
 * @package MaestrosBundle\Datatables
 */
class CategDatatable extends AbstractDatatable
{

	/**
	 * {@inheritdoc}
	 */
	public function buildDatatable(array $options = [])
	{
		$this->language->set([
			'cdn_language_by_locale' => true
		]);

		$this->ajax->set([
		]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_3_STYLE,
			'stripe_classes' => ['strip1', 'strip2', 'strip3'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[0, 'asc']],
			'order_cells_top' => true,
			'search_in_non_visible_columns' => true,
			'dom' => 'lBrftip',

		]);
		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr' => ['template' => 'inicio.js.twig'],
			'search' => ['template' => 'search.js.twig'],
			'state_loaded' => ['template' => 'loaded.js.twig'],

		]);

		$this->extensions->set([]);

		$this->features->set([]);
		$catgenAll = $this->em->getRepository('MaestrosBundle:CatGen')->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->getQuery()->getResult();
		$grupoCobroAll = $this->em->getRepository('MaestrosBundle:GrupoCobro')->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->getQuery()->getResult();

		$this->columnBuilder
			->add('id', Column::class, ['title' => 'Id', 'width' => '20px', 'searchable' => false])
			->add('codigo', Column::class, ['title' => 'Codigo', 'width' => '80px'])
			->add('descripcion', Column::class, ['title' => 'Descripcion', 'width' => '420px'])
			->add('catGen.descripcion', Column::class, [
				'title' => 'Categoría General',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($catgenAll, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('grupoCobro.descripcion', Column::class, [
				'title' => 'Grupo Cobro por Defecto',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($grupoCobroAll, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('enuso', Column::class, [
				'title' => 'Uso',
				'width' => '40px',
				'filter' => [SelectFilter::class,
					[
						'search_type' => 'eq',
						'multiple' => false,
						'select_options' => [
							'' => 'Todo',
							'S' => 'Si',
							'N' => 'No'],
						'cancel_button' => false,
						'initial_search' => 'S'
					],
				],
			])
			->add('sincroLog.estado.descripcion', Column::class, [
				'title' => 'Estado Sincronzación',
				'default_content' => ''])
			->add(null, ActionColumn::class, [
				'title' => 'Acciones',
				'actions' => [
					['route' => 'editCateg',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Editar',
						'icon' => 'glyphicon glyphicon-edit',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Editar Categoría',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],
					['route' => 'queryEqCateg',
						'route_parameters' => ['categ_id' => 'id'],
						'label' => 'Equivalencias',
						'icon' => 'glyphicon glyphicon-th-list',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Ver Equivalencias Categoría',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],
					['route' => 'descargaLogCateg',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Logs',
						'icon' => 'glyphicon glyphicon-download-alt',
						'render_if' => function ($row) {
							if ($row['sincroLog'] != null)
								return true;
						},
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Logs',
							'class' => 'btn btn-warning btn-xs',
							'role' => 'button']]
				]]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'MaestrosBundle\Entity\Categ';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'categ_datatable';
	}

}
