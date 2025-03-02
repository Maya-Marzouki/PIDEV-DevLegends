<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection; 
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $firstName = null;

    #[ORM\Column(length: 20)]
    private ?string $lastName = null;

    #[ORM\Column(length: 30)]
    private ?string $userEmail = null;

    #[ORM\Column(length: 10)]
    private ?string $pswrd = null;

    #[ORM\Column(length: 20)]
    private ?string $userRole = null;

    #[ORM\Column]
    private ?int $userAge = null;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Contrat::class)]
    private Collection $contrats;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    public function getPswrd(): ?string
    {
        return $this->pswrd;
    }

    public function setPswrd(string $pswrd): static
    {
        $this->pswrd = $pswrd;

        return $this;
    }

    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): static
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getUserAge(): ?int
    {
        return $this->userAge;
    }

    public function setUserAge(int $userAge): static
    {
        $this->userAge = $userAge;

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
            $contrat->setClient($this);
        }

        return $this;
    }
}

