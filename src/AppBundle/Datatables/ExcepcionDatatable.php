<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;

/**
 * Class ExcepcionDatatable
 *
 * @excepcionckage AppBundle\Datatables
 */
class ExcepcionDatatable extends AbstractDatatable {

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
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
        ));
        
        $this->features->set(array(
        ));

        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id',
                    'width' => '20px',
                    'searchable' => false
                 ))
                ->add('descripcion', Column::class, array(
                    'title' => 'Descripción',
                ))
                ->add('cecoReal.codigo', Column::class, array(
                    'title' => 'Código Ceco Calculado',
                ))
                ->add('cecoReal.descripcion', Column::class, array(
                    'title' => 'Ceco Calculado',
                ))
                ->add('cecoExcepcion.codigo', Column::class, array(
                    'title' => 'Código Ceco Excepción',
                ))
                ->add('cecoExcepcion.descripcion', Column::class, array(
                    'title' => 'Ceco Excepción',
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'editExcepcion',
                            'route_parameters' => array(
                                'id' => 'id'
                            ),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            ),
                        )
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'AppBundle\Entity\Excepcion';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'excepcion_datatable';
    }

}
