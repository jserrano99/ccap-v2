<?php

namespace MaestrosBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AltasEventSuscribe implements EventSubscriberInterface
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
        
        if ($data->getCodigo()== null ) {
            $form->add('codigo', TextType::class, array(
                    "label" => 'Código',
                    'required' => true,
                    'disabled' => false,
                    "attr" => array("class" => "form-control muycorto")));
        } else { 
            $form->add('codigo', TextType::class, array(
                    "label" => 'Código',
                    'required' => true,
                    'disabled' => true,
                    "attr" => array("class" => "form-control muycorto")));
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
