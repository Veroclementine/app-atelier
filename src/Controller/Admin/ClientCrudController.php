<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;


class ClientCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Clients')
            ->setEntityLabelInSingular('Client')
            
            ->setPageTitle("index", "Atelier Desk - Client")

            ->setPaginatorPageSize(10);
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnForm(),
            TextField::new('username'),
            TextField::new('email'),
            TextField::new('city')->setLabel('ville'),
            TextField::new('address')->setLabel('addresse'),
            TextField::new('telephone'),  
            DateTimeField::new ('CreatedAt')
            ->setLabel('Data création')
            ->setFormTypeOption('disabled', 'disabled')
            ->hideOnIndex(),
            // Campo de asociación para mostrar los equipos vinculados
        AssociationField::new('leasedEquipment')
        ->hideOnForm() 
        ->setLabel('Leased Equipment') 
        ];
    }
    

    
}
