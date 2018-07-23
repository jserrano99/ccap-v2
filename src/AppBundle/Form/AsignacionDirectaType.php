<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AsignacionDirectaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codigoUf78', TextType::class, array(
                    "label" => 'Tipo de Puesto',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('cecoInf', TextType::class, array(
                    "label" => 'Centro de Coste ',
                    'required' => false,
                    'disabled' => false,
                    'mapped' => false,
                    "attr" => array("class" => "form-control")))
                ->add('ceco', EntityType::class, array(
                    "label" => 'Centro de Coste ',
                    "class" => 'AppBundle:Ceco',
                    'placeholder' => ' Seleccione Centro de Coste......',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    'required' => false,
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
            'data_class' => 'AppBundle\Entity\AsignacionDirecta'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_asignaciondirecta';
    }

}
