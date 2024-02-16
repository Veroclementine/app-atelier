<?php

namespace App\Controller\Admin;

use App\Entity\Equipment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EquipmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Equipment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Gestion Equipement')
        ->setEntityLabelInSingular('Equipement')
        
        ->setPageTitle("index", "Atelier Desk - Equipement")

        ->setPaginatorPageSize(10);
    ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('name'),
            TextField::new('brand'),
            TextField::new('model'),
            DateTimeField::new('purchase_date'),
            DateTimeField::new('warranty_expiry'),
            TextField::new('serial_number'),
            AssociationField::new('client')
            ->hideOnIndex(),

            
        ];
    }

}
