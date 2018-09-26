<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AusenciaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->addEventSubscriber(new EventListener\AusenciaEventSuscribe())
                ->add('descrip', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => true,
                    "attr" => array("class" => "form-control medio")))
                ->add('enUso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S','No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('modOcupa', EntityType::class, array(
                    "label" => 'Modo Ocupación del Suplente',
                    'class' => 'MaestrosBundle:ModOcupa',
                    'placeholder' => 'Seleccione Modo Ocupación Suplente...',
                    'query_builder' => function (\MaestrosBundle\Repository\ModOcupaRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('fco', EntityType::class, array(
                    "label" => 'Forma Cobertura',
                    'class' => 'MaestrosBundle:Fco',
                    'placeholder' => 'Seleccione Forma Cobertura...',
                    'query_builder' => function (\MaestrosBundle\Repository\FcoRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('ocupacion', EntityType::class, array(
                    "label" => 'Ocupación (Valido del 01/07/2007 al 31/12/2009) ',
                    'class' => 'MaestrosBundle:Ocupacion',
                    'placeholder' => 'Seleccione Ocupación...',
                    'query_builder' => function (\MaestrosBundle\Repository\OcupacionRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control"))) 
                ->add('janoDocumPermiso', EntityType::class, array(
                    "label" => 'Url Documentación',
                    'class' => 'MaestrosBundle:DocumPermiso',
                    'placeholder' => 'Seleccione Url...',
                    'query_builder' => function (\MaestrosBundle\Repository\DocumPermisoRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('ocupacionNew', EntityType::class, array(
                    "label" => 'Ocupación (a partir del 01/01/2010)',
                    'class' => 'MaestrosBundle:Ocupacion',
                    'placeholder' => 'Seleccione Ocupación...',
                    'query_builder' => function (\MaestrosBundle\Repository\OcupacionRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('epiAcc', EntityType::class, array(
                    "label" => 'Epigrafe',
                    'class' => 'MaestrosBundle:EpiAcc',
                    'placeholder' => 'Seleccione Epigrafe ...',
                    'query_builder' => function (\MaestrosBundle\Repository\EpiAccRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('tipoIlt', EntityType::class, array(
                    "label" => 'Tipo I.T',
                    'class' => 'MaestrosBundle:TipoIlt',
                    'placeholder' => 'Seleccione Tipo Incapacidad Temporal ...',
                    'query_builder' => function (\MaestrosBundle\Repository\TipoIltRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('moviPat', EntityType::class, array(
                    "label" => 'Patronal',
                    'class' => 'MaestrosBundle:MoviPat',
                    'placeholder' => 'Seleccione Cód. Cuenta Cotización....',
                    'query_builder' => function (\MaestrosBundle\Repository\MoviPatRepository $er) {return $er->createAlphabeticalQueryBuilder();},
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('sindicato', ChoiceType::class, array(
                    "label" => 'Sindicato',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('naturales', ChoiceType::class, array(
                    "label" => 'Naturales Titular',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('naturalesEv', ChoiceType::class, array(
                    "label" => 'Naturales Eventual',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('contador', ChoiceType::class, array(
                    "label" => 'Contador',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('maxAnual', NumberType::class, array(
                    'label' => 'Máximo Anual',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('maxTotal', NumberType::class, array(
                    'label' => 'Máximo Total',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('redondeo', NumberType::class, array(
                    'label' => 'Redondeo',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('ctrlHorario', ChoiceType::class, array(
                    "label" => 'Control Horario',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('otrosPerm', ChoiceType::class, array(
                    "label" => 'Otros Permisos',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('absentismo', ChoiceType::class, array(
                    "label" => 'Absentismo',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('autog', ChoiceType::class, array(
                    "label" => 'Autogestión',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('autogDesde', NumberType::class, array(
                    'label' => 'Desde Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('autogHasta', NumberType::class, array(
                    'label' => 'Hasta Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('pagotit', ChoiceType::class, array(
                    "label" => 'Pago Titular',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('justificar', ChoiceType::class, array(
                    "label" => 'Justificar',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('justificanteDias', NumberType::class, array(
                    'label' => 'Plazo Justificante',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
                ->add('predecible', ChoiceType::class, array(
                    "label" => 'Predecible',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cotizass', ChoiceType::class, array(
                    "label" => 'Cotiza S.S.',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('descuTrienios', ChoiceType::class, array(
                    "label" => 'Retrasa Trienios',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('proporcional', ChoiceType::class, array(
                    "label" => 'Proporcional',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('huelga', ChoiceType::class, array(
                    "label" => 'Huelga',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('calculoFfin', ChoiceType::class, array(
                    "label" => 'Cálculo F.Fin',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('guarda', ChoiceType::class, array(
                    "label" => 'Guarda Legal',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cuentaTurnic', ChoiceType::class, array(
                    "label" => 'Cuenta Turnicidad',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cuentaPago', ChoiceType::class, array(
                    "label" => 'Cuenta Pago',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('dtrab', ChoiceType::class, array(
                    "label" => 'Contabilizar como días trabajados Certificados',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('dtrabperm', ChoiceType::class, array(
                    "label" => 'Contabilizar como días trabajados Ausencias',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('csituadm', ChoiceType::class, array(
                    "label" => 'Sit. Adm',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('esIt', ChoiceType::class, array(
                    "label" => 'I.T',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('a22', ChoiceType::class, array(
                    "label" => 'Variación A22',
                    'choices' => array('Sin Cambio' => 'N', 'Cambio' => 'C', 'Baja' => 'B', 'Alta' => 'A', 'Variación' => 'V'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('red', NumberType::class, array(
                    'label' => 'Cód. RED',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('porcen1', NumberType::class, array(
                    'label' => 'Porcentaje',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('didesde1', NumberType::class, array(
                    'label' => 'Desde Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('dihasta1', NumberType::class, array(
                    'label' => 'Hasta Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('porcen2', NumberType::class, array(
                    'label' => 'Porcentaje',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('didesde2', NumberType::class, array(
                    'label' => 'Desde Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('dihasta2', NumberType::class, array(
                    'label' => 'Hasta Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('porcen3', NumberType::class, array(
                    'label' => 'Porcentaje',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('didesde3', NumberType::class, array(
                    'label' => 'Desde Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('dihasta3', NumberType::class, array(
                    'label' => 'Hasta Día',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('persinsu', ChoiceType::class, array(
                    "label" => 'Permiso Sin Sueldo',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('reserva', ChoiceType::class, array(
                    "label" => 'Reserva de Puesto',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('durReserva', NumberType::class, array(
                    'label' => 'Duración Reserva (Meses)',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('mejoraIt', ChoiceType::class, array(
                    "label" => 'Mejora de IT',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('itinerancia', ChoiceType::class, array(
                    "label" => 'Dto en Itinerancia',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('afectaRevision', ChoiceType::class, array(
                    "label" => 'Tener en Cuenta esta Ausencia para la Nómina de Revisión',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('destino', ChoiceType::class, array(
                    "label" => 'Cambio Destino',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('porcenIt', NumberType::class, array(
                    'label' => 'Porcentaje IT',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('finRed', TextType::class, array(
                    'label' => 'Cód. RED 2',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('tipoInactividad', ChoiceType::class, array(
                    "label" => 'Tipo Inactividad',
                    'choices' => array('Permiso Sin Sueldo' => 7,
                        'Huelga Total' => 2,
                        'Huelga Parcial' => 3),
                    'placeholder' => 'Seleccione Tipo Inactividad ...',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('reduccion', ChoiceType::class, array(
                    "label" => 'Reducción de Jornada',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cambiopuesto', ChoiceType::class, array(
                    "label" => 'Cambio de Puesto',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('excluirPlpage', ChoiceType::class, array(
                    "label" => 'No aportación al plan de pensiones',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cambiogrc',TextType::class, array(
                    'label' => 'cambiogrc',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('btcTipoCon',TextType::class, array(
                    'label' => 'btctipocon)',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('codigonom',TextType::class, array(
                    'label' => 'codigonom)',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('idbasescon',TextType::class, array(
                    'label' => 'idbasescon',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('cambiosgrc',TextType::class, array(
                    'label' => 'cambiosgrc',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('mapturnos',NumberType::class, array(
                    'label' => 'mapturnos',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('maxTotalH',NumberType::class, array(
                    'label' => 'maxTotalH',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('maxAnualH',NumberType::class, array(
                    'label' => 'masAnualH',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('ausenciasrptid',NumberType::class, array(
                    'label' => 'ausenciasrptid',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('ausrptCodigo',NumberType::class, array(
                    'label' => 'ausrptCodigo',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('ausrptDescripcion',NumberType::class, array(
                    'label' => 'ausrptDescripcion',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('turnos',TextType::class, array(
                    'label' => 'turnos',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('txtab',TextType::class, array(
                    'label' => 'txtab',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('ctact',TextType::class, array(
                    'label' => 'ctact',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoApartado', TextType::class, array(
                    'label' => 'Apartado',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoApd', TextType::class, array(
                    'label' => 'Apd',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoConn2annos', ChoiceType::class, array(
                    "label" => 'Valido Para 2 Años',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoDescripcion', TextType::class, array(
                    'label' => 'Descripción',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control medio')))
                ->add('janoNombrelargo', TextType::class, array(
                    'label' => 'Nombre Largo',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control medio')))
                ->add('janoDescripseg', TextType::class, array(
                    'label' => 'Descripción Seg',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control medio')))
               ->add('janoFeclimant', DateType::class, array(
                    "label" => 'Fecha Límite',
                    "required" => false,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string',)))             
                ->add('janoDldold', ChoiceType::class, array(
                    "label" => 'Dias Libre Disposición Antigüedad',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoEnHoras', ChoiceType::class, array(
                    "label" => 'En Horas',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoFecfinAbierta', ChoiceType::class, array(
                    "label" => 'Fecha Fin Abierta',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoGrado', ChoiceType::class, array(
                    "label" => 'Afecta Grado Parentesco',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoGrautoriza', ChoiceType::class, array(
                    "label" => 'Validación Automática',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoLocalidad', ChoiceType::class, array(
                    "label" => 'Afecta Localidad',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoMaxadelanto', NumberType::class, array(
                    'label' => 'Máximo Días de Adelanto',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoMaxlab', NumberType::class, array(
                    'label' => 'Máximo Días Laborables',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoMaxnat', NumberType::class, array(
                    'label' => 'Máximo Días Naturales',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoMir', ChoiceType::class, array(
                    "label" => 'Valido para MIR',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoSar', ChoiceType::class, array(
                    "label" => 'Valido para SAR',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoResponsable', ChoiceType::class, array(
                    "label" => 'Responsable',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoResto', ChoiceType::class, array(
                    "label" => 'Todos',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoUsuario', ChoiceType::class, array(
                    "label" => 'Usuario',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('janoSumaDiasCont', NumberType::class, array(
                    'label' => 'Añadir dias Permiso',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoSumaDiasDisc', NumberType::class, array(
                    'label' => 'Añadir dias Permiso por Discapacidad',
                    'required' => false,
                    'disabled' => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('janoVarold', ChoiceType::class, array(
                    "label" => 'Vacaciones por Antigüedad',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))


        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\Ausencia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_ausencia';
    }

}
