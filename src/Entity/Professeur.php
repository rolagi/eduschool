<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfesseurRepository")
 * @ApiResource()
 */
class Professeur extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="evaluateur")
     */
    private $notes;

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
            $note->setEvaluateur($this);
        }

        return $this;
    }

    public function removeNote(Note $note)
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEvaluateur() === $this) {
                $note->setEvaluateur(null);
            }
        }

        return $this;
    }
}
