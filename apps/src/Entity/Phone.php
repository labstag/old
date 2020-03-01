<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Resolver\Mutation\EmptyResolver;
use Labstag\Resolver\Mutation\RestoreResolver;
use Labstag\Resolver\Query\EntityResolver;
use Labstag\Resolver\Query\TrashCollectionResolver;
use Labstag\Resolver\Query\TrashResolver;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "numero": "partial",
 *     "type": "partial",
 *     "checked": "exact"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "numero"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('ROLE_ADMIN')"
 *     },
 *     graphql={
 *         "item_query": {"security": "is_granted('ROLE_ADMIN')"},
 *         "collection_query": {"security": "is_granted('ROLE_ADMIN')"},
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
 *         "delete": {"security": "is_granted('ROLE_ADMIN')"},
 *         "update": {"security": "is_granted('ROLE_ADMIN')"},
 *         "create": {"security": "is_granted('ROLE_ADMIN')"},
 *         "collection": {"security": "is_granted('ROLE_ADMIN')"},
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
 * @ORM\Entity(repositoryClass="Labstag\Repository\PhoneRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 * @ORM\UniqueConstraint(name="user_phone", columns={"refuser_id", "numero"})
 *     }
 * )
 */
class Phone
{

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
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\User", inversedBy="phones")
     *
     * @var User
     */
    private $refuser;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;

    public function __construct()
    {
        $this->checked = false;
    }

    public function __toString(): string
    {
        return (string) $this->getNumero();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
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

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
