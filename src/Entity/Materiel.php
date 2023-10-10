<?php

namespace App\Entity;

use App\Repository\MaterielRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $num_serie = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_achat = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imagem = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    private ?User $affectation = null;

    public function __toString() :string {
        return $this->user;
    }
   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSerie(): ?string
    {
        return $this->num_serie;
    }

    public function setNumSerie(string $num_serie): self
    {
        $this->num_serie = $num_serie;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->date_achat;
    }

    public function setDateAchat(?\DateTimeInterface $date_achat): self
    {
        $this->date_achat = $date_achat;

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

    public function getImagem(): ?string
    {
        return $this->imagem;
    }

    public function setImagem(?string $imagem): self
    {
        $this->imagem = $imagem;

        return $this;
    }

    public function getAffectation(): ?User
    {
        return $this->affectation;
    }

    public function setAffectation(?User $affectation): self
    {
        $this->affectation = $affectation;


        return $this;
    }
}
