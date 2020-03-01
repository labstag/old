<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;
use Labstag\Entity\Traits\Chapitre;
use Labstag\Resolver\Mutation\EmptyResolver;
use Labstag\Resolver\Mutation\RestoreResolver;
use Labstag\Resolver\Query\CollectionResolver;
use Labstag\Resolver\Query\EntityResolver;
use Labstag\Resolver\Query\TrashCollectionResolver;
use Labstag\Resolver\Query\TrashResolver;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "enable": "exact",
 *     "slug": "partial",
 *     "end": "exact",
 *     "resume": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     graphql={
 *         "item_query": {
 *             "item_query": EntityResolver::class
 *         },
 *         "collection_query",
 *         "restore": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "args": {
 *                 "id": {"type": "ID!"}
 *             },
 *             "mutation": RestoreResolver::class
 *         },
 *         "empty": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "args": {
 *                 "id": {"type": "ID!"}
 *             },
 *             "mutation": EmptyResolver::class
 *         },
 *         "delete": {
 *             "security": "is_granted('ROLE_ADMIN')"
 *         },
 *         "update": {
 *             "security": "is_granted('ROLE_ADMIN')"
 *         },
 *         "create": {
 *             "security": "is_granted('ROLE_ADMIN')"
 *         },
 *         "collection": {
 *             "security": "is_granted('ROLE_ADMIN')"
 *         },
 *         "trash": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "item_query": TrashResolver::class
 *         },
 *         "data": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "item_query": EntityResolver::class
 *         },
 *         "trashCollection": {
 *             "security": "is_granted('ROLE_ADMIN')",
 *             "collection_query": TrashCollectionResolver::class
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
 * @ORM\Entity(repositoryClass="Labstag\Repository\HistoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class History implements Translatable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;
    use Chapitre;

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
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\User", inversedBy="histories")
     *
     * @var User
     */
    private $refuser;

    /**
     * @ORM\Column(type="boolean", options={"default": true}))
     *
     * @var bool
     */
    private $enable;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $file;

    /**
     * @Vich\UploadableField(mapping="upload_file", fileNameProperty="file")
     * @Assert\File(mimeTypes={"image/*"})
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Chapitre", mappedBy="refhistory")
     * @ApiSubresource
     * @ORM\OrderBy({"position": "ASC"})
     *
     * @var ArrayCollection
     */
    private $chapitres;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $end;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $resume;

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
        $this->end       = false;
        $this->enable    = true;
        $this->chapitres = new ArrayCollection();
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setImageFile(File $image = null): void
    {
        $this->imageFile = $image;
        if ($image) {
            $dateTimeImmutable = new DateTimeImmutable();
            $dateTime          = new DateTime();
            $dateTime->setTimestamp($dateTimeImmutable->getTimestamp());
            $this->updatedAt = $dateTime;
        }
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(User $refuser): self
    {
        $this->refuser = $refuser;

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

    public function isEnd(): ?bool
    {
        return $this->end;
    }

    public function setEnd(bool $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function setTranslatableLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }
}
