<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CatGenType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('codigo', TextType::class, array(
                    "label" => 'Código',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "muycorto form-control")))
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('btcTbolCodigo', TextType::class, array(
                    "label" => 'btcTbolCodigo',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "corto form-control")))
                ->add('enuso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S',
                        'No' => 'N'),
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "muycorto form-control")))
                ->add('planOrg', TextType::class, array(
                    "label" => 'planOrg',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => " form-control")))
                ->add('codInsalud', TextType::class, array(
                    "label" => 'Código Insalud',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => " form-control")))
                ->add('desInsalud', TextType::class, array(
                    "label" => 'Descripcion Insalud',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => " form-control")))
                ->add('especialidad', TextType::class, array(
                    "label" => 'Especialidad',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => " form-control")))
                ->add('codSms', TextType::class, array(
                    "label" => 'codSms',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "corto form-control")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\CatGen'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_catgen';
    }

}
