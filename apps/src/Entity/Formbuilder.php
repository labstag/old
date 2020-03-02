<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Labstag\Resolver\Query\EntityResolver;
use Labstag\Resolver\Query\TrashCollectionResolver;
use Labstag\Resolver\Query\TrashResolver;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "enable": "exact",
 *     "slug": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     graphql={
 *         "item_query",
 *         "collection_query",
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"},
 *         "update": {"security": "is_granted('ROLE_ADMIN')"},
 *         "create": {"security": "is_granted('ROLE_ADMIN')"},
 *         "collection",
 *         "trash": {
 *             "item_query": TrashResolver::class
 *         },
 *         "data": {
 *             "item_query": EntityResolver::class
 *         },
 *         "trashCollection": {
 *             "collection_query": TrashCollectionResolver::class
 *         }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "put": {"security": "is_granted('ROLE_ADMIN')"},
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\FormbuilderRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Formbuilder
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     * @ApiProperty(iri="https://schema.org/identifier")
     *
     * @var string
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    private $name;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $formbuilder;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $enable;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     *
     * @var string|null
     */
    private $slug;

    public function __construct()
    {
        $this->formbuilder = (string) json_encode([]);
    }

    public function __toString(): string
    {
        return (string) $this->getName();
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

    public function getFormbuilder(): string
    {
        return $this->formbuilder;
    }

    public function setFormbuilder(string $formbuilder): self
    {
        $this->formbuilder = $formbuilder;

        return $this;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
