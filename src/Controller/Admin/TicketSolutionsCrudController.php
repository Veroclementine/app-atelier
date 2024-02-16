<?php

namespace App\Controller\Admin;

use App\Entity\TicketSolutions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TicketSolutionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TicketSolutions::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setEntityLabelInPlural('Gestion Solutions Tickets')
        ->setEntityLabelInSingular('Solution')
        
        ->setPageTitle("index", "Atelier Desk - Solution's Library")

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
            AssociationField::new('tickets')
            ->hideOnIndex(),
        ];
    }

}
