<?php

namespace CostesBundle\Form;

use CostesBundle\Repository\UnidadOrganizativaRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use MaestrosBundle\Repository\ModalidadRepository;
use MaestrosBundle\Repository\CatGenRepository;
use MaestrosBundle\Repository\CatFpRepository;
use CostesBundle\Repository\UfRepository;
use CostesBundle\Repository\PaRepository;

class PlazaType extends AbstractType
{

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
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
			->add('catFp', EntityType::class, [
				"label" => 'Categoría Fp',
				'class' => 'MaestrosBundle:CatFp',
				'placeholder' => 'Seleccione Categoría ....',
				'query_builder' => function (CatFpRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('unidadOrganizativa', EntityType::class, [
				"label" => 'Unidad Organizativa',
				'class' => 'CostesBundle\Entity\UnidadOrganizativa',
				'placeholder' => 'Seleccione Unidad Organizativa...',
				'query_builder' => function (UnidadOrganizativaRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => true,
				"attr" => ["class" => "form-control"]])

			->add('observaciones', TextType::class, [
				"label" => 'Observaciones ',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('uf', EntityType::class, [
				"label" => 'Unidad Funcional',
				'class' => 'CostesBundle:Uf',
				'placeholder' => 'Seleccione Unidad Funcional ...',
				'query_builder' => function (UfRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('pa', EntityType::class, [
				"label" => 'Punto Asistencial',
				'class' => 'CostesBundle:Pa',
				'placeholder' => 'Seleccione Punto Asistencial ...',
				'query_builder' => function (PaRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('fAmortiza', DateType::class, [
				"label" => 'Fecha Amortización',
				"required" => false,
				"disabled" => true,
				'widget' => 'single_text',
				'attr' => [
					'class' => 'form-control corto',
					'data-date-format' => 'dd-mm-yyyy',
					'data-class' => 'string',]])
			->add('fCreacion', DateType::class, [
				'label' => 'Fecha Creación',
				'required' => false,
				'widget' => 'single_text',
				'attr' => [
					'class' => 'form-control corto',
				]
			])
			->add('modalidad', EntityType::class, [
				"label" => 'Modalidad',
				'class' => 'MaestrosBundle:Modalidad',
				'placeholder' => 'Seleccione Modalidad ...',
				'query_builder' => function (ModalidadRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "corto form-control"]])
			->add('refuerzo', ChoiceType::class, [
				"label" => 'Refuerzo',
				'required' => true,
				'disabled' => false,
				'choices' => ['No' => 'N', 'Si' => 'S'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('colaboradora', ChoiceType::class, [
				"label" => 'Colaboradora',
				'required' => true,
				'disabled' => false,
				'choices' => ['No' => 'N', 'Si' => 'S'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('plantilla', ChoiceType::class, [
				"label" => 'Plantilla',
				'required' => true,
				'disabled' => false,
				'choices' => ['Si' => 'S', 'No' => 'N'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('horNormal', ChoiceType::class, [
				"label" => 'Horario Normal',
				'required' => true,
				'disabled' => false,
				'choices' => ['Si' => 'S', 'No' => 'N'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('ficticia', ChoiceType::class, [
				"label" => 'Ficticia',
				'required' => true,
				'disabled' => false,
				'choices' => ['No' => 'N', 'Si' => 'S'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('cupequi', ChoiceType::class, [
				"label" => 'Tipo de Plaza',
				'required' => true,
				'disabled' => false,
				'choices' => ['Equipo' => 'E', 'Cupo' => 'C', 'Otro' => 'O'],
				"attr" => ["class" => "form-control muycorto"]])
			->add('cecoActual', EntityType::class, [
				"label" => 'Centro de Coste Actual',
				'class' => 'CostesBundle:Ceco',
				'placeholder' => 'Seleccione Centro de Coste ...',
				'query_builder' => function (\CostesBundle\Repository\CecoRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => true,
				"attr" => ["class" => "medio form-control"]])
			->add('turnof', EntityType::class, [
				"label" => 'Turno',
				'class' => 'MaestrosBundle:Turno',
				'placeholder' => 'Seleccione Turno...',
				'query_builder' => function (\MaestrosBundle\Repository\TurnoRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "medio form-control"]])
			->add('fInicio', DateType::class, [
				"label" => 'Fecha Inicio',
				"required" => false,
				'mapped' => false,
				'widget' => 'single_text',
				'attr' => [
					'class' => 'form-control muycorto js-datepicker',
					'data-date-format' => 'dd-mm-yyyy',
					'data-class' => 'string',]])
			->add('nuevoCecoCodigo', TextType::class, [
				"label" => 'Nuevo Ceco (Código)',
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "form-control muycorto"]])
			->add('codigoUf', TextType::class, [
				"label" => 'Unidad Funcional (Código)',
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "form-control muycorto"]])
			->add('descripcionUf', TextType::class, [
				"label" => 'Descripción',
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "form-control medio"]])
			->add('codigoPa', TextType::class, [
				"label" => 'Punto Asistencial (Código)',
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "form-control muycorto"]])
			->add('descripcionPa', TextType::class, [
				"label" => 'Descripción',
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "form-control medio"]])
			->add('nuevoCecoDesc', TextType::class, [
				"label" => 'Descripción',
				'required' => false,
				'disabled' => true,
				'mapped' => false,
				"attr" => ["class" => "form-control medio"]])
			->add('nuevoCeco', EntityType::class, [
				"label" => 'Selección de Centro de Coste',
				'class' => 'CostesBundle:Ceco',
				'placeholder' => 'Seleccione Centro de Coste ...',
				'query_builder' => function (\CostesBundle\Repository\CecoRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				'mapped' => false,
				"attr" => ["class" => "corto form-control"]])
			->add('Guardar', SubmitType::class, [
				"attr" => ["class" => "form-submit btn btn-t btn-success"]])
			->add('h1ini', TimeType::class, [
				"label" => 'Horario 1 Inicio',
				'required' => false,
				'disabled' => false,
				'widget' => 'single_text',
				"attr" => ["class" => "muycorto form-control"]
			])
			->add('h1fin', TimeType::class, [
				"label" => 'Horario 1 Fin',
				'required' => false,
				'disabled' => false,
				'widget' => 'single_text',
				"attr" => ["class" => "muycorto form-control"]
			])
			->add('h2ini', TimeType::class, [
				"label" => 'Horario 2 Inicio',
				'required' => false,
				'disabled' => false,
				'widget' => 'single_text',

				"attr" => ["class" => "muycorto form-control"]
			])
			->add('h2fin', TimeType::class, [
				"label" => 'Horario 2 Fin',
				'required' => false,
				'disabled' => false,
				'widget' => 'single_text',
				"attr" => ["class" => "muycorto form-control"]
			])
			->addEventSubscriber(new EventListener\PlazaEventSuscribe());
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{

	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'CostesBundle\Entity\Plaza'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'costesbundle_plaza';
	}

}
