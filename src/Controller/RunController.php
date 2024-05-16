<?php
namespace App\Controller;

use App\Entity\Run;
use App\Repository\RunRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RunController extends AbstractController
{
    private $runRepository;

    public function __construct(RunRepository $runRepository)
    {
        $this->runRepository = $runRepository;
    }

    public function index(): JsonResponse
    {
        $runs = $this->runRepository->findAll();

        // Format data
        $formattedRuns = [];
        foreach ($runs as $run) {
            $formattedRuns[] = [
                'id' => $run->getId(),
                'username' => $run->getUsername(),
                'type' => $run->getType(),
                'average_speed' => $run->getAverageSpeed(),
                'running_pace' => $run->getRunningPace()->format('H:i:s'),
                'start_date' => $run->getStartDate()->format('Y-m-d'),
                'start_time' => $run->getStartTime()->format('H:i:s'),
                'time' => $run->getTime()->format('H:i:s'),
                'distance' => $run->getDistance(),
                'comments' => $run->getComments(),
            ];
        }

        // Render a Twig template
        $html = $this->renderView('runs/index.html.twig', [
            'formattedRuns' => $formattedRuns,
        ]);

        // Return JSON data along with HTML
        return new JsonResponse([
            'formattedRuns' => $formattedRuns,
            'html' => $html,
        ]);
    }

    // New method for retrieving a specific run
    public function read(int $id): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], 404);
        }

        // Format the response data
        $formattedRun = [
            'id' => $run->getId(),
            'username' => $run->getUsername(),
            'type' => $run->getType(),
            'average_speed' => $run->getAverageSpeed(),
            'running_pace' => $run->getRunningPace()->format('H:i:s'),
            'start_date' => $run->getStartDate()->format('Y-m-d'),
            'start_time' => $run->getStartTime()->format('H:i:s'),
            'time' => $run->getTime()->format('H:i:s'),
            'distance' => $run->getDistance(),
            'comments' => $run->getComments(),
        ];

        return $this->json($formattedRun);
    }
}