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
    #[Assert\NotBlank(message:"Pack Name is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your pack name must be at least {{ limit }} characters long',
        maxMessage: 'Your pack name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your pack name cannot contain a number',
     )]
    private ?string $nomPack = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Pack description is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your Pack description  must be at least {{ limit }} characters long',
        maxMessage: 'Your Pack description  cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your Pack description  cannot contain a number',
    )]
    private ?string $descriptPack = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"Pack price is required")]
    #[Assert\GreaterThan(0,message:"The pack price must be positive")]
    private ?float $prixPack = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Pack duration is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your Pack duration  must be at least {{ limit }} characters long',
        maxMessage: 'Your Pack duration  cannot be longer than {{ limit }} characters',
    )]
    
    private ?string $dureePack = null;

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
}
