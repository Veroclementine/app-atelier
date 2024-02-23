<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserController extends AbstractController
{
    /**
     * this controller allow to edit a user
     */
    #[Route('/user/edition/{id}', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        UserRepository $userRepository
    ): Response {
        // Vérifier si l'utilisateur est authentifié et s'il s'agit d'une instance d'utilisateur
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifiez que l'ID utilisateur fourni est valide.
        $userToEdit = $userRepository->find($id);
        if (!$userToEdit instanceof User) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Vérifiez que l'utilisateur authentifié est le même que l'utilisateur qui tente de modifier.
        if ($user->getId() !== $userToEdit->getId()) {
            throw $this->createAccessDeniedException('Pas le droit de modifier cet utilisateur');
        }

        // Créer le formulaire avec l'utilisateur à éditer
        $form = $this->createForm(UserType::class, $userToEdit);
        $form->handleRequest($request);

        // Traiter la demande de formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash(
                'success',
                'Vos informations ont été modifiées avec succès!'
            );

            return $this->redirectToRoute('app_ticket');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //Edit password code a revoir
    #[Route('/user/edition-mot-de-passe/{id}', name: 'user_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(
        Request $request,
        Security $security,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $manager,
        string $id // Ajoutez cette ligne pour obtenir l'id de l'URL
    ): Response {
        $user = $security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Utilisateur pas authentifié !');
        }

        // Check if the current user matches the ID in the URL
        if ($user->getId() != $id) {
            $this->addFlash(
                'warning',
                "Vous n'êtes pas autorisé à modifier le mot de passe d'un autre utilisateur."
            );
            return $this->redirectToRoute('user_edit_password', ['id' => $user->getId()]);
        }

        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->getData()['plainPassword'])) {
                $user->setPassword(
                    $hasher->hashPassword($user, $form->getData()['newPassword'])
                );

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Le mot de passe a été modifié.'
                );

                return $this->redirectToRoute('app_ticket');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe renseigné est incorrect.'
                );
            }
        }

        return $this->render('user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
