<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;

final class LibraryController extends AbstractController
{
    #[Route('/library', name: 'library_index')]
    public function index(): Response {
        return $this->render('library/index.html.twig');
    }
}