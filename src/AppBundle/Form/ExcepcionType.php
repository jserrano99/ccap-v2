<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExcepcionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('cecoRealInf', TextType::class, array(
                    "label" => 'Centro de Coste ',
                    'required' => false,
                    'disabled' => false,
                    'mapped' => false,
                    "attr" => array("class" => "form-control")))
                ->add('cecoReal', EntityType::class, array(
                    "label" => 'Centro de Coste Calculado',
                    "class" => 'AppBundle:Ceco',
                    'placeholder' => ' Seleccione Centro de Coste......',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('cecoExcepcionInf', TextType::class, array(
                    "label" => 'Centro de Coste ',
                    'required' => false,
                    'disabled' => false,
                    'mapped' => false,
                    "attr" => array("class" => "form-control")))
                ->add('cecoExcepcion', EntityType::class, array(
                    "label" => 'Centro de Coste Excepción',
                    "class" => 'AppBundle:Ceco',
                    'placeholder' => ' Seleccione Centro de Coste......',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success"
        )));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Excepcion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_excepcion';
    }


}
