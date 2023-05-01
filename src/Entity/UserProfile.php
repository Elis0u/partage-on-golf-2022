<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
/**
 * @Vich\Uploadable
 */
class UserProfile implements Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $biography;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $birthday;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $avatarName;

    /**
     * @var FILE|null
    * @Assert\File(
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="userprofile_avatar", fileNameProperty="avatarName")
     */
    private $avatarFile;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\OneToOne(inversedBy: 'userProfile', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'userProfile', targetEntity: Alike::class)]
    private $alikes;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $golf;

    #[ORM\OneToMany(mappedBy: 'userProfile', targetEntity: AlikePost::class)]
    private $alikePosts;  

    public function __construct()
    {
        $this->alikes = new ArrayCollection();
        $this->alikePosts = new ArrayCollection();
    }

    public function serialize() {
        return serialize(array($this->id, $this->biography));
    }

    public function unserialize($data)
    {
        list($this->id, $this->biography) = unserialize($data);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getAvatarName(): ?string
    {
        return $this->avatarName;
    }

    public function setAvatarName(?string $avatarName): self
    {
        $this->avatarName = $avatarName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     *
     * @return  FILE|null
     */ 
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     *
     * @param  FILE|null  
     *
     * @return  self
     */ 
    public function setAvatarFile($avatarFile)
    {
        $this->avatarFile = $avatarFile;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Alike>
     */
    public function getAlikes(): Collection
    {
        return $this->alikes;
    }

    public function addAlike(Alike $alike): self
    {
        if (!$this->alikes->contains($alike)) {
            $this->alikes[] = $alike;
            $alike->setUserProfile($this);
        }

        return $this;
    }

    public function removeAlike(Alike $alike): self
    {
        if ($this->alikes->removeElement($alike)) {
            // set the owning side to null (unless already changed)
            if ($alike->getUserProfile() === $this) {
                $alike->setUserProfile(null);
            }
        }

        return $this;
    }

    public function getGolf(): ?string
    {
        return $this->golf;
    }

    public function setGolf(?string $golf): self
    {
        $this->golf = $golf;

        return $this;
    }

    /**
     * @return Collection<int, AlikePost>
     */
    public function getAlikePosts(): Collection
    {
        return $this->alikePosts;
    }

    public function addAlikePost(AlikePost $alikePost): self
    {
        if (!$this->alikePosts->contains($alikePost)) {
            $this->alikePosts[] = $alikePost;
            $alikePost->setUserProfile($this);
        }

        return $this;
    }

    public function removeAlikePost(AlikePost $alikePost): self
    {
        if ($this->alikePosts->removeElement($alikePost)) {
            // set the owning side to null (unless already changed)
            if ($alikePost->getUserProfile() === $this) {
                $alikePost->setUserProfile(null);
            }
        }

        return $this;
    }

}
