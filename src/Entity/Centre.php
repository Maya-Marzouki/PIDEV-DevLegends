<?php

namespace App\Entity;

use App\Repository\CentreRepository;
use Symfony\Component\Validator\Constraints as Assert;
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
    #[Assert\NotBlank(message:"Center Name is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your center name must be at least {{ limit }} characters long',
        maxMessage: 'Your center name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your center name cannot contain a number',
    )]
    private ?string $nomCentre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Center address is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your center address must be at least {{ limit }} characters long',
        maxMessage: 'Your center address cannot be longer than {{ limit }} characters',
    )]
    private ?string $adresseCentre = null;


    #[ORM\Column(length: 12)] // Longueur maximale de 12 caractères (+21612345678)
    #[Assert\NotBlank(message: "Le numéro de téléphone est requis.")]
    // #[Assert\Regex(
    //     pattern: '/^\+216\d{8}$/',
    //     message: 'Le numéro de téléphone doit commencer par +216 et être suivi de 8 chiffres.'
    // )]
    private ?string $telCentre = null;



    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Email is required")]
    #[Assert\Email(message:"The email {{ value }} is not valid")]
    private ?string $emailCentre = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Center specialty is required")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Your center specialty must be at least {{ limit }} characters long',
        maxMessage: 'Your center specialty cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your center specialty cannot contain a number',
    )]
    private ?string $specialiteCentre = null;


    #[ORM\Column]
    #[Assert\NotBlank(message:"center capacity is required")]
    #[Assert\GreaterThan(0,message:"The center capacity must be positive")]
    private ?int $capaciteCentre = null;

    #[ORM\Column(length: 255)]
   // #[Assert\Url(message: "The provided URL is not valid.")]
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

    public function setNomCentre(?string $nomCentre): static
    {
        $this->nomCentre = $nomCentre;

        return $this;
    }

    public function getAdresseCentre(): ?string
    {
        return $this->adresseCentre;
    }

    public function setAdresseCentre(?string $adresseCentre): static
    {
        $this->adresseCentre = $adresseCentre;

        return $this;
    }

    public function getTelCentre(): ?int
    {
        return $this->telCentre;
    }

    public function setTelCentre(?int $telCentre): static
    {
        $this->telCentre = $telCentre;

        return $this;
    }

    public function getEmailCentre(): ?string
    {
        return $this->emailCentre;
    }

    public function setEmailCentre(?string $emailCentre): static
    {
        $this->emailCentre = $emailCentre;

        return $this;
    }

    public function getSpecialiteCentre(): ?string
    {
        return $this->specialiteCentre;
    }

    public function setSpecialiteCentre(?string $specialiteCentre): static
    {
        $this->specialiteCentre = $specialiteCentre;

        return $this;
    }

    public function getCapaciteCentre(): ?int
    {
        return $this->capaciteCentre;
    }

    public function setCapaciteCentre(?int $capaciteCentre): static
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

    /*
     * @return Collection<int, Contrat>
     
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
    }*/
}
