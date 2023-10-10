<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $code_ordonnance = null;

    #[ORM\Column(length: 255)]
    private ?string $medicaments = null;

    #[ORM\Column(length: 255)]
    private ?string $dosage = null;

    #[ORM\Column]
    private ?int $nombre_jours = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_modification = null;

    #[ORM\OneToOne(mappedBy: 'ordonnance', cascade: ['persist', 'remove'])]
    private ?Consultation $consultation = null;

    #[ORM\OneToOne(inversedBy: 'ordonnance', cascade: ['persist', 'remove'])]
    private ?Facture $facture = null;


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

    public function getCodeOrdonnance(): ?string
    {
        return $this->code_ordonnance;
    }

    public function setCodeOrdonnance(string $code_ordonnance): self
    {
        $this->code_ordonnance = $code_ordonnance;

        return $this;
    }

    public function getMedicaments(): ?string
    {
        return $this->medicaments;
    }

    public function setMedicaments(string $medicaments): self
    {
        $this->medicaments = $medicaments;

        return $this;
    }

    public function getDosage(): ?string
    {
        return $this->dosage;
    }

    public function setDosage(string $dosage): self
    {
        $this->dosage = $dosage;

        return $this;
    }

    public function getNombreJours(): ?int
    {
        return $this->nombre_jours;
    }

    public function setNombreJours(int $nombre_jours): self
    {
        $this->nombre_jours = $nombre_jours;

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

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        // unset the owning side of the relation if necessary
        if ($consultation === null && $this->consultation !== null) {
            $this->consultation->setOrdonnance(null);
        }

        // set the owning side of the relation if necessary
        if ($consultation !== null && $consultation->getOrdonnance() !== $this) {
            $consultation->setOrdonnance($this);
        }

        $this->consultation = $consultation;

        return $this;
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(?Facture $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

}
