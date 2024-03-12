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
            TextField::new('name'),
            TextEditorField::new('description'),
            AssociationField::new('user')
            ->formatValue(function ($value, $entity) {
                $user = $entity->getUser();
                return $user ? $user->getName() : 'N/A';
            }),
            AssociationField::new('category'),
            AssociationField::new('equipment'),
            BooleanField::new('isOpen'),
            DateTimeField::new('createAt')
                ->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('updateAt')->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled')
        ];
    }


    
}
