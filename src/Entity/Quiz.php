<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $questionQuiz = null;

    #[ORM\Column(length: 255)]
    private ?string $categorieSant = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    private ?Consultation $consultationQ = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionQuiz(): ?string
    {
        return $this->questionQuiz;
    }

    public function setQuestionQuiz(string $questionQuiz): static
    {
        $this->questionQuiz = $questionQuiz;

        return $this;
    }

    public function getCategorieSant(): ?string
    {
        return $this->categorieSant;
    }

    public function setCategorieSant(string $categorieSant): static
    {
        $this->categorieSant = $categorieSant;

        return $this;
    }

    public function getConsultationQ(): ?Consultation
    {
        return $this->consultationQ;
    }

    public function setConsultationQ(?Consultation $consultationQ): static
    {
        $this->consultationQ = $consultationQ;

        return $this;
    }
}
