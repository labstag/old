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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Traits\Bookmark;
use Labstag\Entity\Traits\Email;
use Labstag\Entity\Traits\History;
use Labstag\Entity\Traits\OauthConnectUser;
use Labstag\Entity\Traits\Phone;
use Labstag\Entity\Traits\Post;
use Labstag\Entity\Traits\Timestampable;
use Labstag\Resolver\Mutation\DeleteResolver;
use Labstag\Resolver\Mutation\EmptyResolver;
use Labstag\Resolver\Mutation\RestoreResolver;
use Labstag\Resolver\Query\EntityResolver;
use Labstag\Resolver\Query\TrashCollectionResolver;
use Labstag\Resolver\Query\TrashResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "username": "partial",
 *     "email": "partial",
 *     "enable": "exact"
 * })
 * @ApiFilter(OrderFilter::class, properties={"id", "username"}, arguments={"orderParameterName": "order"})
 * @ApiResource(
 *     attributes={
 *         "normalization_context": {"groups": {"get"}},
 *         "denormalization_context": {"groups": {"get"}}
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
 * @ORM\Entity(repositoryClass="Labstag\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    use SoftDeleteableEntity;
    use Timestampable;
    use Bookmark;
    use Email;
    use History;
    use OauthConnectUser;
    use Phone;
    use Post;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     * @ApiProperty(iri="https://schema.org/identifier")
     * @Groups({"get"})
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Groups({"get"})
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, options={"default": true})
     * @Assert\NotBlank
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email."
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"get"})
     *
     * @var array
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=true)
     * @Groups({"write"})
     *
     * @var string|null
     */
    private $apiKey;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     * @Groups({"get"})
     */
    private $enable;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get"})
     *
     * @var string
     */
    private $avatar;

    /**
     * @Vich\UploadableField(mapping="upload_file", fileNameProperty="avatar")
     * @Assert\File(mimeTypes={"image/*"})
     * @Groups({"get"})
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Post", mappedBy="refuser")
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\OauthConnectUser", mappedBy="refuser", orphanRemoval=true)
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $oauthConnectUsers;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\History", mappedBy="refuser")
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Bookmark", mappedBy="refuser")
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Email", mappedBy="refuser", cascade={"all"})
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $emails;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Phone", mappedBy="refuser", cascade={"all"})
     * @ApiSubresource
     * @Groups({"get"})
     *
     * @var ArrayCollection
     */
    private $phones;

    /**
     * @Gedmo\Versioned
     * @ORM\Column(type="boolean")
     */
    private $lost;

    public function __construct()
    {
        $this->enable            = true;
        $this->lost              = false;
        $this->posts             = new ArrayCollection();
        $this->oauthConnectUsers = new ArrayCollection();
        $this->histories         = new ArrayCollection();
        $this->bookmarks         = new ArrayCollection();
        $this->emails            = new ArrayCollection();
        $this->phones            = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getUsername();
    }

    public function setImageFile(File $image = null): self
    {
        $this->imageFile = $image;
        if ($image) {
            $dateTimeImmutable = new DateTimeImmutable();
            $dateTime          = new DateTime();
            $dateTime->setTimestamp($dateTimeImmutable->getTimestamp());
            $this->updatedAt = $dateTime;
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    /**
     * @param mixed $role
     */
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

    public function getApiKey(): ?string
    {
        return (string) $this->apiKey;
    }

    public function setApiKey(?string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @see UserInterface
     *
     * @return string|null
     */
    public function getSalt()
    {
        return '';
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     *
     * @return mixed;
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
     *
     * @param string $serialized
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
            ['allowed_classes' => false]
        );
    }

    /**
     * @return string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function isLost(): ?bool
    {
        return $this->lost;
    }

    public function setLost(bool $lost): self
    {
        $this->lost = $lost;

        return $this;
    }
}
