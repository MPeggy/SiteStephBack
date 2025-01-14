<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Repository\RendezVousRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/rendezvous', name: 'app_api_rendezvous_')]
class RendezvousController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private RendezVousRepository $repository)
    {
    }

    #[Route(methods: 'POST')]
    public function new(): Response
    {
        $rendezvous = new Rendezvous();
        $rendezvous->setOrderDate(new datetimeImmutable());
        $rendezvous->setOrderHour(new DateTimeImmutable());
        $rendezvous->setCreatedAt(new DateTimeImmutable());
        
        $this->manager->persist($rendezvous);
        $this->manager->flush();

        return $this->json(
            ['message' => "RDV resource created with {$rendezvous ->getId()}"],
            Response::HTTP_CREATED,
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        // ... Affiche et sauvegarde en base de données
        $rendezvous = $this->repository->findOneBy(['id' => $id]);

        if (!$rendezvous   ) {
            throw $this->createNotFoundException("No rendezvous found for {$id} id");
        }

        return $this->json(
            ['message' => "A rendezvous was found : {$rendezvous->getId()} id"]
        );
    }
    
    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id): Response
    {
        // … Edite et sauvegarde en base de données
        $rendezvous = $this->repository->findOneBy(['id' => $id]);

        if (!$rendezvous   ) {
            throw $this->createNotFoundException("No rendezvous found for {$id} id");
        }

        $this->manager->flush();

        return $this->redirectToRoute('app_api_rendezvous_show', ['id' => $rendezvous->getId()]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        // ... Supprime le rendezvous   de la base de données
        $rendezvous = $this->repository->findOneBy(['id' => $id]);
        if (!$rendezvous   ) {
            throw $this->createNotFoundException("No rendezvous found for {$id} id");
        }

        $this->manager->remove($rendezvous );
        $this->manager->flush();

        return $this->json(['message' => "rendezvous resource deleted"], Response::HTTP_NO_CONTENT);
        
    }
}