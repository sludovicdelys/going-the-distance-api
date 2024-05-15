<?php
namespace App\Controller;

use App\Entity\Run;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class RunController extends AbstractController
{
    public function index(): JsonResponse
    {
        $runs = $this->getDoctrine()->getRepository(Run::class)->findAll();

        // Formater les données
        $formattedRuns = [];
        foreach ($runs as $run) {
            $formattedRuns[] = [
                'id' => $run->getId(),
                'date' => $run->getDate()->format('Y-m-d'),
                // Autres champs...
            ];
        }

        return $this->json($runs);
    }
}