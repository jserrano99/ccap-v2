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
 * Class PaDatatable
 *
 * @package CostesBundle\Datatables
 */
class PaDatatable extends AbstractDatatable {

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


        /*
          $this->callbacks->set(array(
          'init_complete' => array(
          'template' => 'pa/init.js.twig',
          ),
          ));

          $this->events->set(array(
          'xhr' => array(
          'template' => 'pa/event.js.twig',
          'vars' => array('table_name' => $this->getName()),
          ),
          ));
         */
        $edificios = $this->em->getRepository('ComunBundle:Edificio')->findAll();
        $das = $this->em->getRepository('ComunBundle:Da')->findAll();

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true
        ));


        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id',
                    'width' => '20px',
                    'searchable' => false
                ))
                ->add('pa', Column::class, array(
                    'title' => 'Código',
                    'width' => '40px',
                ))
                ->add('descripcion', Column::class, array(
                    'title' => 'Descripción',
                ))
                ->add('oficial', Column::class, array(
                    'title' => 'Código Oficial',
                ))
                ->add('da.descripcion', Column::class, array(
                    'title' => 'Dirección Asistencial',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($das, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('edificio.descripcion', Column::class, array(
                    'title' => 'Edificio',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('enuso', Column::class, array(
                    'title' => 'Uso',
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
                ->add('sincroLog.estado.descripcion', Column::class, array(
                    'title' => 'Log',
                    'width' => '120px',
                    'default_content' => ''))
                
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'editPa',
                            'route_parameters' => array(
                                'id' => 'id'),
                            'label' => 'Editar',
                            'icon' => 'glyphicon glyphicon-edit',
                            'attributes' => array(
                                'rel' => 'tooltip',
                                'title' => 'Editar Punto Asistencial',
                                'class' => 'btn btn-primary btn-xs',
                                'role' => 'button'
                            )),
                        array('route' => 'descargaLogPa',
                            'route_parameters' => array('id' => 'id'),
                            'label' => 'Logs',
                            'icon' => 'glyphicon glyphicon-edit',
                            'render_if' => function ($row) {
                                if ($row['sincroLog'] != null)
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
        return 'CostesBundle\Entity\Pa';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'pa_datatable';
    }

}
