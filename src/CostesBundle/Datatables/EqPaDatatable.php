<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class EqPaDatatable
 * @package MaestrosBundle\Datatables
 */
class EqPaDatatable extends AbstractDatatable
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
			->add('codigoLoc', Column::class, ['title' => 'Código Local', 'width' => '30px',
				])
			->add('pa.pa', Column::class, ['title' => 'Codigo Unificado', 'width' => '30px'])
			->add('pa.oficial', Column::class, ['title' => 'Codigo Oficial', 'width' => '30px'])
			->add('pa.descripcion', Column::class, ['title' => 'Descripción', 'width' => '320px'])
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
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntity()
	{
		return 'CostesBundle\Entity\EqPa';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName()
	{
		return 'eqpa_datatable';
	}

}
