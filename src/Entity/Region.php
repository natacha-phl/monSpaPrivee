<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Spa::class, mappedBy: 'region')]
    private Collection $spas;

    public function __construct()
    {
        $this->spas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Spa>
     */
    public function getSpas(): Collection
    {
        return $this->spas;
    }

    public function addSpa(Spa $spa): static
    {
        if (!$this->spas->contains($spa)) {
            $this->spas->add($spa);
            $spa->setRegion($this);
        }

        return $this;
    }

    public function removeSpa(Spa $spa): static
    {
        if ($this->spas->removeElement($spa)) {
            // set the owning side to null (unless already changed)
            if ($spa->getRegion() === $this) {
                $spa->setRegion(null);
            }
        }

        return $this;
    }
}
