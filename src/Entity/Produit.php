<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomProduit = null;

    #[ORM\Column]
    private ?float $prixProduit = null;

    #[ORM\Column]
    private ?int $qteProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $statutProduit = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorieProduit = null;

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
}
