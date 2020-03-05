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
use Labstag\Resolver\Query\Chapitre\ItemEnableResolver;
use Labstag\Resolver\Query\Chapitre\CollectionEnableResolver;
use Gedmo\Translatable\Translatable;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "content": "partial",
 *     "position": "exact",
 *     "enable": "exact",
 *     "status": "exact"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     graphql={
 *         "item_query",
 *         "collection_query": {"security": "is_granted('ROLE_ADMIN')"},
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"},
 *         "update": {"security": "is_granted('ROLE_ADMIN')"},
 *         "create": {"security": "is_granted('ROLE_ADMIN')"},
 *         "collection": {"security": "is_granted('ROLE_ADMIN')"},
 *         "lastenable": {
 *             "item_query": ItemEnableResolver::class,
 *             "args": {}
 *         },
 *         "enable": {
 *             "collection_query": CollectionEnableResolver::class
 *         }
 *     },
 *     collectionOperations={
 *         "get": {"security": "is_granted('ROLE_ADMIN')"},
 *         "post": {"security": "is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get": {"security": "is_granted('ROLE_ADMIN')"},
 *         "put": {"security": "is_granted('ROLE_ADMIN')"},
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"}
 *     }
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\ChapitreRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Chapitre implements Translatable
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
     * @ORM\Column(type="string", length=255)
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
    private $content;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="History", inversedBy="chapitres")
     *
     * @var History
     */
    private $refhistory;

    /**
     * @ORM\Column(type="boolean", options={"default": true}))
     *
     * @var bool
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $status;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    private $locale;

    public function __construct()
    {
        $this->status = '';
        $this->enable = true;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRefhistory(): ?History
    {
        return $this->refhistory;
    }

    public function setRefhistory(History $refhistory): self
    {
        $this->refhistory = $refhistory;

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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setTranslatableLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
