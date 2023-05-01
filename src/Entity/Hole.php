<?php

namespace App\Entity;

use App\Repository\HoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File;

#[ORM\Entity(repositoryClass: HoleRepository::class)]
/**
 * @Vich\Uploadable
 */
class Hole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'text', nullable: true)]
    private $tips;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'holes')]
    private $course;

    #[ORM\Column(type: 'float', nullable: true)]
    private $distanceBleu;

    #[ORM\Column(type: 'float')]
    private $distanceRouge;

    #[ORM\Column(type: 'float')]
    private $distanceJaune;

    #[ORM\Column(type: 'float')]
    private $distanceBlanc;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    /**
     * @var FILE|null
     * @Assert\File(
     *     mimeTypes = {"image/jpeg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="holes", fileNameProperty="imageName")
     */
    private $imageFile;

    public function getId(): ?int
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


    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getDistanceBleu(): ?float
    {
        return $this->distanceBleu;
    }

    public function setDistanceBleu(?float $distanceBleu): self
    {
        $this->distanceBleu = $distanceBleu;

        return $this;
    }

    public function getDistanceRouge(): ?float
    {
        return $this->distanceRouge;
    }

    public function setDistanceRouge(float $distanceRouge): self
    {
        $this->distanceRouge = $distanceRouge;

        return $this;
    }

    public function getDistanceJaune(): ?float
    {
        return $this->distanceJaune;
    }

    public function setDistanceJaune(float $distanceJaune): self
    {
        $this->distanceJaune = $distanceJaune;

        return $this;
    }

    public function getDistanceBlanc(): ?float
    {
        return $this->distanceBlanc;
    }

    public function setDistanceBlanc(float $distanceBlanc): self
    {
        $this->distanceBlanc = $distanceBlanc;

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

    /**
     * Get mimeTypes = {"image/jpeg", "image/png"}
     *
     * @return  FILE|null
     */ 
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set mimeTypes = {"image/jpeg", "image/png"}
     *
     * @param  FILE|null  $imageFile  mimeTypes = {"image/jpeg", "image/png"}
     *
     * @return  self
     */ 
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
