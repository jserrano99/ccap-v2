<?php

namespace CostesBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Class UnidadOrganizativaType
 *
 * @package CostesBundle\Form
 */
class UnidadOrganizativaType extends AbstractType
{
	/**
	 * {@inheritdoc}
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('id', TextType::class, [
				"label" => 'id',
				'required' => true,
				'disabled' => true,
				"attr" => ["class" => "form-control muycorto"]])
			->add('descripcion', TextType::class, [
				"label" => 'Descripción Unidad',
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control "]])
			->add('dependencia', EntityType::class, [
				"label" => 'Unidad de Dependencia',
				'class' => 'CostesBundle\Entity\UnidadOrganizativa',
				'placeholder' => 'Seleccione Unidad de Dependencia',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control medio "]])
			->add('codigo', TextType::class, [
				"label" => 'Código',
				'required' => true,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('orden', TextType::class, [
				"label" => 'Orden',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control muycorto"]])
			->add('tipoUnidad', EntityType::class, [
				"label" => 'Tipo de Unidad ',
				'class' => 'CostesBundle\Entity\TipoUnidad',
				'placeholder' => 'Seleccione Tipo de Unidad',
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control"]])
			->add('ciasResponsable', TextType::class, [
				"label" => 'Cias del Responsable',
				'mapped' => false,
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('responsableActual', EntityType::class, [
				"label" => 'Responsable Actual' ,
				'class' => 'CostesBundle\Entity\Plaza',
				'placeholder' => 'Cias Responsable Actual',
				'required' => false,
				'disabled' => true,
				"attr" => ["class" => "form-control corto"]])
			->add('fcCambioResponsable', DateType::class, [
				"label" => 'Fecha Cambio',
				"required" => false,
				"disabled" => false,
				'mapped' => false,
				'widget' => 'single_text',
				'attr' => [
					'class' => 'form-control corto',
					'data-date-format' => 'dd-mm-yyyy',
					'data-class' => 'string',]])
			->add('ciasValidador', TextType::class, [
				"label" => 'Cias Validador',
				'mapped' => false,
				'required' => false,
				'disabled' => false,
				"attr" => ["class" => "form-control corto"]])
			->add('fcCambioValidador', DateType::class, [
				"label" => 'Fecha Cambio',
				"required" => false,
				"disabled" => false,
				'mapped' => false,
				'widget' => 'single_text',
				'attr' => [
					'class' => 'form-control corto',
					'data-date-format' => 'dd-mm-yyyy',
					'data-class' => 'string',]])

			->add('Guardar', SubmitType::class, [
				"attr" => ["class" => "form-submit btn btn-t btn-success"
				]]);

	}

	/**
	 * {@inheritdoc}
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'CostesBundle\Entity\UnidadOrganizativa'
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlockPrefix()
	{
		return 'costesbundle_unidadorganizativa';
	}


}
