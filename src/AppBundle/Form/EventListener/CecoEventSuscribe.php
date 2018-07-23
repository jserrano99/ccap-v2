<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CecoEventSuscribe implements EventSubscriberInterface {

    public static function getSubscribedEvents() {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::POST_SET_DATA => 'postSetData'
        );
    }

    public function preSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if ($data->getCodigo() == null) {
            $form->add('sociedad', TextType::class, array(
                        "label" => 'Sociedad',
                        "required" => 'required',
                        'disabled' => false,
                        "attr" => array("class" => "form-control muycorto")))
                    ->add('division', TextType::class, array(
                        "label" => 'division',
                        "required" => 'required',
                        'disabled' => false,
                        "attr" => array("class" => "form-control muycorto ")))
                    ->add('codigo', TextType::class, array(
                        "label" => 'Codigo',
                        "required" => 'required',
                        'disabled' => false,
                        "attr" => array("class" => "form-control ")))
            ;
        } else {
            $form->add('sociedad', TextType::class, array(
                        "label" => 'Sociedad',
                        "required" => 'required',
                        'disabled' => true,
                        "attr" => array("class" => "form-control muycorto")))
                    ->add('division', TextType::class, array(
                        "label" => 'division',
                        "required" => 'required',
                        'disabled' => true,
                        "attr" => array("class" => "form-control muycorto")))
                    ->add('codigo', TextType::class, array(
                        "label" => 'Codigo',
                        "required" => 'required',
                        'disabled' => true,
                        "attr" => array("class" => "form-control ")))
            ;
        }
        
        if (null === $data) {
            return;
        }
        $accessor = PropertyAccess::createPropertyAccessor();

        return;
    }

    public function preSubmit(FormEvent $event) {
        $form = $event->getForm();
    }

    public function postSetData(FormEvent $event) {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        return;
    }

}
