<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class CatGenDatatable
 *
 * @package MaestrosBundle\Datatables
 */
class CatGenDatatable extends AbstractDatatable
{

	/**
	 * {@inheritdoc}
	 */
	public function buildDatatable(array $options = [])
	{
		$this->language->set([
			'cdn_language_by_locale' => true
		]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_3_STYLE,
			'stripe_classes' => ['strip1', 'strip2', 'strip3'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[0, 'asc']],
			'order_cells_top' => true,
			'search_in_non_visible_columns' => true,
			'dom' => 'lBtrip'

		]);

		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr' => ['template' => 'inicio.js.twig'],
			'search' => ['template' => 'search.js.twig'],
			'state_loaded' => ['template' => 'loaded.js.twig'],

		]);

		$this->columnBuilder
			->add('id', Column::class, [
				'title' => 'Id',
				'width' => '20px',
				'searchable' => false
			])
			->add('codigo', Column::class, [
				'title' => 'Codigo',
				'width' => '40px'
			])
			->add('descripcion', Column::class, [
				'title' => 'Descripcion',
			])
			->add('enuso', Column::class, [
				'title' => 'Uso',
				'filter' => [SelectFilter::class,
					[
						'search_type' => 'eq',
						'multiple' => false,
						'select_options' => [
							'' => 'Todo',
							'S' => 'Si',
							'N' => 'No',
						],
						'cancel_button' => false,
						'initial_search' => 'S'
					],
				],
			])
			->add('sincroLog.estado.descripcion', Column::class, [
				'title' => 'Log',
				'width' => '120px',
				'default_content' => ''])
			->add(null, ActionColumn::class, [
				'title' => 'Acciones',
				'actions' => [
					['route' => 'editCatGen',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Editar',
						'icon' => 'glyphicon glyphicon-edit',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Editar Categoría General',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],
					['route' => 'queryEqCatGen',
						'route_parameters' => ['catgen_id' => 'id'],
						'label' => 'Equivalencias',
						'icon' => 'glyphicon glyphicon-th-list',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Ver Equivalencias',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],
					['route' => 'descargaLogCatGen',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Logs',
						'icon' => 'glyphicon glyphicon-download-alt',
						'render_if' => function ($row) {
							if ($row['sincroLog'] != null)
								return true;
						},
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Logs',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']]
				]]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'MaestrosBundle\Entity\CatGen';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'catgen_datatable';
	}

}
