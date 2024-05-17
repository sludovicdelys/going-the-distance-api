<?php
namespace App\Controller;

use App\Entity\Run;
use App\Repository\RunRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RunController extends AbstractController
{
    private $runRepository;
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(RunRepository $runRepository, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->runRepository = $runRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/runs', name: 'runs_list', methods: ['GET'])]
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

        // Return JSON response
        return new JsonResponse(['formattedRuns' => $formattedRuns]);
    }

    #[Route('/runs', name: 'runs_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = $request->getContent();
        $run = $this->serializer->deserialize($data, Run::class, 'json');

        $errors = $this->validator->validate($run);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($run);
        $this->entityManager->flush();

        return new JsonResponse('Run created successfully', JsonResponse::HTTP_CREATED);
    }

    #[Route('/runs/{id}', name: 'runs_read', methods: ['GET'])]
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

    #[Route('/runs/{id}', name: 'runs_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $updatedRun = $this->serializer->deserialize($data, Run::class, 'json');

        // Manually update the run entity
        $run->setUsername($updatedRun->getUsername());
        $run->setType($updatedRun->getType());
        $run->setAverageSpeed($updatedRun->getAverageSpeed());
        $run->setRunningPace($updatedRun->getRunningPace());
        $run->setStartDate($updatedRun->getStartDate());
        $run->setStartTime($updatedRun->getStartTime());
        $run->setTime($updatedRun->getTime());
        $run->setDistance($updatedRun->getDistance());
        $run->setComments($updatedRun->getComments());

        $errors = $this->validator->validate($run);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse('Run updated successfully', JsonResponse::HTTP_OK);
    }

    #[Route('/runs/{id}', name: 'runs_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($run);
        $this->entityManager->flush();

        return new JsonResponse('Run deleted successfully', JsonResponse::HTTP_NO_CONTENT);
    }
}