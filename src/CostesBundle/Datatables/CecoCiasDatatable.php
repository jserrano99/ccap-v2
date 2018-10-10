<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;

/**
 * Class CecoCiasDatatable
 *
 * @package CostesBundle\Datatables
 */
class CecoCiasDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->ajax->set(array());

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => ['strip1', 'strip2'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
        ));

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change' => true,
            'state_save' => true
        ));

        $this->columnBuilder
                ->add('id', Column::class, array('title' => 'Id', 'searchable' => false, 'width' => '20px'))
                ->add('fInicio', DateTimeColumn::class, array(
                    'title' => 'Fecha Inicio',
                    'date_format' => 'DD/MM/YYYY',
                    'filter' => array(DateRangeFilter::class, array(
                            'cancel_button' => true,
                        )),
                ))
                ->add('fFin', DateTimeColumn::class, array(
                    'title' => 'Fecha Fin',
                    'date_format' => 'DD/MM/YYYY',
                    'filter' => array(DateRangeFilter::class, array(
                            'cancel_button' => true,
                        )),
                ))
                ->add('plaza.cias', Column::class, array(
                    'title' => 'Cias',
                    'width' => '120px',
                    'default_content' => ''))
                ->add('ceco.codigo', Column::class, array(
                    'title' => 'Ceco Codigo',
                    'width' => '120px',
                    'default_content' => ''))
                ->add('ceco.descripcion', Column::class, array(
                    'title' => 'Ceco Descripcion',
                    'width' => '120px',
                    'default_content' => ''))

//                ->add(null, ActionColumn::class, array('title' => 'Acciones',
//                    'actions' => array(
//                        array('route' => 'editCecoCias',
//                            'route_parameters' => array(
//                                'id' => 'id'),
//                            'label' => 'Editar',
//                            'icon' => 'glyphicon glyphicon-edit',
//                            'attributes' => array(
//                                'rel' => 'tooltip',
//                                'title' => 'Editar',
//                                'class' => 'btn btn-primary btn-xs',
//                                'role' => 'button'
//                            )),
//                        array('route' => 'verPlazasByCecoCias',
//                            'route_parameters' => array(
//                                'ceco_id' => 'id'),
//                            'label' => 'Ver Cias',
//                            'icon' => 'glyphicon glyphicon-search',
//                            'attributes' => array(
//                                'rel' => 'tooltip',
//                                'title' => 'Ver Cias',
//                                'class' => 'btn btn-primary btn-xs',
//                                'role' => 'button')),
////                        array('route' => 'deleteCecoCias',
////                            'route_parameters' => array(
////                                'ceco_id' => 'id'
////                            ),
////                            'label' => 'Eliminar',
////                            'icon' => 'glyphicon glyphicon-trash',
////                            'attributes' => array(
////                                'rel' => 'tooltip',
////                                'title' => 'Eliminar registro en las Base de Datos de las Areas',
////                                'class' => 'btn btn-primary btn-xs',
////                                'role' => 'button')),
//                        array('route' => 'descargaLogCecoCias',
//                            'route_parameters' => array('id' => 'id'),
//                            'label' => 'Logs',
//                            'icon' => 'glyphicon glyphicon-edit',
//                            'render_if' => function ($row) {
//                                if ($row['sincroLog'] != null )
//                                    return true;
//                            },
//                            'attributes' => array('rel' => 'tooltip',
//                                'title' => 'Logs',
//                                'class' => 'btn btn-primary btn-xs',
//                                'role' => 'button'))
//                    )
//                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'CostesBundle\Entity\CecoCias';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'cecocias_datatable';
    }

}
