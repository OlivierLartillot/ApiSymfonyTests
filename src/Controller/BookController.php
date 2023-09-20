<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class BookController extends AbstractController
{

     // !! recommandations GET List : code 200 - ok !!   
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

    // !! recommandations GET List : code 200 - ok !!
    #[Route('/api/books/{id}', name: 'oneBook', methods:['GET'])]
    public function getOneBook(Book $book, SerializerInterface $serializerInterface): JsonResponse
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

    // !! recommandations DELETE : code 204 - no content !!
    #[Route('/api/books/{id}', name: 'deleteBook', methods:['DELETE'])]
    public function deleteOneBook(Book $book, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($book);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);    
    }

    // !! recommandations POST : code 201 - created. Retourner le post nouvellement créer !!
    #[Route('/api/books', name: 'createBook', methods:['POST'])]
    public function createOneBook(Request $request,AuthorRepository $authorepository, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
       

        
        $book = $serializer->deserialize($request->getContent(), Book::class, 'json');
        // on va mettre a jour l'auteur
        $content = $request->toArray();
        $author = $authorepository->find($content['idAuthor']);
        $book->setAuthor($author);
        
        $em->persist($book);
        $em->flush();
        


        // on re-serialize pour renvoyer le book nouvellement créer
        $jsonBook = $serializer->serialize($book, 'json', ['groups' => 'getBooks']);

        // BONNE PRATIQUE: envoyer la location dans le header => rediriger vers le book nouvellement créer
        $location = $urlGenerator->generate('oneBook', ['id' => $book->getId()], UrlGeneratorInterface::ABSOLUTE_PATH);

        return new JsonResponse($jsonBook, JsonResponse::HTTP_CREATED, ["Location" => $location], true);    
    }

    // !! recommandations PUT : réponse vide avec un  204 - No content . Cela signifie que l’opération s’est bien passée !!
    #[Route('/api/books/{id}', name: 'updateBook', methods:['PUT'])]
    public function updateOneBook(Book $currentBook, Request $request, AuthorRepository $authorepository, EntityManagerInterface $em, SerializerInterface $serializer): JsonResponse
    {

        $updateBook = $serializer->deserialize(
            $request->getContent(), 
            Book::class, 
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentBook]
        );

        // on va mettre a jour l'auteur
        $content = $request->toArray();
        $author = $authorepository->find($content['idAuthor']);
        $updateBook->setAuthor($author);

        $em->persist($updateBook);
        $em->flush();

        // on est déja dans la page de ce livre ... donc pas besoin de retourner a nouveau le livre
       // $jsonBook = $serializer->serialize($updateBook, 'json', ['groups' => 'getBooks']);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    
    
    }

}
