<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Labstag\Repository\FormbuilderRepository")
 */
class Formbuilder
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formbuilder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormbuilder(): ?string
    {
        return $this->formbuilder;
    }

    public function setFormbuilder(string $formbuilder): self
    {
        $this->formbuilder = $formbuilder;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }
}
