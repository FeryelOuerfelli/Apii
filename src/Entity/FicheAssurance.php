<?php

namespace App\Entity;

use App\Repository\FicheAssuranceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheAssuranceRepository::class)]
class FicheAssurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_adherent = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    #[ORM\Column(length: 255)]
    private ?string $image_facture = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Ordonnance $ordonnance = null;

    #[ORM\ManyToOne]
    private ?User $pharmacien = null;

    #[ORM\ManyToOne(inversedBy: 'ficheAssurances')]
    private ?User $assureur = null;

    #[ORM\OneToOne(inversedBy: 'ficheAssurance', cascade: ['persist', 'remove'])]
    private ?Remboursement $remboursement = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumAdherent(): ?int
    {
        return $this->num_adherent;
    }

    public function setNumAdherent(int $num_adherent): self
    {
        $this->num_adherent = $num_adherent;

        return $this;
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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getImageFacture(): ?string
    {
        return $this->image_facture;
    }

    public function setImageFacture(string $image_facture): self
    {
        $this->image_facture = $image_facture;

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

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): self
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getPharmacien(): ?User
    {
        return $this->pharmacien;
    }

    public function setPharmacien(?User $pharmacien): self
    {
        $this->pharmacien = $pharmacien;

        return $this;
    }

    public function getAssureur(): ?User
    {
        return $this->assureur;
    }

    public function setAssureur(?User $assureur): self
    {
        $this->assureur = $assureur;

        return $this;
    }

    public function getRemboursement(): ?Remboursement
    {
        return $this->remboursement;
    }

    public function setRemboursement(?Remboursement $remboursement): self
    {
        $this->remboursement = $remboursement;

        return $this;
    }

}
