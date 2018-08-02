<?php

namespace ComunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DependenciaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cargaInicial',EntityType::class, array(
                    "label" => 'Tabla Dependiente',
                    'class' => 'ComunBundle:CargaInicial',
                    'placeholder' => ' Seleccione Tabla...',
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
            'data_class' => 'ComunBundle\Entity\Dependencia'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'comunbundle_dependencia';
    }


}
