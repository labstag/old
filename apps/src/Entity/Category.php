<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Translatable\Translatable;
use Labstag\Controller\Api\CategoryApi;
<<<<<<< HEAD
=======
use Labstag\CollectionResolver\TrashCollectionResolver;
>>>>>>> 70eef9d9de7dd17df3a3addf58c7c49623b0f58b
use Labstag\Entity\Traits\Post;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "temporary": "exact",
 *     "slug": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
<<<<<<< HEAD
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete",
 *         "api_categorytrash": {
 *             "method": "GET",
 *             "path": "/categories/trash",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
=======
 *     graphql={
 *       "trashCollection"={
 *            "collection_query"=TrashCollectionResolver::class
 *       }
 *     },
 *     itemOperations={
 *         "get": {
 *             "access_control": "is_granted('ROLE_ADMIN')"
 *          },
 *         "put": {
 *             "access_control": "is_granted('ROLE_ADMIN')"
 *          },
 *         "delete": {
 *             "access_control": "is_granted('ROLE_ADMIN')"
 *          },
 *         "api_categorytrash": {
 *             "access_control": "is_granted('ROLE_ADMIN')",
 *             "method": "GET",
 *             "path": "/categories/trash",
>>>>>>> 70eef9d9de7dd17df3a3addf58c7c49623b0f58b
 *             "controller": CategoryApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Corbeille",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_categorytrashdelete": {
<<<<<<< HEAD
 *             "method": "DELETE",
 *             "path": "/categories/trash",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
=======
 *             "access_control": "is_granted('ROLE_ADMIN')",
 *             "method": "DELETE",
 *             "path": "/categories/trash",
>>>>>>> 70eef9d9de7dd17df3a3addf58c7c49623b0f58b
 *             "controller": CategoryApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Remove",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_categoryrestore": {
<<<<<<< HEAD
 *             "method": "POST",
 *             "path": "/categories/restore",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
=======
 *             "access_control": "is_granted('ROLE_ADMIN')",
 *             "method": "POST",
 *             "path": "/categories/restore",
>>>>>>> 70eef9d9de7dd17df3a3addf58c7c49623b0f58b
 *             "controller": CategoryApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Restore",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_categoryempty": {
<<<<<<< HEAD
 *             "method": "POST",
 *             "path": "/categories/empty",
 *             "access_control": "is_granted('ROLE_SUPER_ADMIN')",
=======
 *             "access_control": "is_granted('ROLE_ADMIN')",
 *             "method": "POST",
 *             "path": "/categories/empty",
>>>>>>> 70eef9d9de7dd17df3a3addf58c7c49623b0f58b
 *             "controller": CategoryApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Empty",
 *                 "parameters": {}
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\CategoryRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Category implements Translatable
{
    use BlameableEntity;
    use SoftDeleteableEntity;
    use TimestampableEntity;
    use Post;

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
     * @ORM\Column(type="boolean", options={"default": false})
     *
     * @var bool
     */
    private $temporary;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Post", mappedBy="refcategory")
     * @ApiSubresource
     *
     * @var ArrayCollection
     */
    private $posts;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string",   length=255, nullable=true)
     *
     * @var string|null
     */
    private $slug;

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
        $this->temporary = false;
        $this->posts     = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTranslatableLocale(string $locale): self
    {
        $this->locale = $locale;

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
