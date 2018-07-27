<?php

namespace ComunBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BaseDatosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('alias',TextType::class, array(
                    "label" => 'Alias de Base De datos',
                    "required" => false,
                    "attr" => array("class" => "form-control")))
                ->add('maquina',TextType::class, array(
                    "label" => 'Máquina',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('puerto',TextType::class, array(
                    "label" => 'Nº de Puerto',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('servidor',TextType::class, array(
                    "label" => 'Alias ',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('esquema',TextType::class, array(
                    "label" => 'Esquema ',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('usuario',TextType::class, array(
                    "label" => 'Usuario',
                    "required" => false,
                    "attr" => array("class" => "form-control")))
                ->add('password',TextType::class, array(
                    "label" => 'Password ',
                    "required" => false,
                    "attr" => array("class" => "form-control")))
                ->add('tipoBaseDatos',EntityType::class, array(
                    "label" => 'Alias ',
                    'class' => 'ComunBundle:TipoBaseDatos',
                    'placeholder' => ' Seleccione Entorno de Base de Datos ...',
                    "required" => false,
                    "attr" => array("class" => "form-control")))
                ->add('edificio',EntityType::class, array(
                    "label" => 'Edificio ',
                    'class' => 'ComunBundle:Edificio',
                    'placeholder' => ' Seleccione Edificio...',
                    "required" => true,
                    "attr" => array("class" => "form-control")))
                ->add('activa', ChoiceType::class, array(
                    "label" => 'Activa',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
           
                 ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")
                        ));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ComunBundle\Entity\BaseDatos'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'comunbundle_basedatos';
    }


}
