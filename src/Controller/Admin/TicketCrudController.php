<?php

namespace App\Controller\Admin;

use App\Entity\Ticket;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            // TextField::new('category')
            //     ->setFormTypeOption('disabled', 'disabled'),
            TextEditorField::new('description'),
            AssociationField::new('user')
                ->hideOnForm()->formatValue(function ($value, $entity) {
                return $entity->getUser()->getName();
            }),
            AssociationField::new('category'),
            DateTimeField::new('createAt')
                ->hideOnIndex()->setFormTypeOption('disabled', 'disabled'),
            DateTimeField::new('updateAt')->hideOnIndex()
                ->setFormTypeOption('disabled', 'disabled')
        ];
    }

    //for remove Create new ticket button Because only technicies can create tickets
    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions
    //         ->disable(Action::NEW);
    // }
    
}
