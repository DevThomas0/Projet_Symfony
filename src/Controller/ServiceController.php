<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/service')]
#[IsGranted('ROLE_USER')]
final class ServiceController extends AbstractController
{
    #[Route(name: 'app_service_index', methods: ['GET'])]
    public function index(ServiceRepository $RepoDeService): Response
    {
        // On récupere tout
        return $this->render('service/index.html.twig', [
            'services' => $RepoDeService->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_new', methods: ['GET', 'POST'])]
    public function new(Request $LaRequest, EntityManagerInterface $ManagerEntity): Response
    {
        $LeNewService = new Service();
        $LeFormulaire = $this->createForm(ServiceType::class, $LeNewService);
        $LeFormulaire->handleRequest($LaRequest);

        if ($LeFormulaire->isSubmitted() && $LeFormulaire->isValid()) {

            // on persiste le service
            $ManagerEntity->persist($LeNewService);
            $ManagerEntity->flush();

            // petit message de succes
            $this->addFlash('success', 'Service créé avec succès.');

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/new.html.twig', [
            'service' => $LeNewService,
            'form' => $LeFormulaire,
        ]);
    }

    #[Route('/{UnService}', name: 'app_service_show', methods: ['GET'])]
    public function show(Service $UnService): Response
    {
        return $this->render('service/show.html.twig', [
            'service' => $UnService,
        ]);
    }

    #[Route('/{ServiceToEdit}/edit', name: 'app_service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $TheRequest, Service $ServiceToEdit, EntityManagerInterface $LeManager): Response
    {
        $FormEdit = $this->createForm(ServiceType::class, $ServiceToEdit);
        $FormEdit->handleRequest($TheRequest);

        if ($FormEdit->isSubmitted() && $FormEdit->isValid()) {
            // update du time
            $ServiceToEdit->setUpdatedAt(new \DateTimeImmutable());
            $LeManager->flush();

            // succeessss
            $this->addFlash('success', 'Service modifié avec succès.');

            return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service/edit.html.twig', [
            'service' => $ServiceToEdit,
            'form' => $FormEdit,
        ]);
    }

    #[Route('/{ServiceToDelete}', name: 'app_service_delete', methods: ['POST'])]
    public function delete(Request $Requete, Service $ServiceToDelete, EntityManagerInterface $EntityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ServiceToDelete->getId(), $Requete->getPayload()->getString('_token'))) {
            // on suprime
            $EntityManager->remove($ServiceToDelete);
            $EntityManager->flush();

            $this->addFlash('success', 'Service supprimé avec succès.');
        }

        return $this->redirectToRoute('app_service_index', [], Response::HTTP_SEE_OTHER);
    }
}
