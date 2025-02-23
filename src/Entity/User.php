<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le prénom est un champs obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le prénom doit contenir au plus {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Your first name cannot contain a number',
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le nom est un champs obligatoire")]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom doit contenir au plus {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'Le nom ne peut pas contenir des caractères numériques',
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 60)]
    #[Assert\NotBlank(message:"L'adresse email est un champs obligatoire")]
    #[Assert\Email(message:"L'adresse email {{ value }} n'est pas valide")]
    #[Assert\Length(
        max: 60,
        maxMessage: "L'adresse email doit contenir au moins {{ limit }} caractères",
    )]
    private ?string $userEmail = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"Le mot de passe est un champs obligatoire")]
    #[Assert\Length(
        min: 8,
        max: 20,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le mot de passe doit contenir au plus {{ limit }} caractères',
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,20}$/',
        message: 'Le mot de passe est trop faible. Il doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
    )]
    private ?string $password = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message:"Le role est un champs obligatoire")]
    private ?string $userRole = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"L'age est un champs obligatoire")]
    #[Assert\Range(
        min: 3,
        max: 100,
        notInRangeMessage: 'Vous devez avoir entre {{ min }} ans et {{ max }} ans pour vous inscrire',
    )]
    private ?int $userAge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userPicture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $docSpecialty = null;

    /**
     * @var Collection<int, Contrat>
     */
    #[ORM\OneToMany(targetEntity: Contrat::class, mappedBy: 'user')]
    private Collection $contrats;

    /**
     * @var Collection<int, Reclamation>
     */
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user')]
    private Collection $reclamations;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'user')]
    private Collection $avis;

    public function __construct()
    {
        $this->contrats = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(?string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(?string $userRole): static
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserAge(): ?int
    {
        return $this->userAge;
    }

    public function setUserAge(?int $userAge): static
    {
        $this->userAge = $userAge;

        return $this;
    }

    public function getUserPicture(): ?string
    {
        return $this->userPicture;
    }

    public function setUserPicture(?string $userPicture): static
    {
        $this->userPicture = $userPicture;

        return $this;
    }

    public function getDocSpecialty(): ?string
    {
        return $this->docSpecialty;
    }

    public function setDocSpecialty(?string $docSpecialty): static
    {
        $this->docSpecialty = $docSpecialty;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->userEmail;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary sensitive data, clear it here.
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
            $contrat->setUser($this);
        }

        return $this;
    }

    public function removeContrat(Contrat $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getUser() === $this) {
                $contrat->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUser() === $this) {
                $reclamation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }
}
