<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{
    #[Route('/api/books', name: 'books', methods:['GET'])]
    public function getAllBooks(BookRepository $bookRepository, SerializerInterface $serializerInterface): JsonResponse
    {

        $books = $bookRepository->findAll();
        $jsonBooks = $serializerInterface->serialize($books, 'json', ['groups' => 'getBooks']);
        return new JsonResponse(
            $jsonBooks,
            Response::HTTP_OK, 
            [], 
            true
        );
    }

    #[Route('/api/books/{id}', name: 'oneBook', methods:['GET'])]
    public function getOneBook(Book $book, BookRepository $bookRepository, SerializerInterface $serializerInterface): JsonResponse
    {

        $jsonBook = $serializerInterface->serialize($book, 'json', ['groups' => 'getBooks']);
        return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);        

    /* 
        ! pas nécessaire grace au param converter !
        if ($book) {
            $jsonBook = $serializerInterface->serialize($book, 'json');
            return new JsonResponse($jsonBook, Response::HTTP_OK, [], true);
        }
        
        return new JsonResponse(null, Response::HTTP_NOT_FOUND); */
    }

    #[Route('/api/books/{id}', name: 'deleteBook', methods:['DELETE'])]
    public function deleteOneBook(Book $book, EntityManagerInterface $em): JsonResponse
    {

        $em->remove($book);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);    
    }



}
