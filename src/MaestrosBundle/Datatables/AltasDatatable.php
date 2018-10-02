<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter; 

class AltasDatatable extends AbstractDatatable {

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

        $edificios = $this->em->getRepository('ComunBundle:Edificio')->findAll();

        $this->features->set(array(
            'auto_width' => false,
            'ordering' => true,
            'length_change'=> true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array('title' => 'Id', 'width' => '20px', 'searchable' => false))
                ->add('codigo', Column::class, array('title' => 'Código','width' => '20px', 'searchable' => true))
                ->add('descripcion', Column::class, array( 'title' => 'Descripción', 'width' => '500px'))
                ->add('enuso', Column::class, array(
                    'title' => 'Uso',
                    'filter' => array(SelectFilter::class,
                        array('search_type' => 'eq',
                            'multiple' => false,
                            'select_options' => array(
                                '' => 'Todo',
                                'S' => 'Si',
                                'N' => 'No'),
                            'cancel_button' => false,
                            'initial_search' => 'S'
                        ),
                    ),
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array('route' => 'editAltas',
                            'route_parameters' => array(
                                'id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            )
                        ),
                        array('route' => 'queryEqAltas',
                            'route_parameters' => array(
                                'altas_id' => 'id'),
                            'label' => 'Equivalencias',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Equivalencias',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')
                        )
                    )
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'MaestrosBundle\Entity\Altas';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'altas_datatable';
    }

}
