<?php

namespace App\Entity;

use App\Repository\ArticlesConseilsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesConseilsRepository::class)]
class ArticlesConseils
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreArticle = null;

    #[ORM\Column(length: 255)]
    private ?string $contenuArticle = null;

    #[ORM\Column(length: 255)]
    private ?string $categorieMentalArticle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreArticle(): ?string
    {
        return $this->titreArticle;
    }

    public function setTitreArticle(string $titreArticle): static
    {
        $this->titreArticle = $titreArticle;

        return $this;
    }

    public function getContenuArticle(): ?string
    {
        return $this->contenuArticle;
    }

    public function setContenuArticle(string $contenuArticle): static
    {
        $this->contenuArticle = $contenuArticle;

        return $this;
    }

    public function getCategorieMentalArticle(): ?string
    {
        return $this->categorieMentalArticle;
    }

    public function setCategorieMentalArticle(string $categorieMentalArticle): static
    {
        $this->categorieMentalArticle = $categorieMentalArticle;

        return $this;
    }
}
