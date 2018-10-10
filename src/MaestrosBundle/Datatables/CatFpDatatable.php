<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
 * Class CatFpDatatable
 *
 * @package MaestrosBundle\Datatables
 */
class CatFpDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            'search_in_non_visible_columns' => true,
        ));

        $this->extensions->set(array());

        $this->features->set(array());
        
        $this->columnBuilder
                ->add('id', Column::class, array('title' => 'Id','width' => '20px','searchable' => false))
                ->add('codigo', Column::class, array('title' => 'Codigo', 'width' => '80px'))
                ->add('descripcion', Column::class, array('title' => 'Descripcion','width' => '420px'))
                ->add('sincroLog.estado.descripcion', Column::class, array(
                    'title' => 'Log',
                    'width' => '120px',
                    'default_content' => ''))
                ->add('enuso', Column::class, array(
                    'title' => 'Uso',
                    'width' => '40px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'search_type' => 'eq',
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
                        array('route' => 'editCatFp',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Editar',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')),
                        array('route' => 'queryEqCatFp',
                            'route_parameters' => array('catfp_id' => 'id'),
                            'label' => 'Equivalencias',
                            'icon' => 'glyphicon glyphicon-th-list',
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Ver Equivalencias',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')),
                        array('route' => 'descargaLogCatFp',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Logs',
                            'icon' => 'glyphicon glyphicon-download-alt',
                            'render_if' => function ($row) {
                                if ($row['sincroLog'] != null )
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Logs',
                                'class' => 'btn btn-warning btn-xs',
                                'role' => 'button'))
                        )))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'MaestrosBundle\Entity\CatFp';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'catfp_datatable';
    }

}
