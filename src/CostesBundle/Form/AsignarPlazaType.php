<?php

namespace CostesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AsignarPlazaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm (FormBuilderInterface $builder, array $options) {
        $builder->add('cias', TextType::class, [
                    "label" => 'Cias',
                    'required' => true,
                    'disabled' => false,
                    "attr" => ["class" => "form-control corto"]])

	        ->add('Guardar', SubmitType::class, [
                    "attr" => ["class" => "form-submit btn btn-t btn-success"]])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'asignarPlaza';
    }

}
