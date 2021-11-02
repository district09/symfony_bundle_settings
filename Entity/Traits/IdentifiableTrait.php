<?php

namespace DigipolisGent\SettingBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait IdentifiableTrait
 * @package DigipolisGent\SettingBundle\Entity\Traits
 */
trait IdentifiableTrait
{
    /**
     * @var string $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
