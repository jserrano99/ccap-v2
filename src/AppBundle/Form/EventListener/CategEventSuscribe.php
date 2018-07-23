<?php

namespace AppBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CategEventSuscribe implements EventSubscriberInterface
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

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $catgen = $accessor->getValue($data, 'catgen');
        
        return;
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $this->addClienteForm($form);
    }
    
    public function postSetData(FormEvent $event)
    {
        dump("postSetData");
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $catgen = $accessor->getValue($data, 'catgen');
        
        return;
    }

}
