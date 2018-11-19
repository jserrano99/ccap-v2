<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class CatGenDatatable
 *
 * @package CostesBundle\Datatables
 */
class PlazaDatatable extends AbstractDatatable
{

	/**
	 * {@inheritdoc}
	 */
	public function buildDatatable(array $options = [])
	{
		$this->language->set([
			//'cdn_language_by_locale' => true
			'language' => 'es',
		]);

		$this->ajax->set([]);

		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr'=> ['template' => 'inicio.js.twig'],
			'search'=> ['template' => 'search.js.twig'],
			'state_loaded'=> ['template' => 'loaded.js.twig'],

		]);

		$this->options->set([
			'classes' => Style::BOOTSTRAP_3_STYLE,
			'stripe_classes' => ['strip1', 'strip2', 'strip3'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[1, 'asc']],
			'order_cells_top' => true,
			'order_classes' => true,
			'search_in_non_visible_columns' => true,
			'dom' => 'lBrftip',
			'length_menu' => ['10', '25', '75', '100', '5000']
		]);

		$this->extensions->set([
			'buttons' => [
				'show_buttons' => ['copy'],
				'create_buttons' => [
					[
						'extend' => 'excel',
						'text' => 'Exportar Excel',
						'button_options' => [
							'exportOptions' => [
								'search' => 'none'
							]
						]
					],
					[
						'extend' => 'pdf',
						'text' => 'Pdf',
						'button_options' => [
							'exportOptions' => [
								'columns' => ['1', '2', '3', '4', '5', '6', '7', '8'],
							],
							'orientation' => 'landscape'
						]
					]
				]
			]
		]);

		$this->features->set([
			'auto_width' => true,
			'ordering' => true,
			'length_change' => true,
			'state_save' => false,
			'searching' => true
		]);

		$UfAll = $this->getEntityManager()->getRepository('CostesBundle:Uf')->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->where("u.enuso = 'S'")
			->getQuery()->getResult();

		$PaAll = $this->getEntityManager()->getRepository('CostesBundle:Pa')->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->where("u.enuso = 'S'")
			->getQuery()->getResult();
		$CatGenAll = $this->getEntityManager()->getRepository('MaestrosBundle:CatGen')->createQueryBuilder('u')
			->orderBy('u.descripcion', 'ASC')
			->where("u.enuso = 'S'")
			->getQuery()->getResult();

		$this->columnBuilder
			->add('id', Column::class, [
				'title' => 'Id',
				'width' => '25px'])
			->add('cias', Column::class, [
				'title' => 'CIAS',
				'width' => '95px',
				'filter' => [TextFilter::class, [
					'cancel_button' => false
				]]])
			->add('uf.oficial', Column::class, [
				'title' => 'Código',
				'width' => '65px'])
			->add('uf.descripcion', Column::class, [
				'title' => 'Unidad Funcional',
				'width' => '220px',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($UfAll, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('pa.oficial', Column::class, [
				'title' => 'Código',
				'width' => '65px'])
			->add('pa.descripcion', Column::class, [
				'title' => 'Punto Asistencial',
				'width' => '220px',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($PaAll, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('cecoActual.codigo', Column::class, [
				'title' => 'Ceco Actual',
				'width' => '65px',
				'default_content' => ''])
			->add('catGen.descripcion', Column::class, [
				'title' => 'Cat. General',
				'width' => '220px',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($CatGenAll, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('amortizada', Column::class, [
				'title' => 'Amortizada',
				'width' => '45px',
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
						'initial_search' => 'N',
					],
				],
			])
			->add('fAmortiza', DateTimeColumn::class, ['title' => 'Fecha Amortización', 'width' => '150px',
				'date_format' => 'DD/MM/YYYY',
				'default_content' => '',
				'filter' => [DateRangeFilter::class, [
					'cancel_button' => false,
				]],
			])
			->add('sincroLog.estado.descripcion', Column::class, [
				'title' => 'Estado Sincronización',
				'width' => '220px',
				'default_content' => ''])
			->add(null, ActionColumn::class, [
				'title' => 'Acciones',
				'actions' => [
					['route' => 'editPlaza',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Editar',
						'icon' => 'glyphicon glyphicon-edit',
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Editar Plaza',
							'class' => 'btn btn-primary btn-xs',
							'role' => 'button']],
					['route' => 'amortizacionPlaza',
						'route_parameters' => ['id' => 'id'],
						'label' => 'Amortizar',
						'icon' => 'glyphicon glyphicon-erase',
						'render_if' => function ($row) {
							if ($row['amortizada'] == 'N')
								return true;
						},
						'attributes' => ['rel' => 'tooltip',
							'title' => 'Amortizar Plaza',
							'class' => 'btn btn-danger btn-xs',
							'role' => 'button']],
					['route' => 'descargaLogPlaza',
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
		return 'CostesBundle\Entity\Plaza';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'plaza_datatable';
	}

}
