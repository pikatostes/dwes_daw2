<?php

namespace App\Form;

use App\Entity\Playlist;
use App\Entity\Song;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
            ->add('songs_id', EntityType::class, [
                'class' => Song::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true, // Esta opción convierte el campo en un conjunto de checkboxes
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlist::class,
        ]);
    }
}
