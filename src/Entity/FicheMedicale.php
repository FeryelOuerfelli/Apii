<?php

namespace App\Entity;

use App\Repository\FicheMedicaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheMedicaleRepository::class)]
class FicheMedicale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $allergies = null;

    #[ORM\Column(length: 255)]
    private ?string $pathologie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_modification = null;

    #[ORM\OneToMany(mappedBy: 'fiche_medicale', targetEntity: Consultation::class)]
    private Collection $consultations;

    public function __construct()
    {
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAllergies(): ?string
    {
        return $this->allergies;
    }

    public function setAllergies(string $allergies): self
    {
        $this->allergies = $allergies;

        return $this;
    }

    public function getPathologie(): ?string
    {
        return $this->pathologie;
    }

    public function setPathologie(string $pathologie): self
    {
        $this->pathologie = $pathologie;

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
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setFicheMedicale($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getFicheMedicale() === $this) {
                $consultation->setFicheMedicale(null);
            }
        }

        return $this;
    }
}
