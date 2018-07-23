<?php

namespace AppBundle\Datatables;

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
 * Class CatGenDatatable
 *
 * @package AppBundle\Datatables
 */
class PlazaDatatable extends AbstractDatatable {

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array()) {
        $this->language->set(array(
            'cdn_language_by_locale' => true
        ));

        $this->ajax->set(array(
        ));

//        $this->callbacks->set(array(
//            'row_callback' => array(
//                'template' => 'plaza/row_callback.js.twig'),
//            'init_complete' => array(
//                'template' => 'plaza/init.js.twig')));
//
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

        $UfAll = $this->getEntityManager()->getRepository('AppBundle:Uf')->createQueryBuilder('u')
                        ->orderBy('u.descripcion', 'ASC')
                        ->where("u.enuso = 'S'")
                        ->getQuery()->getResult();

        $PaAll = $this->getEntityManager()->getRepository('AppBundle:Pa')->createQueryBuilder('u')
                        ->orderBy('u.descripcion', 'ASC')
                        ->where("u.enuso = 'S'")
                        ->getQuery()->getResult();

        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id',
                    'width' => '25px'))
                ->add('cias', Column::class, array(
                    'title' => 'CIAS',
                    'width' => '25px'))
                ->add('uf.oficial', Column::class, array(
                    'title' => 'Código',
                    'width' => '25px'))
                ->add('uf.descripcion', Column::class, array(
                    'title' => 'Unidad Funcional',
                    'width' => '220px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($UfAll, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('pa.oficial', Column::class, array(
                    'title' => 'Código',
                    'width' => '25px'))
                ->add('pa.descripcion', Column::class, array(
                    'title' => 'Punto Asistencial',
                    'width' => '220px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($PaAll, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('ceco.codigo', Column::class, array(
                    'title' => 'CECO',
                    'width' => '25px',
                    'default_content' => ''))
                ->add('amortizada', Column::class, array(
                    'title' => 'Amortizada',
                    'width' => '20px',
                    'filter' => array(SelectFilter::class,
                        array(
                            'search_type' => 'eq',
                            'multiple' => false,
                            'select_options' => array(
                                '' => 'Todo',
                                'S' => 'Si',
                                'N' => 'No',
                            ),
                            'cancel_button' => false,
                            'initial_search' => 'N',
                        ),
                    ),
                ))
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(array('route' => 'editPlaza',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array('rel' => 'tooltip',
                                'title' => 'Editar Plaza',
                                'target' => '_blank',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button')))))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity() {
        return 'AppBundle\Entity\Plaza';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'plaza_datatable';
    }

}
