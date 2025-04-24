<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_index')]
    public function index(): Response {
        return $this->render('library/index.html.twig');
    }

    #[Route('/library/init', name: 'library_init')]
    public function initLibrary(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
    
        $books = [
            ['title' => '1984', 'isbn' => '9780451524935', 'author' => 'George Orwell', 'image' => '1984.jpg'],
            ['title' => 'Brave New World', 'isbn' => '9780060850524', 'author' => 'Aldous Huxley', 'image' => 'brave_new_world.jpg'],
            ['title' => 'Fahrenheit 451', 'isbn' => '9781451673319', 'author' => 'Ray Bradbury', 'image' => 'fahrenheit_451.jpg'],
        ];
    
        foreach ($books as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setIsbn($data['isbn']);
            $book->setAuthor($data['author']);
            $book->setImage($data['image']);
            $entityManager->persist($book);
        }
    
        $entityManager->flush();
    
        return $this->render('library/init.html.twig', [
            'bookCount' => count($books),
        ]);
    }    
}