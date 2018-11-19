<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

class ConceptoDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->ajax->set(array());

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_4_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
            'dom' => 'lBtrip'
        ));

	    $this->events->set([
		    'xhr' => ['template' => 'fin.js.twig'],
		    'pre_xhr' => ['template' => 'inicio.js.twig'],
		    'search' => ['template' => 'search.js.twig'],
		    'state_loaded' => ['template' => 'loaded.js.twig'],

	    ]);

	    $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change' => true,
            'state_save' => true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array('title' => 'Id', 'width' => '20px', 'searchable' => false))
                ->add('codigo', Column::class, array('title' => 'Código Unif.', 'width' => '40px', 'searchable' => true))
                ->add('descripcion', Column::class, array('title' => 'Descripción'))
                ->add('sincroLog.estado.descripcion', Column::class, array(
                    'title' => 'Estado Sincronización',
                    'width' => '320px',
                    'default_content' => ''))
                ->add(null, ActionColumn::class, array('title' => 'Acciones',
                    'actions' => array(
                        array('route' => 'editConcepto',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')),
                        array('route' => 'queryEqConcepto',
                            'route_parameters' => array('concepto_id' => 'id'),
                            'label' => 'Equivalencias',
                            'icon' => 'glyphicon glyphicon-th-list',
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Equivalencias',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')),
                        array('route' => 'descargaLogConcepto',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Logs',
                            'icon' => 'glyphicon glyphicon-download-alt',
                            'render_if' => function ($row) {
                                if ($row['sincroLog'] != null)
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Logs',
                                'class' => 'btn btn-warning btn-xs',
                                'role' => 'button'))
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'MaestrosBundle\Entity\Concepto';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'concepto_datatable';
    }

}
