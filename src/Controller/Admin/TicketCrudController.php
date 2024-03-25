<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class TicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ticket::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Gestion Tickets')
            ->setEntityLabelInSingular('Ticket')
            
            ->setPageTitle("index", "Atelier Desk - Tickets")

            ->setPaginatorPageSize(10);
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')->setLabel('Nom'),
            TextEditorField::new('description')->setLabel('Description'),
            AssociationField::new('user')->setLabel('Utilisateur')
            ->formatValue(function ($value, $entity) {
                $user = $entity->getUser();
                return $user ? $user->getName() : 'N/A';
            }),
            AssociationField::new('category')->setLabel('Categorie'),
            AssociationField::new('equipment')->setLabel('Equipement')
            ->formatValue(function ($value, $entity) {
                $equipment = $entity->getEquipment();
                return $equipment ? $equipment->getName() : 'N/A';
            }),
            BooleanField::new('isOpen')->setLabel('Ouvert'),
            DateTimeField::new('createAt')->setLabel('Date création')
                ->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('updateAt')->setLabel('Date Update')
                ->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled')
        ];
    }


    
}
