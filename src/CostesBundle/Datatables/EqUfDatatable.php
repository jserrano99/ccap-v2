<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class EqUfDatatable
 * @package MaestrosBundle\Datatables
 */
class EqUfDatatable extends AbstractDatatable
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
			'order' => [[0, 'asc']],
			'order_cells_top' => true,
			'search_in_non_visible_columns' => true,
		]);

		$edificios = $this->em->getRepository('ComunBundle:Edificio')->findAll();

		$this->features->set([
			'auto_width' => false,
			'ordering' => true,
			'length_change' => true
		]);

		$this->columnBuilder
			->add('id', Column::class, [
				'title' => 'Id', 'width' => '20px', 'searchable' => false])
			->add('edificio.descripcion', Column::class, ['title' => 'Edificio', 'width' => '160px',
				'filter' => [SelectFilter::class,
					[
						'multiple' => false,
						'select_options' => ['' => 'Todo'] + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
						'search_type' => 'eq']]])
			->add('codigoLoc', Column::class, ['title' => 'Código Local', 'width' => '30px'
				])
			->add('uf.uf', Column::class, ['title' => 'Codigo Unificado', 'width' => '30px'])
			->add('uf.oficial', Column::class, ['title' => 'Codigo Oficial', 'width' => '30px'])
			->add('uf.descripcion', Column::class, ['title' => 'Descripción', 'width' => '320px'])
			->add('enuso', Column::class, [
				'title' => 'Uso',
				'width' => '40px',
				'filter' => [SelectFilter::class, ['search_type' => 'eq',
					'multiple' => false,
					'select_options' => [
						'' => 'Todo',
						'S' => 'Si',
						'N' => 'No',
						'X' => 'Pdte. Crear'],
					'cancel_button' => false
				],
				],
			])
			->add(null, ActionColumn::class, array('title' => 'Acciones',
				'actions' => array(
					array('route' => 'activarEqUf',
						'route_parameters' => array('equf_id' => 'id'),
						'label' => 'Activar',
						'icon' => 'glyphicon glyphicon-ok-sign',
						'render_if' => function($row) {
							if ($row['enuso'] === 'N')
								return true;
						},
						'attributes' => array('rel' => 'tooltip',
							'title' => 'Activar',
							'class' => 'btn btn-info btn-xs',
							'role' => 'button')),
					array('route' => 'desActivarEqUf',
						'route_parameters' => array('equf_id' => 'id'),
						'label' => 'Desactivar',
						'icon' => 'glyphicon glyphicon-remove-sign',
						'render_if' => function($row) {
							if ($row['enuso'] === 'S')
								return true;
						},
						'attributes' => array('rel' => 'tooltip',
							'title' => 'Desactivar',
							'class' => 'btn btn-danger btn-xs',
							'role' => 'button')),
				)))
			;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'CostesBundle\Entity\EqUf';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'equf_datatable';
	}

}
