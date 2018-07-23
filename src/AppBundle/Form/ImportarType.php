<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class ImportarType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fichero', FileType::class, array (
                                    "label" => 'Fichero',
                                    "mapped" => false,
                                    "required" => false,
                                    "attr" => array ("class" => "form-control")))
                ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "btn btn-t btn-success")))
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'importar';
    }

}
