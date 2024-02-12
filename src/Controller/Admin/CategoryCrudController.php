<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Category')
            
            ->setPageTitle("index", "Atelier Desk - Category")

            ->setPaginatorPageSize(10);
        ;
    }


    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
               ->hideOnForm()
               ->hideOnIndex(),
            TextField::new('name')
        ];
    }
    
}
