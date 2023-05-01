<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File;
use Gedmo\Mapping\Annotation as Gedmo;


#[ORM\Entity(repositoryClass: ArticleRepository::class)]
/**
 * @Vich\Uploadable
 */
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
     * @var FILE|null
    * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="articles", fileNameProperty="imageName")
     */
    private $imageFile;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: Status::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class)]
    private $comments;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Alike::class)]
    private $alikes;

    #[ORM\Column(type: 'string', length: 100, unique:true)]
    #[Gedmo\Slug(fields: ['title'])]
    private $slug;

    #[ORM\Column(type: 'boolean')]
    private $isValidate = false;

    /**
     * @return bool
     * return true if user has liked the article
     */
    public function isAlikedByUser($user){
        // par défaut à false
        $liked = false;
        // je parcours tout les likes de l'article
        foreach ($this->alikes as $key => $alike) {
            // si le profil utilisateur est égale au userProfile en param
            if($alike->getUserProfile() == $user->getUserProfile()){
                $liked = true;
                // sort de la boucle, car le traitement est terminé (optimisation)
                break;
            }
        }
        return $liked;
    }

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->alikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

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

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * Get maxSize = "1024k",
     *
     * @return  FILE|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set maxSize = "1024k",
     *
     * @param  FILE|null  $imageFile  maxSize = "1024k",
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
            $alike->setArticle($this);
        }

        return $this;
    }

    public function removeAlike(Alike $alike): self
    {
        if ($this->alikes->removeElement($alike)) {
            // set the owning side to null (unless already changed)
            if ($alike->getArticle() === $this) {
                $alike->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set the value of slug
     *
     * @return  self
     */ 
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function isIsValidate(): ?bool
    {
        return $this->isValidate;
    }

    public function setIsValidate(bool $isValidate): self
    {
        $this->isValidate = $isValidate;

        return $this;
    }
}
