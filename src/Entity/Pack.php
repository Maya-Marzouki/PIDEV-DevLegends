<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackRepository::class)]
class Pack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom du pack est requis")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Le nom du pack doit avoir au moins {{ limit }} caractéres',
        maxMessage: 'Le nom du pack doit avoir au plus {{ limit }} caractéres',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Le nom du pack ne peut pas contenir des chiffres',
     )]
    private ?string $nomPack = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description du pack est requise")]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'La description du pack doit avoir au moins {{ limit }} caractéres',
        maxMessage: 'La description du pack doit avoir au plus {{ limit }} caractéres',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'La description du pack ne peut pas contenir des chiffres',
    )]
    private ?string $descriptPack = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix du pack est requis")]
    #[Assert\GreaterThan(0,message:"Le prix du pack doit étre positif")]
    private ?float $prixPack = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La durée du pack est requise")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'La durée du pack doit avoir au moins {{ limit }} caractéres',
        maxMessage: 'La durée du pack doit avoir au plus {{ limit }} caractéres',
    )]
    
    private ?string $dureePack = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoPack = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPack(): ?string
    {
        return $this->nomPack;
    }

    public function setNomPack(?string $nomPack): static
    {
        $this->nomPack = $nomPack;

        return $this;
    }

    public function getDescriptPack(): ?string
    {
        return $this->descriptPack;
    }

    public function setDescriptPack(?string $descriptPack): static
{
    $this->descriptPack = $descriptPack;

    return $this;
}

    public function getPrixPack(): ?float
    {
        return $this->prixPack;
    }

    public function setPrixPack(?float $prixPack): static
    {
        $this->prixPack = $prixPack;

        return $this;
    }

    public function getDureePack(): ?string
    {
        return $this->dureePack;
    }

    public function setDureePack(?string $dureePack): static
    {
        $this->dureePack = $dureePack;

        return $this;
    }

    public function getPhotoPack(): ?string
    {
        return $this->photoPack;
    }

    public function setPhotoPack(?string $photoPack): static
    {
        $this->photoPack = $photoPack;

        return $this;
    }
}