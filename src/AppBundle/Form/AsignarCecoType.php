<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class AsignarCecoType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('plaza', EntityType::class, array(
                    "label" => 'Plaza',
                    "class" => 'AppBundle:Plaza',
                    "required" => 'required',
                    "attr" => array("class" => "form-control"
            )))
                ->add('codigoCalculado', TextType::class, array(
                    "label" => 'Código Calculado',
                    'required' => false ,
                    'disabled' => true ,
                    "attr" => array("class" => "form-control"
            ))) 
                ->add('cecoCalculado', EntityType::class, array(
                    "label" => 'Centro de Coste Calculado',
                    "class" => 'AppBundle:Ceco',
                    'placeholder' => ' Seleccione Centro de Coste......',
                    'required' => false ,
                    'disabled' => true ,
                    "attr" => array("class" => "form-control"
            ))) 
                ->add('cecoInformado', EntityType::class, array(
                    "label" => 'Centro de Coste Real',
                    "class" => 'AppBundle:Ceco',
                    'placeholder' => ' Seleccione Centro de Coste......',
                    "required" => false,
                    "attr" => array("class" => "form-control"
            )))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")
                        )
        );
    }

/**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\AsignarCeco'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_asignarceco';
    }

}
