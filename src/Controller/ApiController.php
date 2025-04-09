<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api_index')]
    public function apiOverview(): JsonResponse
    {
        $endpoints = [
            [
                'path' => '/api',
                'description' => 'Returns a list of all available JSON API endpoints',
            ],
            [
                'path' => '/api/quote',
                'description' => 'Returns a random quote',
            ],
            [
                'path' => '/lucky',
                'description' => 'Returns a random number between 0 and 100',
            ],
            [
                'path' => '/',
                'description' => 'Returns the home page',
            ],
            [
                'path' => '/about',
                'description' => 'Returns the about page',
            ],
            [
                'path' => '/report',
                'description' => 'Returns the report page',
            ],
        ];

        return $this->json(
            [
                'title' => 'API Overview',
                'endpoints' => $endpoints
            ],
            200,
            [],
            ['json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES]
        );
    }

    #[Route('/api/quote', name: 'api_quote')]
    public function quote(): JsonResponse
    {
        $quotes = [
            "Do or do not. There is no try. - Yoda",
            "Life is what happens when you're busy making other plans. - John Lennon",
            "The only way to do great work is to love what you do. - Steve Jobs",
            "Success is not final, failure is not fatal: It is the courage to continue that counts. - Winston Churchill",
            "You miss 100% of the shots you don't take. - Wayne Gretzky",
            "Be yourself; everyone else is already taken. - Oscar Wilde",
            "Happiness is not something ready-made. It comes from your own actions. - Dalai Lama",
            "In the middle of every difficulty lies opportunity. - Albert Einstein",
            "The truth is rarely pure and never simple. - Oscar Wilde",
            "A day without laughter is a day wasted. - Charlie Chaplin",
        ];
    
        $quote = $quotes[array_rand($quotes)];
    
        return $this->json(
            [
                'quote' => $quote,
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
            ],
            200,
            [],
            ['json_encode_options' => JSON_PRETTY_PRINT]
        );
    }    
}
