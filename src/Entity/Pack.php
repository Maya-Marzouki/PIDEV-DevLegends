<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomPack = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptPack = null;

    #[ORM\Column]
    private ?float $prixPack = null;

    #[ORM\Column(length: 255)]
    private ?string $dureePack = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPack(): ?string
    {
        return $this->nomPack;
    }

    public function setNomPack(string $nomPack): static
    {
        $this->nomPack = $nomPack;

        return $this;
    }

    public function getDescriptPack(): ?string
    {
        return $this->descriptPack;
    }

    public function setDescriptPack(string $descriptPack): static
    {
        $this->descriptPack = $descriptPack;

        return $this;
    }

    public function getPrixPack(): ?float
    {
        return $this->prixPack;
    }

    public function setPrixPack(float $prixPack): static
    {
        $this->prixPack = $prixPack;

        return $this;
    }

    public function getDureePack(): ?string
    {
        return $this->dureePack;
    }

    public function setDureePack(string $dureePack): static
    {
        $this->dureePack = $dureePack;

        return $this;
    }
}
