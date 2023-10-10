<?php

namespace App\Controller;

use App\Entity\StarWarsPeople;
use App\Repository\StarWarsPeopleRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/api/external/Swapi/people/{id}', name: 'external_api_star_wars_create_people')]
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

    #[Route('/api/external/Swapi/people/{id}', name: 'external_api_star_wars_create_people', methods:['POST'])]
    public function CreateStarsWarsOnePeople($id, HttpClientInterface $httpClient, SerializerInterface $serializer, StarWarsPeopleRepository $starWarsPeopleRepository, EntityManagerInterface $em): JsonResponse
    {

        $response = $httpClient->request(
            'GET',
            'https://swapi.dev/api/people/'.$id
        );

        
        $peopleJson = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        
        $planetLink = $peopleJson['homeworld'];

        $planetObject = $httpClient->request(
            'GET',
            $planetLink
        );
        $planetJson = json_decode($planetObject->getContent(), true, 512, JSON_THROW_ON_ERROR);
    
        $people = new StarWarsPeople();
            
        // je vais tester si ce personnnage a déja été importé => OUI je veux choisir quels personnages vont être importé :p    
            $peopleExist = $starWarsPeopleRepository->findOneBy([
                'peopleIdDist' => $id
            ]);

        if (!$peopleExist) {
            $name = $peopleJson['name'];
            $gender = $peopleJson['gender'] == 'male' ? 1 : 0; // en realité il faudrait d'autres infos ... le robot n est ni male ni female ... ahah
            $planet = $planetJson['name'];
            $people->setPeopleIdDist($id);
            $people->setName($name);
            $people->setGender($gender);   
            $people->setPlanet($planet);  
            $em->persist($people);
            $em->flush();
            $peopleJson = $serializer->serialize($people, 'json', ['groups' => 'StarWarsOnePeople']);
    
            return new JsonResponse($peopleJson, JsonResponse::HTTP_CREATED, [], true);
        }
        
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        

    }

}
