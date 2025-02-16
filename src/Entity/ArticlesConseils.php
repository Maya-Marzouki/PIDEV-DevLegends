<?php

namespace App\Entity;

use App\Repository\ArticlesConseilsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticlesConseilsRepository::class)]
class ArticlesConseils
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le titre de l'article ne peut pas être vide.")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Le titre doit contenir au moins {{ limit }} caractères.",
        maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $titreArticle = null;

    #[ORM\Column(length: 4294967295)]
    #[Assert\NotBlank(message: "Le contenu de l'article ne peut pas être vide.")]
    #[Assert\Length(
        min: 20,
        minMessage: "Le contenu doit contenir au moins {{ limit }} caractères."
    )]
    private ?string $contenuArticle = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La catégorie ne peut pas être vide.")]
    private ?string $categorieMentalArticle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\File(
        maxSize: "2M",
        mimeTypes: ["image/jpeg", "image/png"],
        mimeTypesMessage: "Veuillez uploader une image valide (JPG ou PNG)."
    )]
    // #[Assert\NotBlank(message: "L'URL de l'image ne peut pas être vide.")]
    // #[Assert\Url(message: "L'URL de l'image n'est pas valide.")]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titreArticle;
    }

    public function setTitreArticle(?string $titreArticle): static
    {
        $this->titreArticle = $titreArticle;

        return $this;
    }

    public function getContenuArticle(): ?string
    {
        return $this->contenuArticle;
    }

    public function setContenuArticle(?string $contenuArticle): static
    {
        $this->contenuArticle = $contenuArticle;

        return $this;
    }

    public function getCategorieMentalArticle(): ?string
    {
        return $this->categorieMentalArticle;
    }

    public function setCategorieMentalArticle(?string $categorieMentalArticle): static
    {
        $this->categorieMentalArticle = $categorieMentalArticle;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
