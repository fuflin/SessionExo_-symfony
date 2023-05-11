<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: ModuleSession::class, orphanRemoval: true)]
    private Collection $moduleSessions;

    public function __construct()
    {
        $this->moduleSessions = new ArrayCollection();
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

    /**
     * @return Collection<int, ModuleSession>
     */
    public function getModuleSessions(): Collection
    {
        return $this->moduleSessions;
    }

    public function addModuleSession(ModuleSession $moduleSession): self
    {
        if (!$this->moduleSessions->contains($moduleSession)) {
            $this->moduleSessions->add($moduleSession);
            $moduleSession->setCategory($this);
        }

        return $this;
    }

    public function removeModuleSession(ModuleSession $moduleSession): self
    {
        if ($this->moduleSessions->removeElement($moduleSession)) {
            // set the owning side to null (unless already changed)
            if ($moduleSession->getCategory() === $this) {
                $moduleSession->setCategory(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    
}
