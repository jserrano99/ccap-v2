<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MoviPatType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('codigo', TextType::class, array(
                    "label" => 'Código',
                    "required" => 'required',
                    "attr" => array("class" => "form-control muycorto")))
                ->add('descrip', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('cif', TextType::class, array(
                    "label" => 'CIf',
                    "required" => false,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('numeroSeg', TextType::class, array(
                    "label" => 'Código Cuenta Cotización',
                    "required" => false,
                    "attr" => array("class" => "form-control corto")))
                ->add('clave', TextType::class, array(
                    "label" => 'Clave',
                    "required" => false,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('patContin', MoneyType::class, array(
                    "label" => 'Contingencias Comunes',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patHe', MoneyType::class, array(
                    "label" => 'Horas Extras',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patAcc', MoneyType::class, array(
                    "label" => 'Accidentes',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patFp', MoneyType::class, array(
                    "label" => 'Formación Profesional',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patMunpal', MoneyType::class, array(
                    "label" => 'MUNPAL',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('fogasa', MoneyType::class, array(
                    "label" => 'FOGASA',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patIntegra', MoneyType::class, array(
                    "label" => 'Integra',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('patAccAnt', MoneyType::class, array(
                    "label" => 'Accidentes Anterior',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('obrHe', MoneyType::class, array(
                    "label" => 'Horas Extras',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('obrContin', MoneyType::class, array(
                    "label" => 'Contingencias Comunes',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('obrAcc', MoneyType::class, array(
                    "label" => 'Accidentes',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('obrFp', MoneyType::class, array(
                    "label" => 'Formación Profesional',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('obrMunpal', MoneyType::class, array(
                    "label" => 'CUOTA OBRERA MUNPAL',
                    "required" => false,
                    'attr' => array('class' => 'form-control corto')))
                ->add('empresa', TextType::class, array(
                    "label" => 'Empresa',
                    "required" => false,
                    "attr" => array("class" => "form-control medio")))
                ->add('enuso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('eventual', ChoiceType::class, array(
                    "label" => 'Eventual',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('porcent', MoneyType::class, array(
                    "label" => 'Porcentaje',
                    "required" => false,
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('forzarL00', ChoiceType::class, array(
                    "label" => 'Forzar L00',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\MoviPat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_movipat';
    }

}
