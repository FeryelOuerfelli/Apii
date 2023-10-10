<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column]
    private ?float $taille = null;

    #[ORM\Column]
    private ?float $imc = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $pression_arterielle = null;

    #[ORM\Column]
    private ?float $frequence_cardiaque = null;

    #[ORM\Column]
    private ?float $taux_glycemie = null;

    #[ORM\Column(length: 255)]
    private ?string $symptomes = null;

    #[ORM\Column(length: 255)]
    private ?string $traitement = null;

    #[ORM\Column(length: 255)]
    private ?string $observation = null;

    #[ORM\OneToOne(mappedBy: 'consultation', cascade: ['persist', 'remove'])]
    private ?RendezVous $rendezVous = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?FicheMedicale $fiche_medicale = null;

    #[ORM\OneToOne(inversedBy: 'consultation', cascade: ['persist', 'remove'])]
    private ?Ordonnance $ordonnance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(float $taille): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getImc(): ?float
    {
        return $this->imc;
    }

    public function setImc(float $imc): self
    {
        $this->imc = $imc;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPressionArterielle(): ?float
    {
        return $this->pression_arterielle;
    }

    public function setPressionArterielle(float $pression_arterielle): self
    {
        $this->pression_arterielle = $pression_arterielle;

        return $this;
    }

    public function getFrequenceCardiaque(): ?float
    {
        return $this->frequence_cardiaque;
    }

    public function setFrequenceCardiaque(float $frequence_cardiaque): self
    {
        $this->frequence_cardiaque = $frequence_cardiaque;

        return $this;
    }

    public function getTauxGlycemie(): ?float
    {
        return $this->taux_glycemie;
    }

    public function setTauxGlycemie(float $taux_glycemie): self
    {
        $this->taux_glycemie = $taux_glycemie;

        return $this;
    }

    public function getSymptomes(): ?string
    {
        return $this->symptomes;
    }

    public function setSymptomes(string $symptomes): self
    {
        $this->symptomes = $symptomes;

        return $this;
    }

    public function getTraitement(): ?string
    {
        return $this->traitement;
    }

    public function setTraitement(string $traitement): self
    {
        $this->traitement = $traitement;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->observation;
    }

    public function setObservation(string $observation): self
    {
        $this->observation = $observation;

        return $this;
    }

    public function getRendezVous(): ?RendezVous
    {
        return $this->rendezVous;
    }

    public function setRendezVous(?RendezVous $rendezVous): self
    {
        // unset the owning side of the relation if necessary
        if ($rendezVous === null && $this->rendezVous !== null) {
            $this->rendezVous->setConsultation(null);
        }

        // set the owning side of the relation if necessary
        if ($rendezVous !== null && $rendezVous->getConsultation() !== $this) {
            $rendezVous->setConsultation($this);
        }

        $this->rendezVous = $rendezVous;

        return $this;
    }

    public function getFicheMedicale(): ?FicheMedicale
    {
        return $this->fiche_medicale;
    }

    public function setFicheMedicale(?FicheMedicale $fiche_medicale): self
    {
        $this->fiche_medicale = $fiche_medicale;

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
}
