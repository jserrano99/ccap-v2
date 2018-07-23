<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

//use AppBundle\Form\EventListener\CategEventSuscribe;

class CategType extends AbstractType {

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
                ->add('codigo', TextType::class, array(
                    "label" => 'Código ',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('descripcion', TextType::class, array(
                    "label" => 'Descripción ',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('fsn', ChoiceType::class, array(
                    "label" => 'F/S/N',
                    'choices' => array('Facultativo' => '1',
                        'Sanitario No Factultativo' => '4',
                        'No Sanitario' => '6'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "form-control")))
                ->add('mir', ChoiceType::class, array(
                    "label" => 'MIR',
                    'required' => false,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control")))
                ->add('directivo', ChoiceType::class, array(
                    "label" => 'Directivo',
                    'required' => false,
                    'disabled' => false,
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    "attr" => array("class" => "form-control")))
                ->add('catAnexo', EntityType::class, array(
                    "label" => 'Categoría Anexo ',
                    'class' => 'AppBundle:CatAnexo',
                    'placeholder' => 'Seleccione Categoría Anexo ....',
                    'query_builder' => function (\AppBundle\Repository\CatAnexoRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('grupoCot', EntityType::class, array(
                    "label" => 'Grupo Cotización ',
                    'class' => 'AppBundle:GrupoCot',
                    'placeholder' => 'Seleccione Grupo Cotización....',
                    'query_builder' => function (\AppBundle\Repository\GrupoCotRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('epiAcc', EntityType::class, array(
                    "label" => 'Epígrafe de Accidentes ',
                    'class' => 'AppBundle:EpiAcc',
                    'placeholder' => 'Seleccione Epígrafe de Accidentes ....',
                    'query_builder' => function (\AppBundle\Repository\EpiAccRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('grupoProf', EntityType::class, array(
                    "label" => 'Grupo Profesional ',
                    'class' => 'AppBundle:GrupoProf',
                    'placeholder' => 'Seleccione Grupo Profesional ....',
                    'query_builder' => function (\AppBundle\Repository\GrupoProfRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('enuso', ChoiceType::class, array(
                    "label" => 'En Uso',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "muycorto form-control")))
                ->add('condicionado', ChoiceType::class, array(
                    "label" => 'Condicionado',
                    'choices' => array('Si' => 'S', 'No' => 'N'),
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "muycorto form-control")))
                ->add('grupoCobro', EntityType::class, array(
                    "label" => 'Grupo de Cobro',
                    'class' => 'AppBundle:GrupoCobro',
                    'placeholder' => 'Seleccione Grupo de Cobro ....',
                    'query_builder' => function (\AppBundle\Repository\GrupoCobroRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
                ->add('ocupacion', EntityType::class, array(
                    "label" => 'Ocupación',
                    'class' => 'AppBundle:Ocupacion',
                    'placeholder' => 'Seleccione Ocupación ....',
                    'query_builder' => function (\AppBundle\Repository\OcupacionRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => array("class" => "medio form-control")))
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
            'data_class' => 'AppBundle\Entity\Categ'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_categ';
    }

}
