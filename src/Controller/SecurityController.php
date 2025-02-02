<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\JsonContent;


#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController
{
    public function __construct(private EntityManagerInterface $manager,
    private SerializerInterface $serializer,
    private UserPasswordHasherInterface $passwordHasher)
    {

    }
    #[Route('/registration', name: 'registration', methods: 'POST')]
    #[OA\Post(
        path: "/api/registration",
        summary: "Inscription d'un nouvel utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Données de l'utilisateur à inscrire",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "firstName", type: "string", example: "Thomas"),
                    new OA\Property(property: "lastName", type: "string", example: "Dupont"),
                    new OA\Property(property: "email", type: "string", example: "thomas@email.com"),
                    new OA\Property(property: "password", type: "string", example: "Mot de passe"),
                    new OA\Property(property: "telephone", type: "string", example: "0606060606"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Utilisateur inscrit avec succès",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "user", type: "string", example: "Nom d'utilisateur"),
                        new OA\Property(property: "apiToken", type: "string", example: "31a023e212f116124a36af14ea0c1c3806eb9378"),
                        new OA\Property(
                            property: "roles",
                            type: "array",
                            items: new OA\Items(type: "string", example: "ROLE_USER")
                        ),
                    ]
                )
            )
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
            ['user'  => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    #[OA\Post(
        path: "/api/login",
        summary: "Connecter un utilisateur",
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["username", "password"],
                    properties: [
                        new OA\Property(property: "username", type: "string"),
                        new OA\Property(property: "password", type: "string"),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Connexion réussie",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "X-AUTH-TOKEN", type: "string"),
                    ]
                )
            )
        ]
    )]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'user'  => $user->getUserIdentifier(),
            'apiToken' => $user->getApiToken(),
            'roles' => $user->getRoles(),
        ]);
    }

    #[Route('/account/me', name: 'me', methods: 'GET')]
    #[OA\Get(
        path: "/api/account/me",
        summary: "Récupérer toutes les informations de l'utilisateur connecté",
        security: [["bearerAuth" => []]], // Ajout pour sécuriser via un token JWT ou autre
        responses: [
            new OA\Response(
                response: 200,
                description: "Champs utilisateur retournés avec succès",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "email", type: "string", example: "user@example.com"),
                        new OA\Property(property: "telephone", type: "string", example: "0606060606"),
                        new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string")),
                        new OA\Property(property: "firstName", type: "string", example: "John"),
                        new OA\Property(property: "lastName", type: "string", example: "Doe"),
                        new OA\Property(property: "createdAt", type: "string", format: "date-time", example: "2023-01-01T12:00:00Z"),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Non autorisé (l'utilisateur n'est pas authentifié)",
            )
        ]
    )]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        $responseData = $this->serializer->serialize($user, 'json');

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/account/edit', name: 'edit', methods: 'PUT')]
    #[OA\Put(
        path: "/api/account/edit",
        summary: "Modifier son compte utilisateur",
        description: "Permet à l'utilisateur connecté de modifier ses informations personnelles, y compris le prénom, le nom, et éventuellement le mot de passe.",
        requestBody: new OA\RequestBody(
            required: true,
            description: "Nouvelles données utilisateur à mettre à jour",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "firstName", type: "string", example: "Nouveau prénom"),
                    new OA\Property(property: "lastName", type: "string", example: "Nouveau nom"),
                    new OA\Property(property: "password", type: "string", example: "NouveauMotDePasse"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 204,
                description: "Utilisateur modifié avec succès"
            ),
            new OA\Response(
                response: 400,
                description: "Données invalides fournies"
            ),
            new OA\Response(
                response: 401,
                description: "Non autorisé (l'utilisateur n'est pas authentifié)"
            )
        ]
    )]
    public function edit(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $this->getUser()],
        );
        $user->setUpdatedAt(new DateTimeImmutable());

        if (isset($request->toArray()['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }

        $this->manager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
