<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ModOcupaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->addEventSubscriber(new EventListener\ModOcupaEventSuscribe())
                ->add('descrip', TextType::class, array(
                    "label" => 'Descripción',
                    "required" => 'required',
                    "attr" => array("class" => "form-control medio")))
                ->add('fie', TextType::class, array(
                    "label" => 'FIE',
                    "required" => 'required',
                    'attr' => array('class' => 'form-control muycorto')))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'MaestrosBundle\Entity\ModOcupa'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'maestrosbundle_modocupa';
    }

}
