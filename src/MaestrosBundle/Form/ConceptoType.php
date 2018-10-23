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
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class ConceptoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->addEventSubscriber(new EventListener\ConceptoEventSuscribe())
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => true,
                    "attr" => array("class" => "form-control medio")))
                ->add('irpf', ChoiceType::class, array(
                    "label" => 'IRPF',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('segsoc', ChoiceType::class, array(
                    "label" => 'Cotiza Seg. Social',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('extra', ChoiceType::class, array(
                    "label" => 'Paga Extra',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('vacac', ChoiceType::class, array(
                    "label" => 'Vacaciones',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('Importe', MoneyType::class, array(
                    "label" => 'Importe',
                    "attr" => array("class" => "form-control corto")))
                ->add('tipo', ChoiceType::class, array(
                    "label" => 'Tipo ',
                    'choices' => array('Abono' => '+', 'Deducción' => '-'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('acum', ChoiceType::class, array(
                    "label" => 'Acumula',
                    'choices' => array('Abono' => '+', 'Deducción' => '-'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('incre', ChoiceType::class, array(
                    "label" => 'Incrementa',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('huelga', ChoiceType::class, array(
                    "label" => 'Huelga',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('devengo', ChoiceType::class, array(
                    "label" => 'Devengo',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('clave190', TextType::class, array(
                    "label" => 'Clave 190',
                    "required" => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('tipoConcepto', ChoiceType::class, array(
                    "label" => 'Tipo Concepto',
                    'choices' => array('C' => 'C', 'T' => 'T'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cupoAcu', ChoiceType::class, array(
                    "label" => 'Cupo Acu',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cupoCd', ChoiceType::class, array(
                    "label" => 'Cupo CD',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('retJudicial', ChoiceType::class, array(
                    "label" => 'Retención Judicial',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('trienioCupo', ChoiceType::class, array(
                    "label" => 'Trienio Cupo',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('recuperaIt', ChoiceType::class, array(
                    "label" => 'Recupera IT',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('porcentajExtra', PercentType::class, array(
                    "label" => '% Extra',
                    "required" => true,
                    "scale" => 2,
                    "type" => "integer",
                    "attr" => array("class" => "form-control muycorto")))
                ->add('gasto173', ChoiceType::class, array(
                    "label" => 'Gasto 173',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('mayorCarga', ChoiceType::class, array(
                    "label" => 'Mayor Carga',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('mayorCargagrc', ChoiceType::class, array(
                    "label" => 'Mayor Carga GRC',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('sabados', ChoiceType::class, array(
                    "label" => 'Sabados',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('variableIrpf', ChoiceType::class, array(
                    "label" => 'variable IRPF',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('mejoraIt', ChoiceType::class, array(
                    "label" => 'Mejora IT',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cobraEnExtra', ChoiceType::class, array(
                    "label" => 'Cobra en Extra',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('conceptoRptCodigo', TextType::class, array(
                    "label" => 'ConceptoRpt Codigo',
                    "required" => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('conRptDescripcion', TextType::class, array(
                    "label" => 'ConceptoRpt Descripcion ',
                    "required" => true,
                    "attr" => array("class" => "form-control medio")))
                ->add('conceptoRptId', TextType::class, array(
                    "label" => 'ConceptoRpt Id',
                    "required" => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('porcenExtraAnt', PercentType::class, array(
                    "label" => '% Extra Ant',
                    "required" => true,
                    "scale" => 2,
                    "type" => "integer",
                    "attr" => array("class" => "form-control muycorto")))
                ->add('excRetencion', ChoiceType::class, array(
                    "label" => 'Exc. retención',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('variableDecre', ChoiceType::class, array(
                    "label" => 'Variable Decre',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('integroMit', ChoiceType::class, array(
                    "label" => 'Integro Mit',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('salario', ChoiceType::class, array(
                    "label" => 'Salario',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('complemento', ChoiceType::class, array(
                    "label" => 'Complemento',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('atContinuada', ChoiceType::class, array(
                    "label" => 'At. Continuada',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('turnicidad', ChoiceType::class, array(
                    "label" => 'Turnicidad',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('descuentaIt', ChoiceType::class, array(
                    "label" => 'Descuenta IT',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('codigocre', TextType::class, array(
                    "label" => 'codigocre',
                    "required" => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('enEspecie', ChoiceType::class, array(
                    "label" => 'En Especie',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('reduccion', PercentType::class, array(
                    "label" => '% Reducción',
                    "required" => true,
                    "scale" => 2,
                    "type" => "integer",
                    "attr" => array("class" => "form-control muycorto")))
                ->add('it190', ChoiceType::class, array(
                    "label" => 'it190',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('rbmuface', ChoiceType::class, array(
                    "label" => 'Rb Muface',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('rbmuface2', ChoiceType::class, array(
                    "label" => 'Rb Muface2',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('descanso', ChoiceType::class, array(
                    "label" => 'Descanso',
                    'choices' => array('No' => 'N', 'Si' => 'S'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control sino")))
                ->add('cecoConcepto', TextType::class, array(
                    "label" => 'Ceco Concepto',
                    "required" => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\Concepto'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_ausencia';
    }

}
