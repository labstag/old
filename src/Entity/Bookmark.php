<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;
use Labstag\Entity\Traits\Tags;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Labstag\Controller\Api\BookmarkApi;

/**
 * @ApiResource(
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete",
 *         "api_bookmarktrash"={
 *             "method"="GET",
 *             "path"="/bookmarks/trash",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=BookmarkApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Corbeille",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_bookmarktrashdelete"={
 *             "method"="DELETE",
 *             "path"="/bookmarks/trash",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=BookmarkApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Remove",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_bookmarkrestore"={
 *             "method"="POST",
 *             "path"="/bookmarks/restore",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=BookmarkApi::class,
 *             "read"=false,
 *             "swagger_context"={
 *                  "summary"="Restore",
 *                  "parameters"={}
 *              }
 *         },
 *         "api_bookmarkempty"={
 *             "method"="POST",
 *             "path"="/bookmarks/empty",
 *             "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *             "controller"=BookmarkApi::class,
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
 *     "slug": "partial",
 *     "url": "partial",
 *     "enable": "exact",
 *     "content": "partial"
 * })
 * @ORM\Entity(repositoryClass="Labstag\Repository\BookmarkRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class Bookmark implements Translatable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;
    use Tags;

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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string",   length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Url
     */
    private $url;

    /**
     * @ORM\ManyToMany(targetEntity="Labstag\Entity\Tags", inversedBy="bookmarks")
     */
    private $tags;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @Vich\UploadableField(mapping="upload_file", fileNameProperty="file")
     * @Assert\File(mimeTypes={"image/*"})
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\User", inversedBy="bookmarks")
     */
    private $refuser;

    public function __construct()
    {
        $this->enable = true;
        $this->tags   = new ArrayCollection();
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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

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

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
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

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

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

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }
}
