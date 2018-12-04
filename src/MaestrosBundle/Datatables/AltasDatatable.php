<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

class AltasDatatable extends AbstractDatatable
{

	/**
	 * {@inheritdoc}
	 */
	public function buildDatatable(array $options = [])
	{
		$this->language->set([
			'cdn_language_by_locale' => true
		]);

		$this->ajax->set([]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_4_STYLE,
			'stripe_classes' => ['strip1', 'strip2', 'strip3'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[1, 'asc']],
			'order_cells_top' => true,
			'search_in_non_visible_columns' => true,
			'dom' => 'lBrftip'
		]);

		$this->features->set([
			'auto_width' => false,
			'ordering' => true,
			'length_change' => true
		]);

		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr' => ['template' => 'inicio.js.twig'],
			'search' => ['template' => 'search.js.twig'],
			'state_loaded' => ['template' => 'loaded.js.twig'],
		]);

		$this->columnBuilder
			->add('id', Column::class, ['title' => 'Id', 'width' => '20px', 'searchable' => false])
			->add('codigo', Column::class, ['title' => 'Código', 'width' => '40px', 'searchable' => true])
			->add('descripcion', Column::class, ['title' => 'Descripción', 'width' => '500px'])
			->add('enuso', Column::class, [
				'title' => 'Uso',
				'filter' => [SelectFilter::class,
					['search_type' => 'eq',
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
				'title' => 'Estado Sincronización',
				'default_content' => ''])
			->add(null, ActionColumn::class, [
				'title' => 'Acciones',
				'actions' => [
					['route' => 'editAltas',
						'route_parameters' => [
							'id' => 'id'],
						'label' => 'Editar',
						'icon' => 'glyphicon glyphicon-edit',
						'attributes' => [
							'rel' => 'tooltip',
							'title' => 'Editar',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button'
						]
					],
					['route' => 'queryEqAltas',
						'route_parameters' => [
							'altas_id' => 'id'],
						'label' => 'Equivalencias',
						'icon' => 'glyphicon glyphicon-th-list',
						'attributes' => [
							'rel' => 'tooltip',
							'title' => 'Equivalencias',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button'
						]
					],
					['route' => 'descargaLogAltas',
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
				]
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'MaestrosBundle\Entity\Altas';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'altas_datatable';
	}

}
