<?php

namespace ComunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CargaInicialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tabla',TextType::class, array(
                    "label" => 'Nombre de la Tabla',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('descripcion',TextType::class, array(
                    "label" => 'Descripción',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('proceso',TextType::class, array(
                    "label" => 'Proceso de Carga',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('orden',TextType::class, array(
                    "label" => 'Nº Orden',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('modulo',EntityType::class, array(
                    "label" => 'Modulo ',
                    'class' => 'ComunBundle:Modulo',
                    'placeholder' => ' Seleccione Modulo...',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('estadoCargaInicial',EntityType::class, array(
                    "label" => 'Estado Inicial ',
                    'class' => 'ComunBundle:EstadoCargaInicial',
                    'placeholder' => ' Seleccione Estado...',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                
                 ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")
                        ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ComunBundle\Entity\CargaInicial'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'comunbundle_cargainicial';
    }


}
