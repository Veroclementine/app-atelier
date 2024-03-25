<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\EntityListener\UserListener;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField; // Use ChoiceField instead of ArrayField


class UserCrudController extends AbstractCrudController
{
    private $userListener; // Declara la propiedad $userListener
    private $passwordHasher;
    public function __construct(UserListener $userListener,  UserPasswordHasherInterface $passwordHasher)
    {
        $this->userListener = $userListener;
        $this->passwordHasher = $passwordHasher;
    }

    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')   
            ->setPageTitle("index", "Atelier Desk - Admin Utilisateurs")
            ->setPaginatorPageSize(10);
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('name')->setLabel('Prénom'),
            TextField::new('lastname')->setLabel('Nom'),
            TextField::new('email'),
            TextField::new('plainPassword')->setLabel('Mot de passe')
                ->setFormType(PasswordType::class)
                ->hideOnIndex(),
                ChoiceField::new('roles') // Use ChoiceField instead of ArrayField
                ->allowMultipleChoices() // Allow selecting multiple roles
                ->setChoices(['ROLE_USER' => 'ROLE_USER', 'ROLE_ADMIN' => 'ROLE_ADMIN']) // Example choices
                ->setLabel('Roles') // Set a label for the field
                ->hideOnIndex(),
            DateTimeField::new ('CreatedAt')
                ->setFormTypeOption('disabled', 'disabled')//not allow to change the date
                ->hideOnIndex()
            
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $user = new User(); // Crea una nueva instancia de User
        // Configura otros campos del usuario (nombre, correo, roles, etc.)

        // Hashea la contraseña antes de almacenarla
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'plainPassword');
        $user->setPassword($hashedPassword);

        return $user;
    }

}
