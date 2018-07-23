<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter; 


/**
 * Class BasedatosDatatable
 *
 * @package AppBundle\Datatables
 */
class BaseDatosDatatable extends AbstractDatatable {

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
        $edificios = $this->em->getRepository('AppBundle:Edificio')->findAll();
        $das = $this->em->getRepository('AppBundle:Da')->findAll();

        $this->features->set(array(
            'auto_width' => true,
            'ordering' => true,
            'length_change'=> true
        ));

        $edificios = $this->em->getRepository('AppBundle:Edificio')->findAll();
        $tipoBaseDatosALL = $this->em->getRepository('AppBundle:TipoBaseDatos')->findAll();

        $this->columnBuilder
                ->add('id', Column::class, array(
                    'title' => 'Id', 'width' => '20px', 'searchable' => false
                ))
                ->add('alias', Column::class, array(
                    'title' => 'Alias','width' => '20px'
                ))
                ->add('maquina', Column::class, array( 
                    'title' => 'Host','width' => '40px'
                ))
                ->add('puerto', Column::class, array(
                    'title' => 'Puerto', 'width' => '20px'
                ))
                ->add('servidor', Column::class, array(
                    'title' => 'Servidor', 'width' => '40px'
                ))
                ->add('esquema', Column::class, array(
                    'title' => 'esquema', 'width' => '40px'
                ))
                ->add('tipoBaseDatos.descripcion', Column::class, array(
                    'title' => 'Tipo Base Datos',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($tipoBaseDatosALL, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('edificio.descripcion', Column::class, array(
                    'title' => 'Edificio',
                    'filter' => array(SelectFilter::class,
                        array(
                            'multiple' => false,
                            'select_options' => array('' => 'Todo') + $this->getOptionsArrayFromEntities($edificios, 'descripcion', 'descripcion'),
                            'search_type' => 'eq'))))
                ->add('activa', Column::class, array(
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
                ->add(null, ActionColumn::class, array(
                    'title' => 'Acciones',
                    'actions' => array(
                        array(
                            'route' => 'editBaseDatos',
                            'route_parameters' => array(
                                'id' => 'id'),
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
        return 'AppBundle\Entity\BaseDatos';
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'basedatos_datatable';
    }

}
