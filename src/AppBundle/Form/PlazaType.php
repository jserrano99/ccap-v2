<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PlazaType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('catGen', EntityType::class, array(
                    "label" => 'Categoría General',
                    'class' => 'AppBundle:CatGen',
                    'placeholder' => 'Seleccione Categoría General....',
                    'query_builder' => function (\AppBundle\Repository\CatGenRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('catFp', EntityType::class, array(
                    "label" => 'Categoría Fp',
                    'class' => 'AppBundle:CatFp',
                    'placeholder' => 'Seleccione Categoría ....',
                    'query_builder' => function (\AppBundle\Repository\CatFpRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('ceco', EntityType::class, array(
                    "label" => 'Centro de Coste',
                    'class' => 'AppBundle:Ceco',
                    'placeholder' => 'Seleccione Centro de Coste ....',
                    'query_builder' => function (\AppBundle\Repository\CecoRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('observaciones', TextType::class, array(
                    "label" => 'Observaciones ',
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('uf', EntityType::class, array(
                    "label" => 'Unidad Funcional',
                    'class' => 'AppBundle:Uf',
                    'placeholder' => 'Seleccione Unidad Funcional ...',
                    'query_builder' => function (\AppBundle\Repository\UfRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('pa', EntityType::class, array(
                    "label" => 'Punto Asistencial',
                    'class' => 'AppBundle:Pa',
                    'placeholder' => 'Seleccione Punto Asistencial ...',
                    'query_builder' => function (\AppBundle\Repository\PaRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('fAmortiza', DateType::class, array(
                    "label" => 'Fecha Amortización',
                    "required" => false,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string',)))
                ->add('fCreacion', DateType::class, array(
                    "label" => 'Fecha Creación',
                    "required" => true,
                    'widget' => 'single_text',
                    'attr' => array(
                        'class' => 'form-control corto js-datepicker',
                        'data-date-format' => 'dd-mm-yyyy',
                        'data-class' => 'string',)))
                ->add('modalidad', EntityType::class, array(
                    "label" => 'Modalidad',
                    'class' => 'AppBundle:Modalidad',
                    'placeholder' => 'Seleccione Modalidad ...',
                    'query_builder' => function (\AppBundle\Repository\ModalidadRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "corto form-control")))
                ->add('refuerzo', ChoiceType::class, array(
                    "label" => 'Refuerzo',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('colaboradora', ChoiceType::class, array(
                    "label" => 'Colaboradora',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('No' => 'N','Si' => 'S'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('plantilla', ChoiceType::class, array(
                    "label" => 'Plantilla',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('horNormal', ChoiceType::class, array(
                    "label" => 'Horario Normal',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('turno', ChoiceType::class, array(
                    "label" => 'Turno',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Mañana' => 'M', 'Tarde' => 'T','SAR' => 'P'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('ficticia', ChoiceType::class, array(
                    "label" => 'Ficticia',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('No' => 'N','Si' => 'S'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('cupequi', ChoiceType::class, array(
                    "label" => 'Tipo de Plaza',
                    'required' => true,
                    'disabled' => false,
                    'choices' => array('Equipo' => 'E', 'Cupo' => 'C', 'Otro' => 'O'),
                    "attr" => array("class" => "form-control muycorto")))
                ->add('Guardar', SubmitType::class, array(
                    "attr" => array("class" => "form-submit btn btn-t btn-success")))
                ->addEventSubscriber(new EventListener\PlazaEventSuscribe())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Plaza'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_plaza';
    }

}
