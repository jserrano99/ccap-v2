<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use MaestrosBundle\Repository\OcupacionRepository;
use MaestrosBundle\Repository\CatGenRepository;
use MaestrosBundle\Repository\CatAnexoRepository;
use MaestrosBundle\Repository\GrupoCotRepository;
use MaestrosBundle\Repository\EpiAccRepository;
use MaestrosBundle\Repository\GrupoProfRepository;
use MaestrosBundle\Repository\GrupoCobroRepository;


/**
 * Class CategType
 *
 * @package MaestrosBundle\Form
 */
class CategType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('catGen', EntityType::class, [
                    "label" => 'Categoría General',
                    'class' => 'MaestrosBundle:CatGen',
                    'placeholder' => 'Seleccione Categoría General....',
                    'query_builder' => function (CatGenRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => true,
                    'disabled' => false,
                    "attr" => ["class" => "form-control"]])
                ->add('codigo', TextType::class, [
                    "label" => 'Código ',
                    'required' => true,
                    'disabled' => false,
                    "attr" => ["class" => "form-control"]])
                ->add('descripcion', TextType::class, [
                    "label" => 'Descripción ',
                    'required' => true,
                    'disabled' => false,
                    "attr" => ["class" => "form-control"]])
                ->add('fsn', ChoiceType::class, [
                    "label" => 'F/S/N',
                    'choices' => ['Facultativo' => '1',
                        'Sanitario No Factultativo' => '4',
                        'No Sanitario' => '6'],
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "form-control"]])
                ->add('mir', ChoiceType::class, [
                    "label" => 'MIR',
                    'required' => false,
                    'disabled' => false,
                    'choices' => ['Si' => 'S', 'No' => 'N'],
                    "attr" => ["class" => "form-control"]])
                ->add('directivo', ChoiceType::class, [
                    "label" => 'Directivo',
                    'required' => false,
                    'disabled' => false,
                    'choices' => ['Si' => 'S', 'No' => 'N'],
                    "attr" => ["class" => "form-control"]])
                ->add('catAnexo', EntityType::class, [
                    "label" => 'Categoría Anexo ',
                    'class' => 'MaestrosBundle:CatAnexo',
                    'placeholder' => 'Seleccione Categoría Anexo ....',
                    'query_builder' => function (CatAnexoRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('GrupoCot', EntityType::class, [
                    "label" => 'Grupo Cotización ',
                    'class' => 'MaestrosBundle:GrupoCot',
                    'placeholder' => 'Seleccione Grupo Cotización....',
                    'query_builder' => function (GrupoCotRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('epiAcc', EntityType::class, [
                    "label" => 'Epígrafe de Accidentes ',
                    'class' => 'MaestrosBundle:EpiAcc',
                    'placeholder' => 'Seleccione Epígrafe de Accidentes ....',
                    'query_builder' => function (EpiAccRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('grupoProf', EntityType::class, [
                    "label" => 'Grupo Profesional ',
                    'class' => 'MaestrosBundle:GrupoProf',
                    'placeholder' => 'Seleccione Grupo Profesional ....',
                    'query_builder' => function (GrupoProfRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('enuso', ChoiceType::class, [
                    "label" => 'En Uso',
                    'choices' => ['Si' => 'S', 'No' => 'N'],
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "muycorto form-control"]])
                ->add('condicionado', ChoiceType::class, [
                    "label" => 'Condicionado',
                    'choices' => ['Si' => 'S', 'No' => 'N'],
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "muycorto form-control"]])
                ->add('grupoCobro', EntityType::class, [
                    "label" => 'Grupo de Cobro por Defecto',
                    'class' => 'MaestrosBundle:GrupoCobro',
                    'placeholder' => 'Seleccione Grupo de Cobro ....',
                    'query_builder' => function (GrupoCobroRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('ocupacion', EntityType::class, [
                    "label" => 'Ocupación',
                    'class' => 'MaestrosBundle:Ocupacion',
                    'placeholder' => 'Seleccione Ocupación ....',
                    'query_builder' => function (OcupacionRepository $er) {
                        return $er->createAlphabeticalQueryBuilder();
                    },
                    'required' => false,
                    'disabled' => false,
                    "attr" => ["class" => "medio form-control"]])
                ->add('replica', ChoiceType::class, [
                    "label" => 'Replicar en ',
                    'required' => true,
                    'disabled' => false,
                    'choices' => ['Todas las Bases de Datos' => '1',
                        'Solo en la Única' => '2',
                        'En todas menos la única' => '3'],
                    "attr" => ["class" => "form-control"]])
                ->add('Guardar', SubmitType::class, [
                    "attr" => ["class" => "form-submit btn btn-t btn-success"]])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        
    }

	/**
	 * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
	 */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'MaestrosBundle\Entity\Categ'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_categ';
    }

}
