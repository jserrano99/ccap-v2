<?php

namespace CostesBundle\Datatables;

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
 * @package CostesBundle\Datatables
 */
class PlazaDatatable extends AbstractDatatable
{

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            //'cdn_language_by_locale' => true
            'language' => 'es'
        ));

        $this->ajax->set(array());

        $this->events->set([
//            'search' =>['template' => 'search.js.twig'],
//            'processing' => ['template' => 'event.js.twig'],
        ]);

        $this->callbacks->set(array(
            'row_callback' => array(
                'template' => 'row_callback.js.twig'),
            'state_loaded' => array(
                'template' => 'init.js.twig'),
            'init_complete' => array(
                'template' => 'init.js.twig')));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => ['strip1', 'strip2', 'strip3'],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
            'order_classes' => true,
            'search_in_non_visible_columns' => true,
            'dom' => 'lBrftip',
            'length_menu' => array('10', '25', '75', '100', '5000')
        ));

        $this->extensions->set(array(
            'buttons' => array(
                'show_buttons' => array('copy'),
                'create_buttons' => array(
                    array(
                        'extend' => 'excel',
                        'text' => 'Exportar Excel',
                        'button_options' => array(
                            'exportOptions' => array(
                                'search' => 'none'
                            )
                        )
                    ),
                    array(
                        'extend' => 'pdf',
                        'text' => 'Pdf',
                        'button_options' => array(
                            'exportOptions' => array(
                                'columns' => array('1', '2', '3', '4', '5', '6', '7', '8'),
                            ),
                            'orientation' => 'landscape'
                        )
                    )
                )
            )
        ));

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change' => true,
            'state_save' => false
        ));

        $UfAll = $this->getEntityManager()->getRepository('CostesBundle:Uf')->createQueryBuilder('u')
            ->orderBy('u.descripcion', 'ASC')
            ->where("u.enuso = 'S'")
            ->getQuery()->getResult();

        $PaAll = $this->getEntityManager()->getRepository('CostesBundle:Pa')->createQueryBuilder('u')
            ->orderBy('u.descripcion', 'ASC')
            ->where("u.enuso = 'S'")
            ->getQuery()->getResult();
        $CatGenAll = $this->getEntityManager()->getRepository('MaestrosBundle:CatGen')->createQueryBuilder('u')
            ->orderBy('u.descripcion', 'ASC')
            ->where("u.enuso = 'S'")
            ->getQuery()->getResult();

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                'width' => '25px'))
            ->add('cias', Column::class, array(
                'title' => 'CIAS',
                'width' => '95px',
                'filter' => array(TextFilter::class, array(
                    'cancel_button' => false
                ))))
            ->add('uf.oficial', Column::class, array(
                'title' => 'Código',
                'width' => '65px'))
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
                'width' => '65px'))
            ->add('pa.descripcion', Column::class, array(
                'title' => 'Punto Asistencial',
                'width' => '220px',
                'filter' => array(SelectFilter::class,
                    array(
                        'multiple' => false,
                        'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($PaAll, 'descripcion', 'descripcion'),
                        'search_type' => 'eq'))))
            ->add('cecoActual.codigo', Column::class, array(
                'title' => 'Ceco Actual',
                'width' => '65px',
                'default_content' => ''))
            ->add('catGen.descripcion', Column::class, array(
                'title' => 'Cat. General',
                'width' => '220px',
                'filter' => array(SelectFilter::class,
                    array(
                        'multiple' => false,
                        'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($CatGenAll, 'descripcion', 'descripcion'),
                        'search_type' => 'eq'))))
            ->add('amortizada', Column::class, array(
                'title' => 'Amortizada',
                'width' => '45px',
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
            ->add('fAmortiza', DateTimeColumn::class, array('title' => 'Fecha Amortización', 'width' => '150px',
                'date_format' => 'DD/MM/YYYY',
                'default_content' => '',
                'filter' => array(DateRangeFilter::class, array(
                    'cancel_button' => false,
                )),
            ))
            ->add('sincroLog.estado.descripcion', Column::class, array(
                'title' => 'Estado Sincronización',
                'width' => '220px',
                'default_content' => ''))
            ->add(null, ActionColumn::class, array(
                'title' => 'Acciones',
                'actions' => array(
                    array('route' => 'editPlaza',
                        'route_parameters' => array('id' => 'id'),
                        'label' => 'Editar',
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array('rel' => 'tooltip',
                            'title' => 'Editar Plaza',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button')),
                    array('route' => 'amortizacionPlaza',
                        'route_parameters' => array('id' => 'id'),
                        'label' => 'Amortizar',
                        'icon' => 'glyphicon glyphicon-erase',
                        'render_if' => function ($row) {
                            if ($row['amortizada'] == 'N')
                                return true;
                        },
                        'attributes' => array('rel' => 'tooltip',
                            'title' => 'Amortizar Plaza',
                            'class' => 'btn btn-danger btn-xs',
                            'role' => 'button')),
                    array('route' => 'descargaLogPlaza',
                        'route_parameters' => array('id' => 'id'),
                        'label' => 'Logs',
                        'icon' => 'glyphicon glyphicon-download-alt',
                        'render_if' => function ($row) {
                            if ($row['sincroLog'] != null)
                                return true;
                        },
                        'attributes' => array('rel' => 'tooltip',
                            'title' => 'Logs',
                            'class' => 'btn btn-warning btn-xs',
                            'role' => 'button'))
                )));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'CostesBundle\Entity\Plaza';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'plaza_datatable';
    }

}
