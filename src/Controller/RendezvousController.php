<?php

namespace App\Controller;

use App\Entity\Rendezvous;
use App\Repository\RendezVousRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('api/rendezvous', name: 'app_api_rendezvous_')]
class RendezvousController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private RendezVousRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator,)
    {
    }

    #[Route(methods: 'POST')]
    public function new(Request $request): JsonResponse
    {
        $rendezvous = $this->serializer->deserialize($request->getContent(), RendezVous::class, 'json');
        $rendezvous->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($rendezvous);
        $this->manager->flush();

        $responseData = $this->serializer->serialize($rendezvous, 'json');
        $location = $this->urlGenerator->generate(
            'app_api_rendezvous_show',
            ['id' => $rendezvous->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL,
        );

        return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $rendezvous = $this->repository->findOneBy(['id' => $id]);
        if ($rendezvous) {
            $responseData = $this->serializer->serialize($rendezvous, 'json');

            return new JsonResponse($responseData, Response::HTTP_OK, [], true);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
    

    #[Route('/{id}', name: 'edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        // … Edite et sauvegarde en base de données
        $rendezvous = $this->repository->findOneBy(['id' => $id]);
        if ($rendezvous) {
            $rendezvous = $this->serializer->deserialize(
                $request->getContent(),
                RendezVous::class, 'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $rendezvous]
            );
            $rendezvous->setUpdatedAt(new DateTimeImmutable());

            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        // ... Supprime le rendezvous$rendezvous de la base de données
        $rendezvous = $this->repository->findOneBy(['id' => $id]);
        if ($rendezvous) {
            $this->manager->remove($rendezvous);
            $this->manager->flush();

            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}