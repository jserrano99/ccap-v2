<?php

namespace CostesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AmortizacionPlazaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('cias', TextType::class, array(
                    "label" => 'Identificador de Plaza ',
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "form-control muycorto")))
                ->add('catGen', EntityType::class, array(
                    "label" => 'Categoría General',
                    'class' => 'MaestrosBundle:CatGen',
                    'placeholder' => 'Seleccione Categoría General....',
                    'query_builder' => function (\MaestrosBundle\Repository\CatGenRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "form-control")))
                ->add('catFp', EntityType::class, array(
                    "label" => 'Categoría Fp',
                    'class' => 'MaestrosBundle:CatFp',
                    'placeholder' => 'Seleccione Categoría ....',
                    'query_builder' => function (\MaestrosBundle\Repository\CatFpRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => true,
                    "attr" => array("class" => "form-control")))
                ->add('observaciones', TextType::class, array(
                    "label" => 'Observaciones ',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('uf', EntityType::class, array(
                    "label" => 'Unidad Funcional',
                    'class' => 'CostesBundle:Uf',
                    'placeholder' => 'Seleccione Unidad Funcional ...',
                    'query_builder' => function (\CostesBundle\Repository\UfRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "medio form-control")))
                ->add('pa', EntityType::class, array(
                    "label" => 'Punto Asistencial',
                    'class' => 'CostesBundle:Pa',
                    'placeholder' => 'Seleccione Punto Asistencial ...',
                    'query_builder' => function (\CostesBundle\Repository\PaRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "medio form-control")))
                ->add('fAmortiza', DateType::class, array(
                    "label" => 'Fecha Amortización',
                    "required" => true,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string',))) 
                ->add('fCreacion', DateType::class, array(
                    "label" => 'Fecha Creación',
                    "required" => false,
                    "disabled" => true,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string',)))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'CostesBundle\Entity\Plaza'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'costesbundle_amortizacionplaza';
    }

}
