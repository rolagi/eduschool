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
 *     collectionOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/eleves",
 *             "openapi_context"={
 *                 "summary"="Liste des élèves",
 *                 "description"="Retourne la liste de tous les élèves",
 *             }
 *         },
 *         "search"={
 *             "method"="GET",
 *             "path"="/eleves/search",
 *             "controller"=App\Controller\EleveController::class,
 *             "openapi_context"={
 *                 "parameters"={
 *                     {
 *                         "name"="name",
 *                         "in"="query",
 *                         "description"="Nom ou prénom de l'élève",
 *                         "required"=false,
 *                         "schema"={"type"="string"}
 *                     },
 *                     {
 *                         "name"="classe",
 *                         "in"="query",
 *                         "description"="Classe de l'élève",
 *                         "required"=false,
 *                         "schema"={"type"="string"}
 *                     }
 *                 },
 *                 "summary"="Rechercher des élèves",
 *                 "description"="Recherche des élèves par nom ou prénom"
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "method"="GET",
 *             "path"="/eleves/{id}",
 *             "openapi_context"={
 *                 "summary"="Obtenir les détails d'un élève",
 *                 "description"="Retourne les informations détaillées d'un élève par son ID",
 *             },
 *             "normalization_context"={"groups"={"eleve:read"}}
 *         },
 *         "get_notes"={
 *             "method"="GET",
 *             "path"="/eleves/{id}/notes",
 *             "normalization_context"={"groups"={"eleve_note:read"}},
 *             "summary"="Obtenir les notes d'un élève",
 *             "description"="Retourne les notes d'un élève en fonction de son ID"
 *         }
 *     },
 *     normalizationContext={"groups"={"eleve:read"}},
 *     denormalizationContext={"groups"={"eleve:write"}},
 * )
 */

class Eleve extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="eleve")
     * @Groups({"eleve:read","eleve_note:read"})
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
