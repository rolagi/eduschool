<?php

namespace App\Form;


namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Matiere;
use App\Entity\Note;
use App\Entity\Professeur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', NumberType::class, [
                'label' => 'Note',
                'scale' => 2,
            ])
            ->add('evaluateur', EntityType::class, [
                'class' => Professeur::class,
                'choice_label' => function (Professeur $professeur) {
                    return $professeur->getPrenom() . ' ' . $professeur->getNom();
                },
                'label' => 'Évaluateur',
            ])
            ->add('matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom',
                'label' => 'Matière',
            ])
            ->add('commentaire', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer la note',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }

}
