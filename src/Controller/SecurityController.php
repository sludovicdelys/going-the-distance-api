<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        // Extract username and password from request
        $username = $request->getUser();
        $password = $request->getPassword();

        // Validate credentials
        if (!$this->isValidCredentials($username, $password)) {
            throw new BadCredentialsException('Invalid username or password');
        }

        // Return success response
        return new Response('Authenticated successfully');
    }

    private function isValidCredentials(string $username, string $password): bool
    {
        // Implement your authentication logic here, such as checking against a database
        // For demonstration purposes, let's assume a hardcoded username and password
        $validUsername = 'admin';
        $validPassword = 'secret';

        return $username === $validUsername && $password === $validPassword;
    }
}