<?php
namespace App\Controller;

use App\Repository\RunRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class GetRunsByUserController extends AbstractController
{
    private $runRepository;
    private $entityManager;
    private $serializer;
    private $validator;

    public function __construct(RunRepository $runRepository)
    {
        $this->runRepository = $runRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/users/{id}/runs', name: 'get_user_runs', methods: ['GET'])]
    public function getRuns(int $id): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user || $user->getId() !== $id) {
            return new JsonResponse(['error' => 'Access Denied'], Response::HTTP_FORBIDDEN);
        }

        $runs = $this->runRepository->findBy(['user' => $id]);

        if (!$runs) {
            return new JsonResponse(['message' => 'No runs found for the user'], Response::HTTP_NOT_FOUND);
        }

        $responseData = array_map(function ($run) {
            return [
                'id' => $run->getId(),
                'distance' => $run->getDistance(),
                'time' => $run->getTime()->format('H:i:s'),
                'date' => $run->getStartDate()->format('Y-m-d'),
                'comments' => $run->getComments()
            ];
        }, $runs);

        return new JsonResponse($responseData, Response::HTTP_OK);
    }
}