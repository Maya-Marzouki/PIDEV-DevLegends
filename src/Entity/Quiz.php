<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[ORM\Column(length: 255)]
    // private ?string $categorieSant = null;

    #[ORM\Column(type: 'integer')]
    private ?int $score = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $etatMental = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    private ?User $user = null;

    // #[ORM\ManyToOne(inversedBy: 'quizzes')]
    // private ?Consultation $consultationQ = null;

    // /**
    //  * @var Collection<int, Question>
    //  */
    // #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quiz')]
    // private Collection $questions;

    // #[ORM\Column(length: 255)]
    // private ?string $name = null;

    // public function __construct()
    // {
    //     $this->questions = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getCategorieSant(): ?string
    // {
    //     return $this->categorieSant;
    // }

    // public function setCategorieSant(string $categorieSant): static
    // {
    //     $this->categorieSant = $categorieSant;

    //     return $this;
    // }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;
        return $this;
    }

    public function getEtatMental(): ?string
    {
        return $this->etatMental;
    }

    public function setEtatMental(string $etatMental): self
    {
        $this->etatMental = $etatMental;
        return $this;
    }

    // public function getConsultationQ(): ?Consultation
    // {
    //     return $this->consultationQ;
    // }

    // public function setConsultationQ(?Consultation $consultationQ): static
    // {
    //     $this->consultationQ = $consultationQ;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Question>
    //  */
    // public function getQuestions(): Collection
    // {
    //     return $this->questions;
    // }

    // public function addQuestion(Question $question): static
    // {
    //     if (!$this->questions->contains($question)) {
    //         $this->questions->add($question);
    //         $question->setQuiz($this);
    //     }

    //     return $this;
    // }

    // public function removeQuestion(Question $question): static
    // {
    //     if ($this->questions->removeElement($question)) {
    //         // set the owning side to null (unless already changed)
    //         if ($question->getQuiz() === $this) {
    //             $question->setQuiz(null);
    //         }
    //     }

    //     return $this;
    // }

    // public function getName(): ?string
    // {
    //     return $this->name;
    // }

    // public function setName(string $name): static
    // {
    //     $this->name = $name;

    //     return $this;
    // }

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
