<?php

namespace CostesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BuscaPlazaType extends AbstractType {

    /**
     * {@inheritdoc} 
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('plaza', EntityType::class, array(
                    'label' => 'CIAS',
                    'class' => 'CostesBundle:Plaza',
                    'placeholder' => 'Seleccione código CIAS.......',
                    'required' => false,
                    'attr' => array("class" => "corto form-control")))
                ->add('plazatxt', TextType::class, array(
                    'label' => 'CÓDIGO CIAS',
                    'required' => false,
                    'attr' => array("class" => "corto form-control")))
                ->add('Buscar', SubmitType::class, array(
                    'attr' => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

}
