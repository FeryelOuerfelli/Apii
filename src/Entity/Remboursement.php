<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant_a_rembourser = null;

    #[ORM\Column]
    private ?float $montant_maximale = null;

    #[ORM\Column]
    private ?float $taux_remboursement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_remboursement = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\OneToOne(mappedBy: 'remboursement', cascade: ['persist', 'remove'])]
    private ?FicheAssurance $ficheAssurance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantARembourser(): ?float
    {
        return $this->montant_a_rembourser;
    }

    public function setMontantARembourser(float $montant_a_rembourser): self
    {
        $this->montant_a_rembourser = $montant_a_rembourser;

        return $this;
    }

    public function getMontantMaximale(): ?float
    {
        return $this->montant_maximale;
    }

    public function setMontantMaximale(float $montant_maximale): self
    {
        $this->montant_maximale = $montant_maximale;

        return $this;
    }

    public function getTauxRemboursement(): ?float
    {
        return $this->taux_remboursement;
    }

    public function setTauxRemboursement(float $taux_remboursement): self
    {
        $this->taux_remboursement = $taux_remboursement;

        return $this;
    }

    public function getDateRemboursement(): ?\DateTimeInterface
    {
        return $this->date_remboursement;
    }

    public function setDateRemboursement(\DateTimeInterface $date_remboursement): self
    {
        $this->date_remboursement = $date_remboursement;

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

    public function getFicheAssurance(): ?FicheAssurance
    {
        return $this->ficheAssurance;
    }

    public function setFicheAssurance(?FicheAssurance $ficheAssurance): self
    {
        // unset the owning side of the relation if necessary
        if ($ficheAssurance === null && $this->ficheAssurance !== null) {
            $this->ficheAssurance->setRemboursement(null);
        }

        // set the owning side of the relation if necessary
        if ($ficheAssurance !== null && $ficheAssurance->getRemboursement() !== $this) {
            $ficheAssurance->setRemboursement($this);
        }

        $this->ficheAssurance = $ficheAssurance;

        return $this;
    }
}
