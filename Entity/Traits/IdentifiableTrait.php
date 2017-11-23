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
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
