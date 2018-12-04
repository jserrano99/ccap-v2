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
 * Class TempAltasDatatable
 *
 * @package CostesBundle\Datatables
 */
class TempAltasDatatable extends AbstractDatatable
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

		$this->options->set([
			'classes' => Style::BOOTSTRAP_4_STYLE,
			'stripe_classes' => ['strip1', 'strip2', 'strip3'],
			'individual_filtering' => true,
			'individual_filtering_position' => 'head',
			'order' => [[4, 'desc']],
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
			->add('cip', Column::class, [
				'title' => 'CIP',
				'width' => '40px',
			])
			->add('dni', Column::class, [
				'title' => 'DNI', 'width' => '150px',
			])
			->add('nombre', Column::class, [
				'title' => 'Nombre',
			])
			->add('fAlta', DateTimeColumn::class, ['title' => 'Fecha Alta', 'width' => '150px',
				'date_format' => 'DD/MM/YYYY',
				'default_content' => '',
				'filter' => [DateRangeFilter::class, [
					'cancel_button' => false,
				]],
			])
			->add('causaAlta', Column::class, [
				'title' => 'Causa de Alta',
			])
			->add('fBaja', DateTimeColumn::class, ['title' => 'Fecha Baja', 'width' => '150px',
				'date_format' => 'DD/MM/YYYY',
				'default_content' => '',
				'filter' => [DateRangeFilter::class, [
					'cancel_button' => false,
				]],
			])
			->add('causaBaja', Column::class, [
				'title' => 'Causa de Baja',
			]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'CostesBundle\Entity\TempAltas';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'tempaltas_datatable';
	}

}
