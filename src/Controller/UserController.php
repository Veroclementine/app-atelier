<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    ): Response
    {
        // Vérifier si l'utilisateur est authentifié et s'il s'agit d'une instance d'utilisateur
        $user = $this->getUser();
        if(!$user instanceof User) {
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
}
