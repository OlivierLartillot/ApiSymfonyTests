<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $userPasswordhasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordhasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        
        $authors = [];
        for ($i = 0; $i < 20; $i++) {
            $author = new Author();
            $author->setLastname("Nom " . $i);
            $author->setFirstname("Prénom " . $i);
            $manager->persist($author);
            $authors[] = $author; 
        }
        for ($i = 0; $i < 20; $i++) {
            $book = new Book();
            $book->setTitle("Titre " . $i);
            $book->setCoverText("Quatrieme de couverture " . $i);
            $book->setAuthor($authors[array_rand($authors)]);
            $manager->persist($book);
        }
        


        $user = new User();
        $user->setEmail('user@bookapi.fr');
        $user->setPassword($this->userPasswordhasher->hashPassword($user, "password"));
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin@bookapi.fr');
        $user->setPassword($this->userPasswordhasher->hashPassword($user, "password"));
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);



        $manager->flush();
    }
}
