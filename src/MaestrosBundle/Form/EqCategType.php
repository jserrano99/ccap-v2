<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EqCategType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('edificio', EntityType::class, array(
                    "label" => 'Edificio',
                    'class' => 'ComunBundle:Edificio',
                    'placeholder' => 'Seleccione Edificio....',
                    'query_builder' => function (\ComunBundle\Repository\EdificioRepository $er) {
                        return $er->verResto('dedificio');
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('codigoLoc', TextType::class, array(
                    "label" => 'Código Local',
                    "required" => 'required',
                    'disabled' => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('categ', EntityType::class, array(
                    "label" => 'Categoria Unificada',
                    'class' => 'MaestrosBundle:Categ',
                    'query_builder' => function (\MaestrosBundle\Repository\CategRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => true,
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
            'data_class' => 'MaestrosBundle\Entity\EqCateg'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_eqmodocupa';
    }

}
