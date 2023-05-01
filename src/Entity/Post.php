<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: CommentPost::class)]
    private $commentPosts;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: AlikePost::class)]
    private $alikePosts;

    /**
     * @return bool
     * return true if user has liked the article
     */
    public function isAlikedPostByUser($user){
        // par défaut à false
        $liked = false;
        // je parcours tout les likes de l'article
        foreach ($this->alikePosts as $key => $alikePosts) {
            // si le profil utilisateur est égale au userProfile en param
            if($alikePosts->getUserProfile() == $user->getUserProfile()){
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
        $this->commentPosts = new ArrayCollection();
        $this->alikePosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @return Collection<int, CommentPost>
     */
    public function getCommentPosts(): Collection
    {
        return $this->commentPosts;
    }

    public function addCommentPost(CommentPost $commentPost): self
    {
        if (!$this->commentPosts->contains($commentPost)) {
            $this->commentPosts[] = $commentPost;
            $commentPost->setPost($this);
        }

        return $this;
    }

    public function removeCommentPost(CommentPost $commentPost): self
    {
        if ($this->commentPosts->removeElement($commentPost)) {
            // set the owning side to null (unless already changed)
            if ($commentPost->getPost() === $this) {
                $commentPost->setPost(null);
            }
        }

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
            $alikePost->setPost($this);
        }

        return $this;
    }

    public function removeAlikePost(AlikePost $alikePost): self
    {
        if ($this->alikePosts->removeElement($alikePost)) {
            // set the owning side to null (unless already changed)
            if ($alikePost->getPost() === $this) {
                $alikePost->setPost(null);
            }
        }

        return $this;
    }
}
