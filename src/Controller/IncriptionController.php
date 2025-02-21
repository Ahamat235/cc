<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Atelier;
use App\Entity\Inscription;
use App\Repository\InscriptionRepository;

final class IncriptionController extends AbstractController
{
    #[Route(path: '/inscrire/{id}', name: 'inscrire', methods: ['GET'])]
    public function inscrire(Atelier $atelier, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_APPRENTI");

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
        $this->denyAccessUnlessGranted("ROLE_APPRENTI");

        $repository = $entityManager->getRepository(Inscription::class);

        $inscription = $repository->findOneBy([
            'user' => $this->getUser(),
            'atelier' => $atelier,
        ]);

        $entityManager->remove($inscription);
        $entityManager->flush();

        return $this->redirectToRoute('app_atelier_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/mesinscriptions', name: 'mesinscriptions', methods: ['GET'])]
    public function mesinscriptions(): Response
    {
        $this->denyAccessUnlessGranted("ROLE_APPRENTI");

        return $this->render('inscription/mesinscriptions.html.twig', [
            'inscriptions' => $this->getUser()->getInscriptions(),
        ]);
    }

    #[Route(path: '/atelier/{id}/inscrits', name: 'inscrits', methods: ['GET'])]
    public function inscrits(Atelier $atelier): Response
    {
        $this->denyAccessUnlessGranted("ROLE_INSTRUCTEUR");

        return $this->render('inscription/inscrits.html.twig', [
            'inscriptions' => $atelier->getInscriptions(),
        ]);
    }

    #[Route(path: '/note/{id}/{n}', name: 'note', methods: ['GET'], requirements: ['n' => '\d+'])]
    public function note(Atelier $atelier, EntityManagerInterface $entityManager, $n): Response
    {
        $this->denyAccessUnlessGranted("ROLE_APPRENTI");

        if($n >= 0 && $n < 6){
            $repository = $entityManager->getRepository(Inscription::class);

            $inscription = $repository->findOneBy([
                'User' => $this->getUser(),
                'atelier' => $atelier,
            ]);

            $inscription->setNote($n);

            $entityManager->persist($inscription);
            $entityManager->flush();
        }
        return $this->redirectToRoute('mesinscriptions', [], Response::HTTP_SEE_OTHER);
    }



}
