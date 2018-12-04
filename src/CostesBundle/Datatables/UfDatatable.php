<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use ComunBundle\Entity\Edificio;
/**
 * Class UfDatatable
 *
 * @package CostesBundle\Datatables
 */
class UfDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            //'cdn_language_by_locale' => true
			'language' => 'es',
        ));

        $this->ajax->set(array());

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => false,
	        'dom' => 'lBrftip',

        ));

        $this->extensions->set(array());
	    $this->events->set([
		    'xhr' => ['template' => 'fin.js.twig'],
		    'pre_xhr'=> ['template' => 'inicio.js.twig'],
		    'search'=> ['template' => 'search.js.twig'],
		    'state_loaded'=> ['template' => 'loaded.js.twig'],

	    ]);
        
        $this->features->set(array(
        ));

        $edificios = $this->em->getRepository('ComunBundle:Edificio')->findAll();
        $das = $this->em->getRepository('ComunBundle:Da')->findAll();
        
        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id',
                    'width' => '25px'
                ))
                ->add('uf', Column::class, array(
                    'title' => 'Uf',
                    'width' => '50px'
                ))
                ->add('descripcion', Column::class, array(
                    'title' => 'Descripción',
                ))
                ->add('oficial', Column::class, array(
                    'title' => 'Código Oficial',
                    'width' => '60px'
                ))
                ->add('da.descripcion', Column::class, array(
                    'title' => 'Dirección Asistencial',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($das, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('edificio.descripcion', Column::class, array(
                    'title' => 'Edificio',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('enuso', Column::class, array(
                    'title' => 'En Uso',
                    'width' => '20px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'search_type' => 'eq',
                            'multiple' => false,
                            'select_options' => array(
                                '' => 'Todo',
                                'S' => 'Si',
                                'N' => 'No',
                            ),
                            'cancel_button' => false,
                            'initial_search' => 'S',
                        ),
                    ),
                ))
                ->add('sincroLog.estado.descripcion', Column::class, array(
                    'title' => 'Estado Sincronización',
                    'default_content' => ''))

                ->add(null, ActionColumn::class, [
                    'title' => 'Acciones',
                    'actions' => [
                        [
                            'route' => 'editUf',
                            'route_parameters' => [
                                'id' => 'id'
                            ],
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => [
                                'rel' => 'tooltip',
                                'title' => 'Editar Unidad Funcional',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            ]],
	                    ['route' => 'queryEqUf',
		                    'route_parameters' => ['uf_id' => 'id'],
		                    'label' => 'Equivalencias',
		                    'icon' => 'glyphicon glyphicon-th-list',
		                    'attributes' => ['rel' => 'tooltip',
			                    'title' => 'Ver Equivalencias',
			                    'class' => 'btn btn-primary btn-xs',
			                    'role' => 'button']],

	                    ['route' => 'descargaLogUf',
                            'route_parameters' => ['id' => 'id'],
                            'label' => 'Logs',
                            'icon' => 'glyphicon glyphicon-edit',
                            'render_if' => function ($row) {
                                if ($row['sincroLog'] != null)
                                    return true;
                            },
                            'attributes' => ['rel' => 'tooltip',
                                'title' => 'Logs',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button']]
                    ]
                ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'CostesBundle\Entity\Uf';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'uf_datatable';
    }

}
