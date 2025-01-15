<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Repository\ActivityRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('api/activity', name: 'app_api_activity_')]
class ActivityController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ActivityRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,)
    {
    }

    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $activity = $this->serializer->deserialize($request->getContent(), Activity::class, 'json');
        $activity->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($activity);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($activity, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_activity_show',
            ['id' => $activity->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $activity = $this->repository->findOneBy(['id' => $id]);
        if ($activity) {
            $responseData = $this->serializer->serialize($activity, 'json');

            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        // … Edite et sauvegarde en base de données
        $activity = $this->repository->findOneBy(['id' => $id]);
        if ($activity) {
            $activity = $this->serializer->deserialize(
                $request->getContent(),
                Activity::class, 'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $activity]
            );
            $activity->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        // ... Supprime le activity de la base de données
        $activity = $this->repository->findOneBy(['id' => $id]);
        if ($activity) {
            $this->manager->remove($activity);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}