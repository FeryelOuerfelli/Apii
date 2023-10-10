<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Veuillez entrer les symptomes")]
    #[Assert\Length (
    min:5,
    minMessage:"La saisie est trop courte. Veuillez entrer au moins 5 caractères ")]
    private ?string $symptomes = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir la date du rendez-vous")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)] 
    #[Assert\NotBlank(message:"veuillez choisir une heure de debut")]
    private ?\DateTimeInterface $heure_debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir une heure de fin")]
    private ?\DateTimeInterface $heure_fin = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?User $patient = null;

    #[ORM\OneToOne(inversedBy: 'rendezVous', cascade: ['persist', 'remove'])]
    private ?Consultation $consultation = null;

    #[ORM\ManyToOne(inversedBy: 'les_rendez_vous')]
    private ?Planning $planning = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(?string $symptomes): self
    {
        $this->symptomes = $symptomes;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->date_de_creation;
    }

    public function setDateDeCreation(\DateTimeInterface $date_de_creation): self
    {
        $this->date_de_creation = $date_de_creation;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date = null): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut  = null): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(\DateTimeInterface $heure_fin  = null): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        return $this;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): self
    {
        $this->planning = $planning;

        return $this;
    }
}
