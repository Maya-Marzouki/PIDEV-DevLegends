<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titreFor = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFor = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuFor = null;

    #[ORM\Column(length: 255)]
    private ?string $statutFor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreFor(): ?string
    {
        return $this->titreFor;
    }

    public function setTitreFor(string $titreFor): static
    {
        $this->titreFor = $titreFor;

        return $this;
    }

    public function getDateFor(): ?\DateTimeInterface
    {
        return $this->dateFor;
    }

    public function setDateFor(\DateTimeInterface $dateFor): static
    {
        $this->dateFor = $dateFor;

        return $this;
    }

    public function getLieuFor(): ?string
    {
        return $this->lieuFor;
    }

    public function setLieuFor(string $lieuFor): static
    {
        $this->lieuFor = $lieuFor;

        return $this;
    }

    public function getStatutFor(): ?string
    {
        return $this->statutFor;
    }

    public function setStatutFor(string $statutFor): static
    {
        $this->statutFor = $statutFor;

        return $this;
    }
}
