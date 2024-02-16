<?php

namespace App\Controller\Admin;

use App\Entity\Songs;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SongsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Songs::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('title'),
            TextField::new('author'),

            ImageField::new('cover', 'Cover')
                ->setBasePath('/uploads/images')
                ->setUploadDir('public/uploads/images'),

            ImageField::new('audio', 'Audio')
                ->setBasePath('/uploads/music')
                ->setUploadDir('public/uploads/music')
                ->hideOnIndex()
        ];
    }
}
