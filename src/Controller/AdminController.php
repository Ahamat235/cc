<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;

class AdminController extends AbstractController
{
    #[Route(path: '/admin', name: 'admin', methods: ['GET'])]
    public function admin(EntityManagerInterface $entityManager): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
        $repository = $entityManager->getRepository(User::class);
        
        return $this->render('admin/index.html.twig', [
            'users' => $repository->findByRoles("ROLE_INSTRUCTEUR"),
        ]);
    }
    
    #[Route(path: '/admin/newinsctructeur', name: 'admin_newinsctructeur', methods: ['GET', 'POST'])]
    public function newinsctructeur(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
		$this->denyAccessUnlessGranted('ROLE_ADMIN');
		
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
			
			$user->setRoles(["ROLE_INSTRUCTEUR"]);
			
			$plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
			
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    
    #[Route('/admin/delete-instructeur/{id}', name: 'delete_instructeur', methods: ['GET'])]
    public function delete(User $user, EntityManagerInterface $entityManager): Response
    {
		$entityManager->remove($user);
		$entityManager->flush();

        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/admin/makeadmin/{id}', name: 'makeadmin', methods: ['GET'])]
    public function makeadmin(User $user, EntityManagerInterface $entityManager): Response
    {	
		$user->setRoles(["ROLE_ADMIN", "ROLE_INSTRUCTEUR"]);
		$entityManager->persist($user);
		$entityManager->flush();

        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/admin/unmakeadmin/{id}', name: 'unmakeadmin', methods: ['GET'])]
    public function unmakeadmin(User $user, EntityManagerInterface $entityManager): Response
    {	
		$user->setRoles(["ROLE_INSTRUCTEUR"]);
		$entityManager->persist($user);
		$entityManager->flush();

        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }

}
