<?php

namespace App\Entity;

use App\Entity\Formation;
use App\Entity\Stagiaire;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_begin = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_end = null;

    #[ORM\Column]
    private ?int $Nb_place = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Formation::class, orphanRemoval: true)]
    private Collection $formations;

    #[ORM\ManyToMany(targetEntity: Stagiaire::class, inversedBy: 'sessions')]
    private Collection $stagiaires;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class)]
    private Collection $programmes;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getDateBegin(): ?\DateTimeInterface
    {
        return $this->date_begin;
    }

    public function setDateBegin(\DateTimeInterface $date_begin): self
    {
        $this->date_begin = $date_begin;
        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->date_end;
    }

    public function setDateEnd(\DateTimeInterface $date_end): self
    {
        $this->date_end = $date_end;
        return $this;
    }
    
    public function getDuree(){

        $interval = $this->date_end->diff($this->date_begin);
        return $interval->format('%m mois');
    }

    public function getNbPlace(): ?int
    {
        return $this->Nb_place;
    }

    public function setNbPlace(int $Nb_place): self
    {
        $this->Nb_place = $Nb_place;
        return $this;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): self
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setSession($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): self
    {
        if ($this->formations->removeElement($formation)) {
            // set the owning side to null (unless already changed)
            if ($formation->getSession() === $this) {
                $formation->setSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires->add($stagiaire);
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        $this->stagiaires->removeElement($stagiaire);

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
