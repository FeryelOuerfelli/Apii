<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $image_signature = null;

    #[ORM\Column]
    private ?int $num_facture = null;

    #[ORM\OneToOne(mappedBy: 'facture', cascade: ['persist', 'remove'])]
    private ?Ordonnance $ordonnance = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Pharmacie $pharmacie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImageSignature(): ?string
    {
        return $this->image_signature;
    }

    public function setImageSignature(string $image_signature): self
    {
        $this->image_signature = $image_signature;

        return $this;
    }

    public function getNumFacture(): ?int
    {
        return $this->num_facture;
    }

    public function setNumFacture(int $num_facture): self
    {
        $this->num_facture = $num_facture;

        return $this;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): self
    {
        // unset the owning side of the relation if necessary
        if ($ordonnance === null && $this->ordonnance !== null) {
            $this->ordonnance->setFacture(null);
        }

        // set the owning side of the relation if necessary
        if ($ordonnance !== null && $ordonnance->getFacture() !== $this) {
            $ordonnance->setFacture($this);
        }

        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getPharmacie(): ?Pharmacie
    {
        return $this->pharmacie;
    }

    public function setPharmacie(?Pharmacie $pharmacie): self
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }
}
