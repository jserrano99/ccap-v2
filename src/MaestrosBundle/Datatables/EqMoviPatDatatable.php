<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;

class EqMoviPatDatatable extends AbstractDatatable {

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

        $edificios = $this->em->getRepository('AppBundle:Edificio')->findAll();
        $moviPatAll = $this->em->getRepository('AppBundle:MoviPat')->findAll();

        $this->features->set(array(
            'auto_width' => false,
            'ordering' => true,
            'length_change' => true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id', 'width' => '20px', 'searchable' => false))
                ->add('edificio.descripcion', Column::class, array('title' => 'Edificio', 'width' => '360px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('codigoLoc', Column::class, array('title' => 'Código Local', 'width' => '30px'))
                ->add('moviPat.codigo', Column::class, array('title' => 'Codigo Unificado', 'width' => '30px'))
                ->add('moviPat.descrip', Column::class, array(
                    'title' => 'Descripción',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($moviPatAll, 'descrip', 'descrip'),
                            'search_type' => 'eq')),
                    'width' => '320px'))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'deleteEqMoviPat',
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
                            'confirm_message' => 'Confirmar la Eliminación de la Equivalencia',
                            ))))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'AppBundle\Entity\EqMoviPat';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'eqmovipat_datatable';
    }

}
