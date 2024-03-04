<?php

namespace App\Form;

use App\Entity\Playlists;
use App\Entity\Songs;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaylistsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title')
            ->add('song_id', EntityType::class, [
                'class' => Songs::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('user_id', EntityType::class, [
                'class' => Users::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Playlists::class,
        ]);
    }
}
