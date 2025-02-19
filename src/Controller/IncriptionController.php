<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Atelier;
use App\Entity\Inscription;

final class IncriptionController extends AbstractController
{
    #[Route(path: '/inscrire/{id}', name: 'inscrire', methods: ['GET'])]
    public function inscrire(Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $inscription = new Inscription();
        $inscription->setUser($this->getUser());
        $inscription->setAtelier($atelier);

        $entityManager->persist($inscription);
        $entityManager->flush();

        return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/desinscrire/{id}', name: 'desinscrire', methods: ['GET'])]
    public function desinscrire(Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");

        $repository = $entityManager->getRepository(Inscription::class);

        $inscription = $repository->findOneBy([
            'user' => $this->getUser(),
            'atelier' => $atelier,
        ]);

        $entityManager->remove($inscription);
        $entityManager->flush();

        return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
    }

}
