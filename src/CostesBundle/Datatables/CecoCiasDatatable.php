<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class CecoCiasDatatable
 *
 * @package CostesBundle\Datatables
 */
class CecoCiasDatatable extends AbstractDatatable
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
			'classes' => Style::BOOTSTRAP_3_STYLE,
			'stripe_classes' => ['strip1', 'strip2'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[0, 'asc']],
			'order_cells_top' => true,
			'search_in_non_visible_columns' => true,
		]);

		$this->features->set([
			'auto_width' => true,
			'ordering' => true,
			'length_change' => true,
			'state_save' => true
		]);
		$this->events->set([
			'xhr' => ['template' => 'fin.js.twig'],
			'pre_xhr' => ['template' => 'inicio.js.twig'],
			'search' => ['template' => 'search.js.twig'],
			'state_loaded' => ['template' => 'loaded.js.twig'],

		]);
		$this->columnBuilder
			->add('id', Column::class, ['title' => 'Id', 'searchable' => false, 'width' => '20px'])
			->add('fInicio', DateTimeColumn::class, [
				'title' => 'Fecha Inicio',
				'date_format' => 'DD/MM/YYYY',
				'filter' => [DateRangeFilter::class, [
					'cancel_button' => true,
				]],
			])
			->add('fFin', DateTimeColumn::class, [
				'title' => 'Fecha Fin',
				'date_format' => 'DD/MM/YYYY',
				'filter' => [DateRangeFilter::class, [
					'cancel_button' => true,
				]],
			])
			->add('plaza.cias', Column::class, [
				'title' => 'Cias',
				'width' => '120px',
				'default_content' => ''])
			->add('ceco.codigo', Column::class, [
				'title' => 'Ceco Codigo',
				'width' => '120px',
				'default_content' => ''])
			->add('ceco.descripcion', Column::class, [
				'title' => 'Ceco Descripcion',
				'width' => '120px',
				'default_content' => ''])
		;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'CostesBundle\Entity\CecoCias';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'cecocias_datatable';
	}

}
