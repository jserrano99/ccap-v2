<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UsuarioType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('codigo', TextType::class, array (
                                    "label" => 'Username',
                                    "required" => 'required',
                                    "attr" => array ("class" => "form-codigo form-control"
                )))
                ->add('nombre', TextType::class, array (
                                    "label" => 'Nombre',
                                    "required" => 'required',
                                    "attr" => array ("class" => "form-nombre form-control"
                )))
                ->add('email', EmailType::class, array(
                                    "label"=>'Correo Electrónico',
                                    "required" => 'required',
                                    'attr' => array('class' => 'form-email form-control'
                )))
                ->add('estadoUsuario', EntityType::class, array(
                                    'label'=>'Estado',
                                    'class' => 'AppBundle:EstadoUsuario',
                                    'required' => "required",
                                    'placeholder' => 'Seleccione Estado....',
                                    'attr'=> array("class" => "form-estado form-control")
                ))
                ->add('perfil', ChoiceType::class, array(
                                    "label"=>'Perfil',
                                    "required" => "required",
                                    "choices" => array( "Administrador" => 'ROLE_ADMIN',
                                                        "Usuario" => 'ROLE_USER'),
                                   "attr"=> array("class" => "form-perfil form-control"
                )))
                ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "form-submit btn btn-t btn-success")
                                )
                );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_usuario';
    }
}
