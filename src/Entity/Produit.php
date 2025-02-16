<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: "Le nom doit contenir au moins 2 caractères",
        maxMessage: "Le nom ne peut dépasser 30 caractères"
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
    private ?string $statutProduit;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Une catégorie doit être sélectionnée")]
    private ?Categorie $categorieProduit = null;
     /**
     * Cette propriété est ajoutée pour gérer la relation avec la commande
     * Elle n'est pas nécessairement persistée si tu ne souhaites pas avoir une relation à travers une entité intermédiaire
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'produits')]
    private $commandes;

    public function __construct()
    {
        // Défaut à "indisponible" à la création
        $this->statutProduit = self::STATUT_INDISPONIBLE;
        $this->commandes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nomProduit ?? '';
    }

    #[ORM\PreUpdate]
    public function updateStatut(): void
    {
        // Met à jour le statut UNIQUEMENT lors d'une modification
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
     // Méthodes pour gérer les commandes liées à ce produit
     public function addCommande(Commande $commande): self
     {
         if (!$this->commandes->contains($commande)) {
             $this->commandes[] = $commande;
         }
 
         return $this;
     }
 
     public function removeCommande(Commande $commande): self
     {
         $this->commandes->removeElement($commande);
 
         return $this;
     }
}