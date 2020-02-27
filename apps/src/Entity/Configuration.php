<?php

namespace Labstag\Entity;

use Labstag\CollectionResolver\TrashCollectionResolver;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Labstag\Controller\Api\ConfigurationApi;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "name": "partial",
 *     "value": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "name"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     attributes={
 *         "access_control": "is_granted('ROLE_ADMIN')"
 *     },
 *     graphql={
 *       "trashCollection"={
 *            "collection_query"=TrashCollectionResolver::class
 *       }
 *     },
 *     itemOperations={
 *         "get",
 *         "put",
 *         "delete",
 *         "api_configurationtrash": {
 *             "method": "GET",
 *             "path": "/configurations/trash",
 *             "controller": ConfigurationApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Corbeille",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_configurationtrashdelete": {
 *             "method": "DELETE",
 *             "path": "/configurations/trash",
 *             "controller": ConfigurationApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Remove",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_configurationrestore": {
 *             "method": "POST",
 *             "path": "/configurations/restore",
 *             "controller": ConfigurationApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Restore",
 *                 "parameters": {}
 *             }
 *         },
 *         "api_configurationempty": {
 *             "method": "POST",
 *             "path": "/configurations/empty",
 *             "controller": ConfigurationApi::class,
 *             "read": false,
 *             "swagger_context": {
 *                 "summary": "Empty",
 *                 "parameters": {}
 *             }
 *         }
 *     }
 * )
 * @ORM\Entity(repositoryClass="Labstag\Repository\ConfigurationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Configuration
{
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
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="object", nullable=true)
     *
     * @var mixed
     */
    private $value;

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
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }
}
