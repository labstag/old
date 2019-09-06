<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
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
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Labstag\Entity\User", inversedBy="emails")
     */
    private $refuser;

    /**
     * @ORM\Column(type="string", length=255)
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

    public function __toString(): ?string
    {
        return $this->getAdresse();
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
