<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;
use Labstag\Controller\Api\ChapitreApi;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete",
 *         "api_chapitretrash"={
 *             "method"="GET",
 *             "path"="/chapitres/trash",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=ChapitreApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Corbeille",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_chapitretrashdelete"={
 *             "method"="DELETE",
 *             "path"="/chapitres/trash",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=ChapitreApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Remove",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_chapitrerestore"={
 *             "method"="POST",
 *             "path"="/chapitres/restore",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=ChapitreApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Restore",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_chapitreempty"={
 *             "method"="POST",
 *             "path"="/chapitres/empty",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=ChapitreApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Empty",
 *                  "parameters"={}
 *              }
 *         }
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "content": "partial",
 *     "position": "exact",
 *     "enable": "exact",
 *     "status": "exact"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
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
     */
    private $id;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\History", inversedBy="chapitres")
     */
    private $refhistory;

    /**
     * @ORM\Column(type="boolean", options={"default": true}))
     */
    private $enable;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
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

    public function setRefhistory(?History $refhistory): self
    {
        $this->refhistory = $refhistory;

        return $this;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(?bool $enable): self
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

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
