<?php

namespace CostesBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;

/**
 * Class CecoDatatable
 *
 * @package CostesBundle\Datatables
 */
class CecoDatatable extends AbstractDatatable {

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
                ->add('id', Column::class, array('title' => 'Id','searchable' => false, 'width' => '20px'))
                ->add('sociedad', Column::class, array('title' => 'Sociedad','searchable' => false, 'width' => '20px'))
                ->add('division', Column::class, array('title' => 'División','searchable' => false, 'width' => '20px'))
                ->add('codigo', Column::class, array('title' => 'Código','searchable' => true, 'width' => '150px'))
                ->add('descripcion', Column::class, array('title' => 'Descripción','searchable' => true, 'width' => '300px'))
                ->add('sincroLog.estado.descripcion', Column::class, array(
                    'title' => 'Estado Sincronización',
                    'width' => '120px',
                    'default_content' => ''))
                
                ->add(null, ActionColumn::class, array('title' => 'Acciones',
                    'actions' => array(
                        array('route' => 'editCeco',
                            'route_parameters' => array(
                                'id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            )),
                        array('route' => 'verPlazasByCeco',
                            'route_parameters' => array(
                                'ceco_id' => 'id'),
                            'label' => 'Ver Cias',
                            'icon' => 'glyphicon glyphicon-search',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Ver Cias',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')),
//                        array('route' => 'deleteCeco',
//                            'route_parameters' => array(
//                                'ceco_id' => 'id'
//                            ),
//                            'label' => 'Eliminar',
//                            'icon' => 'glyphicon glyphicon-trash',
//                            'attributes' => array(
//                                'rel' => 'tooltip',
//                                'title' => 'Eliminar registro en las Base de Datos de las Areas',
//                                'class' => 'btn btn-primary btn-xs',
//                                'role' => 'button')),
                        array('route' => 'descargaLogCeco',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Logs',
                            'icon' => 'glyphicon glyphicon-edit',
                            'render_if' => function ($row) {
                                if ($row['sincroLog'] != null )
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Logs',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'))
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'CostesBundle\Entity\Ceco';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'pa_datatable';
    }
}
