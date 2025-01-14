<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api/activity', name: 'app_api_activity_')]
class ActivityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager, private ActivityRepository $repository)
    {
    }

    #[Route('/', name: 'new', methods: 'POST')]
    public function new(): Response
    {
        $activity = new Activity();
        $activity->setName('Coaching');
        $activity->setDescription('Libérez votre potentiel.');
        $activity->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($activity);
        $this->manager->flush();


        return $this->json(
            ['message' => "Coachong resource created with {$activity->getId()} id"],
            Response::HTTP_CREATED,
        );
        
    }

    #[Route('/{id}', name: 'show', methods: 'GET')]
    public function show(int $id): Response
    {
        // ... Affiche et sauvegarde en base de données
        $activity = $this->repository->findOneBy(['id' => $id]);

        if (!$activity) {
            throw $this->createNotFoundException("No Activity found for {$id} id");
        }

        return $this->json(
            ['message' => "A Activity was found : {$activity->getName()} for {$activity->getId()} id"]
        );
    }
    
    #[Route('/{id}', name: 'edit', methods: 'PUT')]
    public function edit(int $id): Response
    {
        // … Edite et sauvegarde en base de données
        $activity = $this->repository->findOneBy(['id' => $id]);

        if (!$activity) {
            throw $this->createNotFoundException("No Activity found for {$id} id");
        }

        $activity->setName('Activity name updated');
        $this->manager->flush();

        return $this->redirectToRoute('app_api_activity_show', ['id' => $activity->getId()]);
    }

    #[Route('/{id}', name: 'delete', methods: 'DELETE')]
    public function delete(int $id): Response
    {
        // ... Supprime le activity de la base de données
        $activity = $this->repository->findOneBy(['id' => $id]);
        if (!$activity) {
            throw $this->createNotFoundException("No activity found for {$id} id");
        }

        $this->manager->remove($activity);
        $this->manager->flush();

        return $this->json(['message' => "activity resource deleted"], Response::HTTP_NO_CONTENT);
        
    }
}