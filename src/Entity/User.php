<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre email")]
    #[Assert\Email(message: "Votre email n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez renseigner votre mot de passe")]
    #[Assert\Regex(
        pattern: "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/",
        message:"Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole"
    )]
    private ?string $password = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre nom")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Votre nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre nom ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre prénom")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Votre prénom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre prénom ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $description = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable:true)]
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $specialite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $cin = null;

    #[ORM\Column(nullable:true)]
    private ?int $num_securite_sociale = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable:true)]
    private ?\DateTimeInterface $date_de_modification = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $etat = null;

   

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isBlocked = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $region = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $structure = null;

    #[ORM\OneToMany(mappedBy: 'affectation', targetEntity: Materiel::class)]
    private Collection $materiels;

  

    public function __construct()
    {
       
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom = null): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom = null): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse = null): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel = null): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance = null): self
    {
        $this->date_de_naissance = $date_de_naissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite = null): self
    {
        $this->specialite = $specialite;

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

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNumSecuriteSociale(): ?int
    {
        return $this->num_securite_sociale;
    }

    public function setNumSecuriteSociale(int $num_securite_sociale): self
    {
        $this->num_securite_sociale = $num_securite_sociale;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image = null): self
    {
        $this->image = $image;

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

  
 
   
   

   

   

    
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function isIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(?bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getStructure(): ?string
    {
        return $this->structure;
    }

    public function setStructure(?string $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setAffectation($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getAffectation() === $this) {
                $materiel->setAffectation(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nom.' '.$this->prenom;

    }
  
  
    

}
