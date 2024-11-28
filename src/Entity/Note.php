<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 * @ApiResource()
 */
class Note
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"eleve:read"})
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Professeur", inversedBy="notes")
     */
    private $evaluateur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Eleve", inversedBy="notes")
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Matiere", inversedBy="notes")
     */
    private $matiere;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note)
    {
        $this->note = $note;

        return $this;
    }

    public function getEvaluateur(): ?Professeur
    {
        return $this->evaluateur;
    }

    public function setEvaluateur(?Professeur $evaluateur)
    {
        $this->evaluateur = $evaluateur;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve)
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere)
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
