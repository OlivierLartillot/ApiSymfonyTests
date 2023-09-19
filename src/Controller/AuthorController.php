<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorController extends AbstractController
{
    #[Route('api/authors', name: 'authors', methods:['GET'])]
    public function getAllAuthors(AuthorRepository $authorRepository, SerializerInterface $serializer): JsonResponse
    {

        $authors = $authorRepository->findAll();
        $authorsJson = $serializer->serialize($authors, 'json', ['groups' => 'getAuthors']);

        return new JsonResponse($authorsJson,200,[], true);
    }

    #[Route('api/authors/{id}', name: 'authors', methods:['GET'])]
    public function getOneAuthor(Author $author, SerializerInterface $serializer): JsonResponse
    {

        $authorJson = $serializer->serialize($author, 'json', ['groups' => 'getAuthors']);
        return new JsonResponse($authorJson,200,[], true);
    }



}
