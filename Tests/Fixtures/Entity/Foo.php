<?php


namespace DigipolisGent\SettingBundle\Tests\Fixtures\Entity;


use DigipolisGent\SettingBundle\Entity\Traits\IdentifiableTrait;
use DigipolisGent\SettingBundle\Entity\Traits\SettingImplementationTrait;

class Foo
{
    use IdentifiableTrait;
    use SettingImplementationTrait;
}