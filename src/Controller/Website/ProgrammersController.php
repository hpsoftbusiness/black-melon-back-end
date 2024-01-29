<?php

namespace App\Controller\Website;

use App\Entity\Programmers;
use App\Repository\ProgrammersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammersController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/api/programmers', name: 'app.programmers')]
    public function programmersList()
    {
        $programmersRepository = $this->entityManager->getRepository(Programmers::class);
        $programmers = $programmersRepository->findAll();

        foreach ($programmers as $programmer) {
            if ($programmer->getLinkToGithub() === null) {
                $programmer->setLinkToGithub('----------');
            }
        }

        $serializedProgrammers = [];
        foreach ($programmers as $programmer) {
            $serializedProgrammers[] = [
                'id' => $programmer->getId(),
                'name' => $programmer->getName(),
                'surname' => $programmer->getSurname(),
                'birth_date' => $programmer->getBirthDate() ? $programmer->getBirthDate()->format('Y-m-d') : null,
                'height' => $programmer->getHeight(),
                'link_to_github' => $programmer->getLinkToGithub(),
            ];
        }

        return new JsonResponse($serializedProgrammers);
    }
}
