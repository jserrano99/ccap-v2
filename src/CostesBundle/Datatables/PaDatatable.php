<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Style;

/**
 * Class PaDatatable
 *
 * @package CostesBundle\Datatables
 */
class PaDatatable extends AbstractDatatable
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
		]);
		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr' => ['template' => 'inicio.js.twig'],
			'search' => ['template' => 'search.js.twig'],
			'state_loaded' => ['template' => 'loaded.js.twig'],

		]);

		$edificios = $this->em->getRepository('ComunBundle:Edificio')->findAll();
		$das = $this->em->getRepository('ComunBundle:Da')->findAll();

		$this->features->set([
			'auto_width' => true,
			'ordering' => true
		]);

		$this->columnBuilder
			->add('id', Column::class, [
				'title' => 'Id',
				'width' => '20px',
				'searchable' => false
			])
			->add('pa', Column::class, [
				'title' => 'Código',
				'width' => '40px',
			])
			->add('descripcion', Column::class, [
				'title' => 'Descripción',
			])
			->add('oficial', Column::class, [
				'title' => 'Código Oficial',
			])
			->add('da.descripcion', Column::class, [
				'title' => 'Dirección Asistencial',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($das, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('edificio.descripcion', Column::class, [
				'title' => 'Edificio',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('enuso', Column::class, [
				'title' => 'Uso',
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
				'title' => 'Estado Sincronización',
				'default_content' => ''])
			->add(null, ActionColumn::class, [
				'title' => 'Acciones',
				'actions' => [
					[
						'route' => 'editPa',
						'route_parameters' => [
							'id' => 'id'],
						'label' => 'Editar',
						'icon' => 'glyphicon glyphicon-edit',
						'attributes' => [
							'rel' => 'tooltip',
							'title' => 'Editar Punto Asistencial',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button'
						]],
					['route' => 'queryEqPa',
						'route_parameters' => ['pa_id' => 'id'],
						'label' => 'Equivalencias',
						'icon' => 'glyphicon glyphicon-th-list',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Ver Equivalencias',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],

					['route' => 'descargaLogPa',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Logs',
						'icon' => 'glyphicon glyphicon-edit',
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
		return 'CostesBundle\Entity\Pa';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'pa_datatable';
	}

}
