<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Produit
{
    public const STATUT_DISPONIBLE = 'disponible';
    public const STATUT_INDISPONIBLE = 'indisponible';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne peut dépasser {{ limit }} caractères"
    )]
    private ?string $nomProduit = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    private ?float $prixProduit = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero(message: "La quantité ne peut être négative")]
    private ?int $qteProduit = 0;

    #[ORM\Column(length: 255)]
    private ?string $statutProduit = self::STATUT_INDISPONIBLE;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Une catégorie doit être sélectionnée")]
    private ?Categorie $categorieProduit = null;

    public function __toString(): string
    {
        return $this->nomProduit ?? '';
    }

    // Lifecycle Callbacks

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateStatut(): void
    {
        $this->statutProduit = $this->qteProduit > 0 
            ? self::STATUT_DISPONIBLE 
            : self::STATUT_INDISPONIBLE;
    }

    // Getters et Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): static
    {
        $this->nomProduit = $nomProduit;
        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prixProduit;
    }

    public function setPrixProduit(float $prixProduit): static
    {
        $this->prixProduit = $prixProduit;
        return $this;
    }

    public function getQteProduit(): ?int
    {
        return $this->qteProduit;
    }

    public function setQteProduit(int $qteProduit): static
    {
        $this->qteProduit = $qteProduit;
        return $this;
    }

    public function getStatutProduit(): ?string
    {
        return $this->statutProduit;
    }

    public function setStatutProduit(string $statutProduit): static
    {
        $this->statutProduit = $statutProduit;
        return $this;
    }

    public function getCategorieProduit(): ?Categorie
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(?Categorie $categorieProduit): static
    {
        $this->categorieProduit = $categorieProduit;
        return $this;
    }

    public function getStatutProduitLabel(): string
    {
        return match($this->statutProduit) {
            self::STATUT_DISPONIBLE => 'Disponible',
            self::STATUT_INDISPONIBLE => 'Indisponible',
            default => 'Statut inconnu',
        };
    }
}