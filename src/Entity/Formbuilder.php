<?php

namespace Labstag\Entity;

use Gedmo\Translatable\Translatable;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Gedmo\Blameable\Traits\BlameableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ApiResource
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ORM\Entity(repositoryClass="Labstag\Repository\FormbuilderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Formbuilder implements Translatable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Versioned
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Gedmo\Versioned
     */
    private $formbuilder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    public function __construct() {
        $this->formbuilder = json_encode(array());
    }

    public function __toString(): ?string
    {
        return $this->getName();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
