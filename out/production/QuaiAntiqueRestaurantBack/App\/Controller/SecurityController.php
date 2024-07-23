<?php

namespace App\Controller;

use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;


#[Route('/api', name: 'app_api_')]
class SecurityController extends AbstractController

{
    public function __construct(
        private EntityManagerInterface $manager,
        private SerializerInterface    $serializer)
    {
    }

    #[Route('/registration', name: 'registration', methods: 'POST')]
    /**
     * @OA\Post(
     *      path="/api/registration",
     *      summary="Inscription d'un utilisateur",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Données de l'utilisateur",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="email",
     *                  type="string",
     *                  format="email",
     *                  example="votre-mail@mail.com"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  example="votre-mot-de-passe"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Utilisateur créé avec succès",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="user",
     *                  type="string",
     *                  example="votre-mail@mail.com"
     *              ),
     *              @OA\Property(
     *                  property="apiToken",
     *                  type="string",
     *                  example="votre-token"
     *              ),
     *              @OA\Property(
     *                  property="roles",
     *                  type="array",
     *                  @OA\Items(
     *                      type="string",
     *                      example="ROLE_USER"
     *                  )
     *              )
     *          )
     *      )
     * )
     */

    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setCreatedAt(new DateTimeImmutable());
        $this->manager->persist($user);
        $this->manager->flush();
        return new JsonResponse(
            ['user' => $user->getUserIdentifier(), 'apiToken' => $user->getApiToken(), 'roles' => $user->getRoles()],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null !== $user) {
            // L'utilisateur est authentifié, retourner les détails de l'utilisateur
            return new JsonResponse([
                'user' => $user->getUserIdentifier(),
                'apiToken' => $user->getApiToken(),
                'roles' => $user->getRoles(),
            ]);
        }
        // Aucun utilisateur authentifié, retourner un message d'erreur
        return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
    }
}
