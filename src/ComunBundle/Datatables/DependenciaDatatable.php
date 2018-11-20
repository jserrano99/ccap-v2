<?php

namespace ComunBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

/**
 * Class DependenciaDatatable
 *
 * @package ComunBundle\Datatables
 */
class DependenciaDatatable extends AbstractDatatable {

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
            'order' => array(array(2, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
            'dom' => 'lBtrip'
        ));


        $EstadoCargaInicialAll = $this->em->getRepository('ComunBundle:EstadoCargaInicial')->findAll();
        $ModuloAll = $this->em->getRepository('ComunBundle:Modulo')->findAll();

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change' => true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id', 'width' => '15px', 'searchable' => false
                ))
                ->add('cargaInicialDep.tabla', Column::class, array(
                    'title' => 'Tabla', 'width' => '200px'
                ))
                ->add('cargaInicial.orden', Column::class, array(
                    'title' => 'Orden', 'width' => '15px', 'searchable' => false
                ))
                ->add('cargaInicial.tabla', Column::class, array(
                    'title' => 'Nombre de la Tabla', 'width' => '200px'
                ))
                ->add('cargaInicial.descripcion', Column::class, array(
                    'title' => 'Descripción', 'width' => '320px'
                ))
                ->add('cargaInicial.numDep', Column::class, array(
                    'title' => 'Nº', 'width' => '20px'
                ))
                ->add('cargaInicial.proceso', Column::class, array(
                    'title' => 'Proceso', 'width' => '40px'
                ))
                ->add('cargaInicial.modulo.descripcion', Column::class, array(
                    'title' => 'Modulo',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($ModuloAll, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))
                ))
                ->add('cargaInicial.estadoCargaInicial.descripcion', Column::class, array(
                    'title' => 'Estado',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($EstadoCargaInicialAll, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'queryDependencia',
                            'route_parameters' => array('cargaInicial_id' => 'cargaInicial_id'),
                            'label' => 'Dependencias',
                            'icon' => 'glyphicon glyphicon-list',
                            'render_if' => function ($row) {
                                if ($row["cargaInicial"]["numDep"])
                                    return true;
                            },
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Ver Dependencias de otras Cargas',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            )),
                        array(
                            'route' => 'deleteDependencia',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Dependencia',
                            'icon' => 'glyphicon glyphicon-trash',
                            'confirm' => true,
                            'confirm_message' => 'Confirmar la Eliminación Dependencia',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Eliminar Dependencia',
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
        return 'ComunBundle\Entity\Dependencia';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'dependencia_datatable';
    }

}
