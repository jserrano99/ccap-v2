<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FcoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->addEventSubscriber(new EventListener\FcoEventSuscribe())
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('fcorptid', TextType::class, array(
                    "label" => 'fcorptid',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('fcorptCodigo', TextType::class, array(
                    "label" => 'fcorptCodigo',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('fcorptDescripcion', TextType::class, array(
                    "label" => 'fcorptDescripcion',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('enuso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S','No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('propietario', ChoiceType::class, array(
                    "label" => 'Propietario',
                    'choices' => array('Si' => 'S','No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control corto")))
                
                ->add('soliOrigen', ChoiceType::class, array(
                    "label" => 'SoliOrigen',
                    'choices' => array('No' => 'N','Si' => 'S'),
                    'required' => true,
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
            'data_class' => 'MaestrosBundle\Entity\Fco'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_fco';
    }

}
