<?php

namespace CostesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('oficial',TextType::class, array(
                    "label" => 'Código Oficial',
                    'required' => true ,
                    'disabled' => false ,
                    "attr" => array("class" => "corto form-control"))) 
                ->add('pa',TextType::class, array(
                    "label" => 'Código',
                    'required' => false ,
                    'disabled' => false ,
                    "attr" => array("class" => " muycorto form-control"))) 
                ->add('fechaCreacion', DateType::class, array(
                    "label" => 'Fecha Creación',
                    "required" => true,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string')))
                ->add('descripcion',TextType::class, array(
                    "label" => 'Descripción',
                    'required' => true ,
                    'disabled' => false ,
                    "attr" => array("class" => "form-control"))) 
                
                ->add('edificio',EntityType::class, array(
                    "label" => 'Edificio',
                    'class' => 'ComunBundle:Edificio',
                    'placeholder' => 'Seleccione el edificio....',
                    'required' => false ,
                    'disabled' => false ,
                    "attr" => array("class" => "corto form-control"))) 
                ->add('da',EntityType::class, array(
                    "label" => 'Dirección Asistencial ',
                    'class' => 'ComunBundle:Da',
                    'placeholder' => 'Seleccione la Dirección Asistencial....',
                    'required' => false ,
                    'disabled' => false ,
                    "attr" => array("class" => "medio form-control"))) 
                ->add('descripcion',TextType::class, array(
                    "label" => 'Descripción',
                    'required' => true ,
                    'disabled' => false ,
                    "attr" => array("class" => "form-control"))) 
                ->add('enuso',ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S',
                                       'No' => 'N'),
                    'required' => true ,
                    'disabled' => false ,
                    "attr" => array("class" => "muycorto form-control"))) 
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success"
            )));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CostesBundle\Entity\Pa'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pa';
    }


}
