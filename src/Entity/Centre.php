<?php

namespace App\Entity;

use App\Repository\CentreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CentreRepository::class)]
class Centre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseCentre = null;

    #[ORM\Column]
    private ?int $telCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $emailCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $specialiteCentre = null;

    #[ORM\Column]
    private ?int $capaciteCentre = null;

    #[ORM\Column(length: 255)]
    private ?string $photoCentre = null;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'centre')]
    private Collection $contrats;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->nomCentre;
    }

    public function setNomCentre(string $nomCentre): static
    {
        $this->nomCentre = $nomCentre;

        return $this;
    }

    public function getAdresseCentre(): ?string
    {
        return $this->adresseCentre;
    }

    public function setAdresseCentre(string $adresseCentre): static
    {
        $this->adresseCentre = $adresseCentre;

        return $this;
    }

    public function getTelCentre(): ?int
    {
        return $this->telCentre;
    }

    public function setTelCentre(int $telCentre): static
    {
        $this->telCentre = $telCentre;

        return $this;
    }

    public function getEmailCentre(): ?string
    {
        return $this->emailCentre;
    }

    public function setEmailCentre(string $emailCentre): static
    {
        $this->emailCentre = $emailCentre;

        return $this;
    }

    public function getSpecialiteCentre(): ?string
    {
        return $this->specialiteCentre;
    }

    public function setSpecialiteCentre(string $specialiteCentre): static
    {
        $this->specialiteCentre = $specialiteCentre;

        return $this;
    }

    public function getCapaciteCentre(): ?int
    {
        return $this->capaciteCentre;
    }

    public function setCapaciteCentre(int $capaciteCentre): static
    {
        $this->capaciteCentre = $capaciteCentre;

        return $this;
    }

    public function getPhotoCentre(): ?string
    {
        return $this->photoCentre;
    }

    public function setPhotoCentre(string $photoCentre): static
    {
        $this->photoCentre = $photoCentre;

        return $this;
    }

    /**
     * @return Collection<int, Contrat>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(Contrat $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setCentre($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getCentre() === $this) {
                $contrat->setCentre(null);
            }
        }

        return $this;
    }
}
