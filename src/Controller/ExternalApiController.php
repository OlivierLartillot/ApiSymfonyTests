<?php

namespace App\Controller;

use App\Entity\StarWarsPeople;
use OpenApi\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExternalApiController extends AbstractController
{
    #[Route('/api/external/getSfDoc', name: 'external_api')]
    public function index(HttpClientInterface $httpClient): JsonResponse
    {

        $response = $httpClient->request(
            'GET',
            'https://api.github.com/repos/symfony/symfony-docs'
        );

        return new JsonResponse($response->getContent(), $response->getStatusCode(), [], true);
    }

    #[Route('/api/external/Swapi/people/{id}', name: 'external_api')]
    public function starsWarsOnePeople($id, HttpClientInterface $httpClient, SerializerInterface $serializer): JsonResponse
    {

        $response = $httpClient->request(
            'GET',
            'https://swapi.dev/api/people/'.$id
        );

        $peopleJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        
        $people = new StarWarsPeople();
        $name = $peopleJson['name'];
        $gender = $peopleJson['gender'] == 'male' ? 1 : 0;
        $people->setName($name);
        $people->setGender($gender);

        $peopleJson = $serializer->serialize($people, 'json', ['groups' => 'StarWarsOnePeople']);

        return new JsonResponse($peopleJson, $response->getStatusCode(), [], true);
    }

}
