<?php

namespace ComunBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;

/**
 * Class SincroLogDatatable
 *
 * @package ComunBundle\Datatables
 */
class SincroLogDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
//            'cdn_language_by_locale' => true
            'language' => 'es'
        ));

        $this->ajax->set(array());

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_4_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'desc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
            'dom' => 'lBfrtip',
            
        ));

        $EstadosAll = $this->em->getRepository('ComunBundle:EstadoCargaInicial')->findAll();

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change' => true,
            'state_save' => true
        ));


//        $this->extensions->set(array(
//            //'buttons' => true,
//            'buttons' => array(
//                'show_buttons' => array('copy', 'print'), // built-in buttons
//                'create_buttons' => array(// custom buttons 
//                    array(
//                        'action' => array(
//                            'template' => 'comun/action.js.twig',
//                        //'vars' => array('id' => '2', 'test' => 'new value'),
//                        ),
//                        'text' => 'alert',
//                    ),
//                    array(
//                        'extend' => 'csv',
//                        'text' => 'custom csv button',
//                    ),
//                    array(
//                        'extend' => 'pdf',
//                        'text' => 'my pdf',
//                        'button_options' => array(
//                            'exportOptions' => array(
//                                'columns' => array('1', '2'),
//                            ),
//                        ),
//                    ),
//                ),
//            ),
//        ));


        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id', 'width' => '15px', 'searchable' => false
                ))
                ->add('tabla', Column::class, array(
                    'title' => 'Tabla', 'width' => '15px', 'searchable' => false,
                ))
                ->add('script', Column::class, array(
                    'title' => 'Script', 'width' => '15px', 'searchable' => false,
                ))
                ->add('idElemento', Column::class, array(
                    'title' => 'Identificador Elemento', 'width' => '200px'
                ))
                ->add('ficheroLog', Column::class, array('title' => 'log',
                ))
                ->add('fechaProceso', DateTimeColumn::class, array('title' => 'Fecha Proceso', 'width' => '150px',
                    'date_format' => 'DD/MM/YYYY HH:MM:ss',
                    'filter' => array(DateRangeFilter::class, array(
                            'cancel_button' => false,
                        )),
                ))
                ->add('usuario.codigo', Column::class, array(
                    'title' => 'Descripción',
                ))
                ->add('estado.descripcion', Column::class, array(
                    'title' => 'Estado',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($EstadosAll, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'descargaSincroLog',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Log',
                            'icon' => 'glyphicon glyphicon-download',
                            'render_if' => function ($row) {
                                if ($row["ficheroLog"] != "")
                                    return true;
                            },
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Descarga Log de Ejecución',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            ))
            )))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'ComunBundle\Entity\SincroLog';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'sincrolog_datatable';
    }

}
