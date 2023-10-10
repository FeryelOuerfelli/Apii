<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir une Date de debut du planing")]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir une Date de fin du planing")]
    private ?\DateTimeInterface $date_fin = null;


    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Veuillez entrer une description")]
    #[Assert\Length(
    min:5, 
    minMessage:"La saisie est trop courte. Veuillez entrer au moins 5 caractères ")]
    private ?string $description = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir une heure de debut")]
    private ?\DateTimeInterface $heure_debut = null;
    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message:"veuillez choisir une heure de fin")]
    private ?\DateTimeInterface $heure_fin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_modification = null;

    #[ORM\OneToMany(mappedBy: 'planning', targetEntity: RendezVous::class, cascade: ['remove'],)]
    private Collection $les_rendez_vous;

    #[ORM\ManyToOne(inversedBy: 'plannings')]
    private ?User $medecin = null;

    public function __construct()
    {
        $this->les_rendez_vous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut = null): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin = null): self
    {
        $this->date_fin = $date_fin;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut = null): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(\DateTimeInterface $heure_fin = null): self
    {
        $this->heure_fin = $heure_fin;

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

    public function getDateDeModification(): ?\DateTimeInterface
    {
        return $this->date_de_modification;
    }

    public function setDateDeModification(\DateTimeInterface $date_de_modification): self
    {
        $this->date_de_modification = $date_de_modification;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getLesRendezVous(): Collection
    {
        return $this->les_rendez_vous;
    }

    public function addLesRendezVou(RendezVous $lesRendezVou): self
    {
        if (!$this->les_rendez_vous->contains($lesRendezVou)) {
            $this->les_rendez_vous->add($lesRendezVou);
            $lesRendezVou->setPlanning($this);
        }

        return $this;
    }

    public function removeLesRendezVou(RendezVous $lesRendezVou): self
    {
        if ($this->les_rendez_vous->removeElement($lesRendezVou)) {
            // set the owning side to null (unless already changed)
            if ($lesRendezVou->getPlanning() === $this) {
                $lesRendezVou->setPlanning(null);
            }
        }

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }
}
