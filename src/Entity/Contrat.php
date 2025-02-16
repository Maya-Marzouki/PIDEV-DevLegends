<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date début est requise")]
    #[Assert\GreaterThanOrEqual("today", message: "La date début doit étre supérieur ou égale à la date d'aujourd'hui")]
    private ?\DateTimeInterface $datdebCont = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "La date fin est requise")]
    #[Assert\GreaterThanOrEqual(propertyPath: "datdebCont", message: "La date début doit étre supérieur ou égale à la date du début")]
    private ?\DateTimeInterface $datfinCont = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La méthode du paiement est requise")]
    private ?string $modpaimentCont = null;

    #[ORM\Column]
    //#[Assert\NotBlank(message: "The auto-renewal field is required")]
    private ?bool $renouvAutoCont = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?Centre $centre = null;

    #[ORM\ManyToOne(inversedBy: 'contrats')]
    private ?User $user = null;  // Relation ManyToOne vers User (Client)
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatdebCont(): ?\DateTimeInterface
    {
        return $this->datdebCont;
    }

    public function setDatdebCont(?\DateTimeInterface $datdebCont): static
    {
        $this->datdebCont = $datdebCont;

        return $this;
    }

    public function getDatfinCont(): ?\DateTimeInterface
    {
        return $this->datfinCont;
    }

    public function setDatfinCont(?\DateTimeInterface $datfinCont): static
    {
        $this->datfinCont = $datfinCont;

        return $this;
    }

    public function getModpaimentCont(): ?string
    {
        return $this->modpaimentCont;
    }

    public function setModpaimentCont(?string $modpaimentCont): static
    {
        $this->modpaimentCont = $modpaimentCont;

        return $this;
    }

    public function isRenouvAutoCont(): ?bool
    {
        return $this->renouvAutoCont;
    }

    public function setRenouvAutoCont(?bool $renouvAutoCont): static
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }
}