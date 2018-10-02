<?php

namespace ComunBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;

class UsuarioDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            //'cdn_language_by_locale' => true
            //'language_by_locale' => true
            'language' => 'es'

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
            'auto_width' => false,
            'ordering' => true,
            'length_change' => true
        ));

        $this->columnBuilder
                ->add('id', Column::class, array('title' => 'Id', 'width' => '20px', 'searchable' => false))
                ->add('nombre', Column::class, array('title' => 'Nombre', 'width' => '400px', 'searchable' => true))
                ->add('fcAlta', DateTimeColumn::class, array(
                    'title' => 'Fecha Alta',
                    'date_format' => 'DD/MM/YYYY',
                    'filter' => array(DateRangeFilter::class, array(
                            'cancel_button' => true,
                        )),
                ))
                ->add('perfil', Column::class, array(
                    'title' => 'Perfil',
                    'filter' => array(SelectFilter::class,
                        array('search_type' => 'eq',
                            'multiple' => false,
                            'select_options' => array(
                                '' => 'Todo',
                                'Usuario' => 'ROLE_USER',
                                'Administrador' => 'ROLE_ADMIN',
                                'Control de Costes' => 'ROLE_COSTES'),
                            'cancel_button' => false
                        ),
                    ),
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array('route' => 'editUsuario',
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
                        array('route' => 'deleteUsuario',
                            'route_parameters' => array(
                                'id' => 'id'),
                            'label' => 'Eliminar',
                            'icon' => 'glyphicon glyphicon-trash',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Eliminar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'),
                            'confirm' => true,
                            'confirm_message' => 'Confirmar la Eliminación de Usuario'),
            )))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'ComunBundle\Entity\Usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'usuario_datatable';
    }

}
