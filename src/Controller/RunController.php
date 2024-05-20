<?php

namespace App\Controller;

use App\Entity\Run;
use App\Entity\User;
use App\Repository\RunRepository;
use App\Repository\UserRepository;
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
    private $userRepository;
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(
        RunRepository $runRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->runRepository = $runRepository;
        $this->userRepository = $userRepository;
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
                'username' => $run->getUser()->getUsername(),
                'type' => $run->getType(),
                'average_speed' => $run->getAverageSpeed(),
                'running_pace' => $run->getRunningPace() !== null 
                // If running pace is not null, calculate minutes and seconds
                ? floor($run->getRunningPace() / 60) . ':' . sprintf('%02d', ($run->getRunningPace() % 60)) . 'min/km' 
                // If running pace is null, return null
                : null,
                'start_date' => $run->getStartDate()->format('Y-m-d'),
                'start_time' => $run->getStartTime()->format('H:i'),
                'time' => $run->getTime()->format('H:i'),
                'distance' => $run->getDistance(),
                'comments' => $run->getComments(),
            ];
        }

        // Serialize the runs
        $serializedRuns = $this->serializer->serialize($formattedRuns, 'json', ['groups' => 'run:read']);

        return new JsonResponse($serializedRuns, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json'], true);
    }


    #[Route('/runs', name: 'runs_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Check if user exists, if not, create a new user
        $username = $data['user']['username'];
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $user = new User();
            $user->setUsername($username);

            // Optionally, validate the user entity
            $userErrors = $this->validator->validate($user);
            if (count($userErrors) > 0) {
                return new JsonResponse((string) $userErrors, JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
            }

            $this->entityManager->persist($user);
        }

        // Create and populate the run entity
        $run = new Run();
        $run->setUser($user);
        $run->setType($data['type']);
        $run->setStartDate(new \DateTime($data['start_date']));
        $run->setStartTime(new \DateTime($data['start_time']));
        $run->setTime(new \DateTime($data['time']));
        $run->setDistance($data['distance']);
        $run->setComments($data['comments']);

        // Calculate average speed and running pace
        $run->calculateAverageSpeed();
        $run->calculateRunningPace();

        $errors = $this->validator->validate($run);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $this->entityManager->persist($run);
        $this->entityManager->flush();

        return new JsonResponse('Run created successfully', JsonResponse::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    #[Route('/runs/{id}', name: 'runs_read', methods: ['GET'])]
    public function read(int $id): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], JsonResponse::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        // Format the response data
        $formattedRun = [
            'id' => $run->getId(),
            'username' => $run->getUser()->getUsername(),
            'type' => $run->getType(),
            'average_speed' => $run->getAverageSpeed(),
            'running_pace' => $run->getRunningPace() !== null 
            // If running pace is not null, calculate minutes and seconds
            ? floor($run->getRunningPace() / 60) . ':' . sprintf('%02d', ($run->getRunningPace() % 60)) . 'min/km' 
            // If running pace is null, return null
            : null,
            'start_date' => $run->getStartDate()->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d'),
            'start_time' => $run->getStartTime()->setTimezone(new \DateTimeZone('UTC'))->format('H:i:s'),
            'time' => $run->getTime()->setTimezone(new \DateTimeZone('UTC'))->format('H:i:s'),
            'distance' => $run->getDistance(),
            'comments' => $run->getComments(),
        ];

        // Serialize the runs
        $serializedRun = $this->serializer->serialize($formattedRun, 'json', ['groups' => 'run:read']);

        return new JsonResponse($serializedRun, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json'], true);
    }

    #[Route('/runs/{id}', name: 'runs_update', methods: ['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], JsonResponse::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $data = json_decode($request->getContent(), true);

        // Check if user exists, if not, create a new user
        $username = $data['user']['username'];
        $user = $this->userRepository->findOneBy(['username' => $username]);

        if (!$user) {
            $user = new User();
            $user->setUsername($username);

            // Optionally, validate the user entity
            $userErrors = $this->validator->validate($user);
            if (count($userErrors) > 0) {
                return new JsonResponse((string) $userErrors, JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
            }

            $this->entityManager->persist($user);
        }

        // Manually update the run entity
        $run->setUser($user);
        $run->setType($data['type']);
        $run->setStartDate(new \DateTime($data['start_date']));
        $run->setStartTime(new \DateTime($data['start_time']));
        $run->setTime(new \DateTime($data['time']));
        $run->setDistance($data['distance']);
        $run->setComments($data['comments']);

        $run->calculateAverageSpeed();
        $run->calculateRunningPace();

        $errors = $this->validator->validate($run);
        if (count($errors) > 0) {
            return new JsonResponse((string) $errors, JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $this->entityManager->flush();

        return new JsonResponse('Run updated successfully', JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/runs/{id}', name: 'runs_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $run = $this->runRepository->find($id);

        if (!$run) {
            return new JsonResponse(['error' => 'Run not found'], JsonResponse::HTTP_NOT_FOUND, ['Content-Type' => 'application/json']);
        }

        $this->entityManager->remove($run);
        $this->entityManager->flush();

        return new JsonResponse('Run deleted successfully', JsonResponse::HTTP_NO_CONTENT, ['Content-Type' => 'application/json']);
    }
}