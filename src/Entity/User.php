<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ApiFilter(SearchFilter::class, properties={
 *  "id": "exact",
 *  "username": "partial",
 *  "email": "partial",
 *  "enable": "exact"
 * })
 * @ApiResource(
 *     attributes={
 *      "access_control"="is_granted('ROLE_SUPER_ADMIN')",
 *       "normalization_context"={"groups"={"get"}},
 *       "denormalization_context"={"groups"={"get"}},
 *      },
 * )
 * @ApiFilter(OrderFilter::class, properties={"id", "username"}, arguments={"orderParameterName": "order"})
 * @ORM\Entity(repositoryClass="Labstag\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Vich\Uploadable
 */
class User implements UserInterface, \Serializable
{
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     * @Groups({"get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Groups({"get"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, options={"default": true})
     * @Assert\NotBlank
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email.",
     *     checkMX=true
     * )
     * @Groups({"get"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"get"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write"})
     */
    private $password;

    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64, unique=true, nullable=true)
     * @Groups({"write"})
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
     */
    private $avatar;

    /**
     * @Vich\UploadableField(mapping="upload_file", fileNameProperty="avatar")
     * @Assert\File(mimeTypes={"image/*"})
     *
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Post", mappedBy="refuser")
     * @Groups({"get"})
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\OauthConnectUser", mappedBy="refuser", orphanRemoval=true)
     * @Groups({"get"})
     */
    private $oauthConnectUsers;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\History", mappedBy="refuser")
     * @Groups({"get"})
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Bookmark", mappedBy="refuser")
     * @Groups({"get"})
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Email", mappedBy="refuser", cascade={"all"})
     * @Groups({"get"})
     */
    private $emails;

    /**
     * @ORM\OneToMany(targetEntity="Labstag\Entity\Phone", mappedBy="refuser", cascade={"all"})
     * @Groups({"get"})
     */
    private $phones;

    public function __construct()
    {
        $this->enable            = true;
        $this->posts             = new ArrayCollection();
        $this->oauthConnectUsers = new ArrayCollection();
        $this->histories         = new ArrayCollection();
        $this->bookmarks         = new ArrayCollection();
        $this->emails            = new ArrayCollection();
        $this->phones            = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getUsername();
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
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
            ['allowed_classes' => false]
        );
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;
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
            $post->setRefuser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getRefuser() === $this) {
                $post->setRefuser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OauthConnectUser[]
     */
    public function getOauthConnectUsers(): Collection
    {
        return $this->oauthConnectUsers;
    }

    public function addOauthConnectUser(OauthConnectUser $oauthConnectUser): self
    {
        if (!$this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers[] = $oauthConnectUser;
            $oauthConnectUser->setRefuser($this);
        }

        return $this;
    }

    public function removeOauthConnectUser(OauthConnectUser $oauthConnectUser): self
    {
        if ($this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers->removeElement($oauthConnectUser);
            // set the owning side to null (unless already changed)
            if ($oauthConnectUser->getRefuser() === $this) {
                $oauthConnectUser->setRefuser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setRefuser($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getRefuser() === $this) {
                $history->setRefuser(null);
            }
        }

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
            $bookmark->setRefuser($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->contains($bookmark)) {
            $this->bookmarks->removeElement($bookmark);
            // set the owning side to null (unless already changed)
            if ($bookmark->getRefuser() === $this) {
                $bookmark->setRefuser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(Email $email): self
    {
        if (!$this->emails->contains($email)) {
            $email->setRefuser($this);
            $this->emails[] = $email;
        }

        return $this;
    }

    public function removeEmail(Email $email): self
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
            // set the owning side to null (unless already changed)
            if ($email->getRefuser() === $this) {
                $email->setRefuser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Phone[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $phone->setRefuser($this);
            $this->phones[] = $phone;
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
            // set the owning side to null (unless already changed)
            if ($phone->getRefuser() === $this) {
                $phone->setRefuser(null);
            }
        }

        return $this;
    }
}
