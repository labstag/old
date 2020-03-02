<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Resolver\Query\EntityResolver;
use Labstag\Resolver\Query\TrashCollectionResolver;
use Labstag\Resolver\Query\TrashResolver;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "adresse": "partial"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "adresse"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     attributes={
 *         "security": "is_granted('ROLE_ADMIN')"
 *     },
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
 * @ORM\Entity(repositoryClass="Labstag\Repository\EmailRepository")
 * @ORM\Table(
 *     uniqueConstraints={
 * @ORM\UniqueConstraint(name="user_email", columns={"refuser_id", "adresse"})
 *     }
 * )
 */
class Email
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
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\User", inversedBy="emails")
     *
     * @var User
     */
    private $refuser;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $principal;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;

    public function __construct()
    {
        $this->checked   = false;
        $this->principal = false;
    }

    public function __toString(): string
    {
        return (string) $this->getAdresse();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function isPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

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
