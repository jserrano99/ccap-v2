<?php

namespace MaestrosBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

class EqCatGenDatatable extends AbstractDatatable {

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
            'length_change' => true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id', 'width' => '20px', 'searchable' => false))
                ->add('edificio.descripcion', Column::class, array('title' => 'Edificio', 'width' => '160px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('codigoLoc', Column::class, array('title' => 'Código Local', 'width' => '30px',
                      'editable' => array(TextEditable::class, array(
                            'pk' => 'id',
                            'mode' => 'inline'
                        ))))
                ->add('catgen.codigo', Column::class, array('title' => 'Codigo Unificado', 'width' => '30px'))
                ->add('catgen.descripcion', Column::class, array('title' => 'Descripción', 'width' => '320px'))
                ->add('enuso', Column::class, array(
                    'title' => 'Uso',
                    'width' => '40px',
                    'filter' => array(SelectFilter::class, array('search_type' => 'eq',
                            'multiple' => false,
                            'select_options' => array(
                                '' => 'Todo',
                                'S' => 'Si',
                                'N' => 'No',
                                'X' => 'Pdte. Crear'),
                            'cancel_button' => false
                        ), 
                    ),
                ))
                ->add(null, ActionColumn::class, array('title' => 'Acciones',
                    'actions' => array(
                        array('route' => 'activaEqCatGen',
                            'route_parameters' => array('eqcatgen_id' => 'id'),
                            'label' => 'Activar',
                            'icon' => 'glyphicon glyphicon-ok-sign',
                            'render_if' => function($row) {
                                if ($row['enuso'] === 'N')
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Activar',
                                'class' => 'btn btn-info btn-xs',
                                'role' => 'button')),
                        array('route' => 'desactivaEqCatGen',
                            'route_parameters' => array('eqcatgen_id' => 'id'),
                            'label' => 'Desactivar',
                            'icon' => 'glyphicon glyphicon-remove-sign',
                            'render_if' => function($row) {
                                if ($row['enuso'] === 'S')
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Desactivar',
                                'class' => 'btn btn-danger btn-xs',
                                'role' => 'button')),
                        array('route' => 'addEqCatGen',
                            'route_parameters' => array('eqcatgen_id' => 'id'),
                            'label' => 'Crear',
                            'icon' => 'glyphicon glyphicon-new-window',
                            'render_if' => function($row) {
                                if ($row['enuso'] == 'X')
                                    return true;
                            },
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Crear',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')))))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'MaestrosBundle\Entity\EqCatGen';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'eqcatgen_datatable';
    }

}
