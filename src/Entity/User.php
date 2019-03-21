<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, options={"default":true})
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=true))
     */
    private $apiKey;

    /**
     * @ORM\Column(type="boolean", options={"default":true}))
     */
    private $enable;

    public function __construct()
    {
        $this->enable = true;
    }

    public function __toString()
    {
        return (string) $this->getUsername();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isEnable()
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function addRole($role): self
    {
        $roles       = $this->roles;
        $roles[]     = $role;
        $this->roles = array_unique($roles);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getApiKey(): string
    {
        return (string) $this->apiKey;
    }

    public function setApiKey($apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->enable,
            ]
        );
    }
 
    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->password,
            $this->enable,
        ] = unserialize(
            $serialized,
            [
                'allowed_classes' => false
            ]
        );
    }

    function getPlainPassword()
    {
        return $this->plainPassword;
    }

    function setPlainPassword($plainPassword)
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;
    }

}
