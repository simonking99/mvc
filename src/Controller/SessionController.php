<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionController extends AbstractController
{
    #[Route("/session", name: "session")]
    public function sessionDebug(SessionInterface $session): Response {   
    $sessionData = $session->all();
    return $this->render('session/session_debug.html.twig', [
        'sessionData' => $sessionData
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function sessionDelete(SessionInterface $session): Response {
        $session->clear();
        $this->addFlash('Success', 'Session is now deleted.');

        return $this->redirectToRoute('session');
    }
}
