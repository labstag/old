<?php

namespace Labstag\Entity;

use Labstag\CollectionResolver\TrashCollectionResolver;
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
use Labstag\Controller\Api\TemplateApi;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "code": "partial",
 *     "html": "partial",
 *     "text": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     graphql={
 *         "trashCollection"={
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
 *         "api_templatestrash": {
 *             "method": "GET",
 *             "path": "/templates/trash",
 *             "controller": TemplateApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Corbeille",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_templatestrashdelete": {
 *             "method": "DELETE",
 *             "path": "/templates/trash",
 *             "controller": TemplateApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Remove",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_templatesrestore": {
 *             "method": "POST",
 *             "path": "/templates/restore",
 *             "controller": TemplateApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Restore",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_templatesempty": {
 *             "method": "POST",
 *             "path": "/templates/empty",
 *             "controller": TemplateApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Empty",
 *                 "parameters": {}
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\TemplateRepository")
 * @UniqueEntity(fields="name", message="Name déjà pris")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 */
class Template
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
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $html;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $text;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getHtml(): string
    {
        return (string) $this->html;
    }

    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function getText(): string
    {
        return (string) $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
