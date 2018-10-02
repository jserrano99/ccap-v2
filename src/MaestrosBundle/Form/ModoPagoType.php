<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ModoPagoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->addEventSubscriber(new EventListener\ModoPagoEventSuscribe())
                ->add('descrip', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => 'required',
                    "attr" => array("class" => "form-control medio")))
                ->add('modoPagoMes', ChoiceType::class, array(
                    "label" => 'Modalidad de Pago',
                    'choices' => array('Mes Corriente' => 'A',
                        'Mes Vencido' => 'V'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ModoPago'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_modopago';
    }

}
