<?php

namespace App\Form;

use App\Entity\Song;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\DataTransformer\FileToSymfonyFileTransformer;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('author')
            ->add('cover', FileType::class)
            ->add('audio', FileType::class);

        // Add the data transformer to the form
        $builder->get('cover')->addModelTransformer(new FileToSymfonyFileTransformer());
        $builder->get('audio')->addModelTransformer(new FileToSymfonyFileTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
