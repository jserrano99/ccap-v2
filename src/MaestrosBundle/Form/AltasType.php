<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AltasType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->addEventSubscriber(new EventListener\AltasEventSuscribe())
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => 'required',
                    "attr" => array("class" => "form-control medio")))
                ->add('btcMconCodigo', TextType::class, array(
                    "label" => 'BTC_MCON_CODIGO',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('btcTipocon', TextType::class, array(
                    "label" => 'BTC_TIPOCON',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('subaltasAfi', ChoiceType::class, array(
                    "label" => 'subaltas Afi',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('subaltasCerti', ChoiceType::class, array(
                    "label" => 'subaltas Certi',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('certificar', ChoiceType::class, array(
                    "label" => 'Certificar',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('enuso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('motivoAltaRptId', TextType::class, array(
                    "label" => 'motivoAltaRptId',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('malRptCodigo', TextType::class, array(
                    "label" => 'malRptCodigo',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('malRptDescripcion', TextType::class, array(
                    "label" => 'malRptDescripcion',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('l13', ChoiceType::class, array(
                    "label" => 'L13',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('relJuridica', TextType::class, array(
                    "label" => 'Relacion Juridica',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('pagarTramo', ChoiceType::class, array(
                    "label" => 'Pagar Tramo',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('modOcupa', EntityType::class, array(
                    "label" => 'Modo Ocupación',
                    'class' => 'MaestrosBundle:ModOcupa',
                    'placeholder' => 'Seleccione Modo Ocupación ...',
                    'query_builder' => function (\MaestrosBundle\Repository\ModOcupaRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('modoPago', EntityType::class, array(
                    "label" => 'Modo de Pago',
                    'class' => 'MaestrosBundle:ModoPago',
                    'placeholder' => 'Seleccione Modo de Pago....',
                    'query_builder' => function (\MaestrosBundle\Repository\ModoPagoRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('moviPat', EntityType::class, array(
                    "label" => 'Patronal',
                    'class' => 'MaestrosBundle:MoviPat',
                    'placeholder' => 'Seleccione Modo de Pago....',
                    'query_builder' => function (\MaestrosBundle\Repository\MoviPatRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\Altas'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_altas';
    }

}
