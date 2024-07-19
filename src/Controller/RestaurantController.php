<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\RestaurantRepository;
use App\Entity\Restaurant;
use DateTimeImmutable;

#[Route('/api/restaurant', name: 'app_api_restaurant_')]
class RestaurantController extends AbstractController
{
    
    public function __construct(
        private EntityManagerInterface $manager,
        private RestaurantRepository $repository,
        private SerializerInterface $serializer,
        private UrlGeneratorInterface $urlGenerator
        ) {
        }
        
        // POST /api/restaurant
        #[Route(methods: 'POST')]
        public function new(Request $request): JsonResponse
        {
            $restaurant = $this->serializer->deserialize($request->getContent(), Restaurant::class, 'json');
            $restaurant->setCreatedAt(new DateTimeImmutable());
            
            $this->manager->persist($restaurant);
            $this->manager->flush();
            
            $responseData = $this->serializer->serialize($restaurant, 'json');
            $location = $this->urlGenerator->generate(
                'app_api_restaurant_show',
                ['id' => $restaurant->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );
            return new JsonResponse($responseData, Response::HTTP_CREATED, ["Location" => $location], true);
        }
        
        // GET /api/restaurant/{id}
        #[Route('/{id}', name: 'show', methods: 'GET')]
        public function show(int $id): JsonResponse
        {
            $restaurant = $this->repository->findOneBy(['id' => $id]);
            if (!$restaurant) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }

            $responseData = $this->serializer->serialize($restaurant, 'json');
            return new JsonResponse($responseData, Response::HTTP_OK, [], true);

        }
        
        // PUT /api/restaurant/{id}
        #[Route('/{id}', name: 'edit', methods: 'PUT')]
        public function edit(int $id, Request $request): JsonResponse
        {
            $restaurant = $this->repository->findOneBy(['id' => $id]);
            if (!$restaurant) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            $restaurant = $this->serializer->deserialize(
                $request->getContent(),
                Restaurant::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant]
            );
            $restaurant->setUpdateAt(new DateTimeImmutable());
            $this->manager->flush();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
        
        // DELETE /api/restaurant/{id}
        #[Route('/{id}', name: 'delete', methods: 'DELETE')]
        public function delete(int $id): JsonResponse
        {
            $restaurant = $this->repository->findOneBy(['id' => $id]);
            if (!$restaurant) {
                return new JsonResponse(null, Response::HTTP_NOT_FOUND);
            }
            $this->manager->remove($restaurant);
                $this->manager->flush();
                
                return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }
    }
