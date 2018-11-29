<?php

namespace MaestrosBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class GrupoCobroType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('codigo', TextType::class, [
				"label" => 'Código ',
				'required' => true,
				'disabled' => true,
				"attr" => ["class" => "form-control muycorto"]])
			->add('descripcion', TextType::class, [
				"label" => 'Descripción',
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('nivel', TextType::class, [
				"label" => 'Nivel',
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('horas', TextType::class, [
				"label" => 'Horas',
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('grupob', ChoiceType::class, [
				"label" => 'GrupoB',
				'choices' => ['No' => 'N', 'Si' => 'S',],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('apd', ChoiceType::class, [
				"label" => 'APD',
				'choices' => ['No' => 'N', 'Si' => 'S',],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('refuerzo', ChoiceType::class, [
				"label" => 'Refuerzo',
				'choices' => ['No' => 'N', 'Si' => 'S',],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('perSinSueldo', ChoiceType::class, [
				"label" => 'Permiso Sin Sueldo',
				'choices' => ['No' => 'N', 'Si' => 'S',],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('cobraNomina', ChoiceType::class, [
				"label" => 'Cobra Nómina',
				'choices' => ['No' => 'N', 'Si' => 'S',],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('cotizaSs', ChoiceType::class, [
				"label" => 'Cotiza Seg.Social',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('prodtsi', ChoiceType::class, [
				"label" => 'Productividad TSI',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('liq_extra', ChoiceType::class, [
				"label" => 'Liquida Paga Extra TSI',
				'choices' => ['C' => 'C', 'N' => 'N', 'M' => 'M'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('liq_vacaciones', ChoiceType::class, [
				"label" => 'Liquida Vacaciones',
				'choices' => ['C' => 'C', 'N' => 'N', 'M' => 'M'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('retribucion', ChoiceType::class, [
				"label" => 'Retribución',
				'choices' => ['C' => 'C', 'E' => 'E'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('tipo', ChoiceType::class, [
				"label" => 'Tipo',
				'choices' => ['F' => 'F', 'O' => 'O', 'M' => 'M', 'T' => 'T'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('minimoFijo', ChoiceType::class, [
				"label" => 'Minimo Fijo',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('minimoInterino', ChoiceType::class, [
				"label" => 'Minimo Interino',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('minimoEventual', ChoiceType::class, [
				"label" => 'Mínimo Eventual',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('minimoEv', TextType::class, [
				"label" => 'Minimo Ev',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('horasAnuales', TextType::class, [
				"label" => 'Horas Anuales',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('horasSabados', TextType::class, [
				"label" => 'Horas Sabados',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('mediaVacaciones', TextType::class, [
				"label" => 'Media Vacaciones',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('enuso', ChoiceType::class, [
				"label" => 'En USo',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('excluirPlPage', ChoiceType::class, [
				"label" => 'Excluir PLPage',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('grcRptCodigo', TextType::class, [
				"label" => 'Grc RPt Codigo',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('grcRptDescripcion', TextType::class, [
				"label" => 'Grc RPt Descripción',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('grcRptId', TextType::class, [
				"label" => 'Grc RPt Id',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('personal', ChoiceType::class, [
				"label" => 'Personal',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('peac', ChoiceType::class, [
				"label" => 'PEAC',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('excluir_extra', TextType::class, [
				"label" => 'Excluir Extra',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('asuMedia', ChoiceType::class, [
				"label" => 'AsuMedia',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('extraPorHoras', ChoiceType::class, [
				"label" => 'Extra por Horas',
				'choices' => ['No' => 'N', 'Si' => 'S'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('asuMediaPeriodo', ChoiceType::class, [
				"label" => 'PEAC',
				'choices' => ['M' => 'M'],
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('epiAcc', EntityType::class, [
				"label" => 'Epígrafe de Accidentes ',
				'class' => 'MaestrosBundle:EpiAcc',
				'placeholder' => 'Seleccione Epígrafe de Accidentes ....',
				'query_builder' => function (\MaestrosBundle\Repository\EpiAccRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "medio form-control"]])
			->add('grupoProf', EntityType::class, [
				"label" => 'Grupo Profesional ',
				'class' => 'MaestrosBundle:GrupoProf',
				'placeholder' => 'Seleccione Grupo Profesional ....',
				'query_builder' => function (\MaestrosBundle\Repository\GrupoProfRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "medio form-control"]])
			->add('ocupacion', EntityType::class, [
				"label" => 'Ocupación',
				'class' => 'MaestrosBundle:Ocupacion',
				'placeholder' => 'Seleccione Ocupación ....',
				'query_builder' => function (\MaestrosBundle\Repository\OcupacionRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "medio form-control"]])
			->add('grupoCot', EntityType::class, [
				"label" => 'Grupo Cotización ',
				'class' => 'MaestrosBundle:GrupoCot',
				'placeholder' => 'Seleccione Grupo Cotización....',
				'query_builder' => function (\MaestrosBundle\Repository\GrupoCotRepository $er) {
					return $er->createAlphabeticalQueryBuilder();
				},
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "medio form-control"]]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'MaestrosBundle\Entity\GrupoCobro'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'maestrosbundle_grupocobro';
	}


}
