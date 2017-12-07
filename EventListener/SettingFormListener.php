<?php


namespace DigipolisGent\SettingBundle\EventListener;


use DigipolisGent\SettingBundle\Service\FormService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class SettingFormListener
 * @package DigipolisGent\SettingBundle\EventListener
 */
class SettingFormListener implements EventSubscriberInterface
{

    private $formService;

    /**
     * SettingFormListener constructor.
     * @param FormService $formService
     */
    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    /**
     * @param FormEvent $formEvent
     */
    public function onPostSetData(FormEvent $formEvent)
    {
        $this->formService->addConfig($formEvent->getForm());
    }

    /**
     * @param FormEvent $formEvent
     */
    public function onPostSubmit(FormEvent $formEvent)
    {
        $this->formService->processForm($formEvent->getForm());
    }


}