<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;
use Labstag\Controller\Api\TagsApi;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "slug": "partial",
 *     "type": "partial",
 *     "temporary": "exact"
 * })
 * @ApiResource(
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete",
 *         "api_usertrash": {
 *             "method": "GET",
 *             "path": "/tags/trash",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
 *             "controller": TagsApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Corbeille",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_usertrashdelete": {
 *             "method": "DELETE",
 *             "path": "/tags/trash",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
 *             "controller": TagsApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Remove",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_userrestore": {
 *             "method": "POST",
 *             "path": "/tags/restore",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
 *             "controller": TagsApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Restore",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_userempty": {
 *             "method": "POST",
 *             "path": "/tags/empty",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
 *             "controller": TagsApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Empty",
 *                 "parameters": {}
 *             }
 *         }
 *     }
 * )
 * @ApiFilter(
 *     OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"}
 * )
 * @ApiFilter(
 *     SearchFilter::class, properties={"type"}
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\TagsRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * @ORM\Table(
 *     uniqueConstraints={
 * @ORM\UniqueConstraint(name="tags_unique", columns={"name", "type"})
 *     }
 * )
 */
class Tags implements Translatable
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
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string",   length=255, nullable=true)
     */
    private $slug;

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice({"bookmark", "post"})
     */
    private $type;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $temporary;

    /**
     * @ORM\ManyToMany(targetEntity="Labstag\Entity\Post", mappedBy="tags")
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="Labstag\Entity\Bookmark", mappedBy="tags")
     */
    private $bookmarks;

    public function __construct()
    {
        $this->temporary = false;
        $this->posts     = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
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

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->addTag($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            $post->removeTag($this);
        }

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Bookmark[]|Collection
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->addTag($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
            $bookmark->removeTag($this);
        }

        return $this;
    }

    public function isTemporary(): ?bool
    {
        return $this->temporary;
    }

    public function setTemporary(bool $temporary): self
    {
        $this->temporary = $temporary;

        return $this;
    }
}
