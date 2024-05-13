<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    public function index(Request $request): Response
    {
        return new Response('Hello ' . $request->query->get('name', 'Stranger'));
    }
}
