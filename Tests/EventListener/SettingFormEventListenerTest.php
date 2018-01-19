<?php


namespace DigipolisGent\SettingBundle\Tests\EventListener;


use DigipolisGent\SettingBundle\EventListener\SettingFormListener;
use DigipolisGent\SettingBundle\Service\FormService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Forms;

class SettingFormEventListenerTest extends TestCase
{

    public function testGetSubscribedEvents()
    {
        $formService = $this->getFormServiceMock();
        $eventListener = new SettingFormListener($formService);

        $expected = [
            FormEvents::POST_SET_DATA => 'onPostSetData',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];

        $this->assertEquals($expected, $eventListener::getSubscribedEvents());
    }

    public function testOnPostSetData()
    {
        $formService = $this->getFormServiceMock();
        $formService
            ->expects($this->at(0))
            ->method('addConfig');

        $formEvent = $this->getFormEventMock();

        $eventListener = new SettingFormListener($formService);
        $eventListener->onPostSetData($formEvent);
    }

    public function testOnPostSubmit()
    {
        $formService = $this->getFormServiceMock();
        $formService
            ->expects($this->at(0))
            ->method('addConfig');

        $formEvent = $this->getFormEventMock();

        $eventListener = new SettingFormListener($formService);
        $eventListener->onPostSubmit($formEvent);
    }

    private function getFormServiceMock()
    {
        $mock = $this
            ->getMockBuilder(FormService::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    private function getFormEventMock()
    {
        $mock = $this
            ->getMockBuilder(FormEvent::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->at(0))
            ->method('getForm')
            ->willReturn($this->getForm());

        return $mock;
    }

    private function getForm()
    {
        $factory = Forms::createFormFactoryBuilder()->getFormFactory();
        return $factory->create(FormType::class);
    }


}