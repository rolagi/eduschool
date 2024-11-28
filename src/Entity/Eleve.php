<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EleveRepository")
 * @ApiResource(
 *          itemOperations={
 *              "search"={
 *              "method"="GET",
 *              "path"="/eleves/search",
 *              "controller"=App\Controller\EleveController::class
 *          }
 *          },
 *          normalizationContext={"groups"={"eleve:read"}},
 *          denormalizationContext={"groups"={"eleve:write"}},
 * )
 */
class Eleve extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="eleve")
     * @Groups({"eleve:read"})
     */
    private $notes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classe", inversedBy="eleves")
     * @Groups({"eleve:read","eleve:write"})
     */
    private $classe;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note)
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setEleve($this);
        }

        return $this;
    }

    public function removeNote(Note $note)
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe)
    {
        $this->classe = $classe;

        return $this;
    }
}
