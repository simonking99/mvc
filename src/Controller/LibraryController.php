<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Book;
use App\Form\BookType;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BookRepository;
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
            ['title' => 'Harry Potter and the Philosopher Stone', 'isbn' => '9781408855652', 'author' => 'J.K. Rowling', 'image' => 'https://m.media-amazon.com/images/I/81q77Q39nEL._SL1500_.jpg'],
            ['title' => 'Harry Potter and the Chamber of Secrets', 'isbn' => '1408855666', 'author' => 'J.K. Rowling', 'image' => 'https://m.media-amazon.com/images/I/818umIdoruL._SL1500_.jpg'],
            ['title' => 'Harry Potter and the Prisoner of Azkaban', 'isbn' => '1408855674', 'author' => 'J.K. Rowling', 'image' => 'https://m.media-amazon.com/images/I/81NQA1BDlnL._SL1500_.jpg'],



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
    
    #[Route('/library/books', name: 'library_books', methods: ['GET'])]
    public function showAllBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();
    
        return $this->render('library/books.html.twig', [
            'books' => $books
        ]);
    }    

    #[Route('/library/create', name: 'library_create', methods: ['GET', 'POST'])]
    public function createBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('library_books');
        }

        return $this->render('library/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}