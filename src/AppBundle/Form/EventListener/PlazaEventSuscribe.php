<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PlazaEventSuscribe implements EventSubscriberInterface
{
   public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::POST_SET_DATA => 'postSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        
        if ($data->getCias()== null ) {
            $form->add('cias', TextType::class, array(
                    "label" => 'Identificador de Plaza ',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control muycorto")));
            $form->add('orden', TextType::class, array(
                    "label" => 'Nº Orden',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control ident")));
        } else { 
            $form->add('cias', TextType::class, array(
                    "label" => 'Identificador de Plaza ',
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "form-control muycorto")));
            $form->add('orden', TextType::class, array(
                    "label" => 'Nº Orden',
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "form-control ident")));
        }
        if ($data->getCeco()== null ) {
            $form->add('cecoInf', TextType::class, array(
                    "label" => 'Centro de Coste',
                    'required' => false,
                    'disabled' => false,
                    'mapped' => false,
                    "attr" => array("class" => "form-control muycorto")));
            $form->add('cecoDesc', TextType::class, array(
                    "label" => 'Centro de Coste',
                    'required' => false,
                    'disabled' => true,
                    'mapped' => false,
                    "attr" => array("class" => "form-control corto")));
        }
        
        
        if (null === $data) {
            return;
        }
        $accessor = PropertyAccess::createPropertyAccessor();
        
        return;
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
    }
    
    public function postSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        
        return;
    }

}
