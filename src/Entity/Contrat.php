<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datdebCont = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datfinCont = null;

    #[ORM\Column(length: 255)]
    private ?string $modpaimentCont = null;

    #[ORM\Column]
    private ?bool $renouvAutoCont = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?Centre $centre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatdebCont(): ?\DateTimeInterface
    {
        return $this->datdebCont;
    }

    public function setDatdebCont(\DateTimeInterface $datdebCont): static
    {
        $this->datdebCont = $datdebCont;

        return $this;
    }

    public function getDatfinCont(): ?\DateTimeInterface
    {
        return $this->datfinCont;
    }

    public function setDatfinCont(\DateTimeInterface $datfinCont): static
    {
        $this->datfinCont = $datfinCont;

        return $this;
    }

    public function getModpaimentCont(): ?string
    {
        return $this->modpaimentCont;
    }

    public function setModpaimentCont(string $modpaimentCont): static
    {
        $this->modpaimentCont = $modpaimentCont;

        return $this;
    }

    public function isRenouvAutoCont(): ?bool
    {
        return $this->renouvAutoCont;
    }

    public function setRenouvAutoCont(bool $renouvAutoCont): static
    {
        $this->renouvAutoCont = $renouvAutoCont;

        return $this;
    }

    public function getCentre(): ?Centre
    {
        return $this->centre;
    }

    public function setCentre(?Centre $centre): static
    {
        $this->centre = $centre;

        return $this;
    }
}
