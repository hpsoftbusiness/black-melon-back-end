<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Common\DoctrineListRepresentationFactory;
use App\Entity\Programmers;
use App\Repository\ProgrammersRepository;
use DateTimeImmutable;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @phpstan-type ProgrammerData array{
 *     id: int|null,
 *     name: string,
 *     surname: string,
 *     birthDate: string|null,
 *     height: int|null,
 *     linkToGithub: string|null,
 * }
 */
class ProgrammersController extends AbstractController
{
    public function __construct(
        private readonly ProgrammersRepository $programmersRepository,
        private readonly MediaRepositoryInterface $mediaRepository,
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
    ) {
    }

    #[Route(path: '/admin/api/programmers/{id}', methods: ['GET'], name: 'app.get_programmer')]
    public function getAction(int $id, Request $request): Response
    {
        $programmer = $this->load($id);
        if (!$programmer instanceof Programmers) {
            throw new NotFoundHttpException();
        }

        return $this->json($this->getDataForEntity($programmer));
    }

    #[Route(path: '/admin/api/programmers/{id}', methods: ['PUT'], name: 'app.put_programmer')]
    public function putAction(int $id, Request $request): Response
    {
        $programmer = $this->load($id);
        if (!$programmer instanceof Programmers) {
            throw new NotFoundHttpException();
        }

        /** @var ProgrammerData $data */
        $data = $request->toArray();
        $this->mapDataToEntity($data, $programmer);
        $this->save($programmer);

        return $this->json($this->getDataForEntity($programmer));
    }

    #[Route(path: '/admin/api/programmers', methods: ['POST'], name: 'app.post_programmer')]
    public function postAction(Request $request): Response
    {
        $programmer = $this->create();

        /** @var ProgrammerData $data */
        $data = $request->toArray();
        $this->mapDataToEntity($data, $programmer);
        $this->save($programmer);

        return $this->json($this->getDataForEntity($programmer), 201);
    }

    #[Route(path: '/admin/api/programmers/{id}', methods: ['DELETE'], name: 'app.delete_programmer')]
    public function deleteAction(int $id): Response
    {
        $this->remove($id);

        return $this->json(null, 204);
    }

    #[Route(path: '/admin/api/programmers', methods: ['GET'], name: 'app.get_programmers_list')]
    public function getListAction(Request $request): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            Programmers::RESOURCE_KEY,
            [],
            ['locale' => $this->getLocale($request)],
        );

        return $this->json($listRepresentation->toArray());
    }

    /**
     * @return ProgrammerData $data
     */
    protected function getDataForEntity(Programmers $entity): array
    {
        return [
            'id' => $entity->getId(),
            'name' => $entity->getName(),
            'surname' => $entity->getSurname(),
            'birthDate' => null !== $entity->getBirthDate() ? $entity->getBirthDate()->format('c') : null,
            'height' => $entity->getHeight(),
            'linkToGithub' => $entity->getLinkToGithub(),
        ];
    }

    /**
     * @param ProgrammerData $data
     */
    protected function mapDataToEntity(array $data, Programmers $entity): void
    {
        $entity->setName($data['name']);
        $entity->setSurname($data['surname']);
        $entity->setBirthDate($data['birthDate'] ? new DateTimeImmutable($data['birthDate']) : null);
        $entity->setHeight($data['height']);
        $entity->setLinkToGithub($data['linkToGithub'] ?? null);
    }

    protected function load(int $id): ?Programmers
    {
        return $this->programmersRepository->find($id);
    }

    protected function create(): Programmers
    {
        return new Programmers();
    }

    protected function save(Programmers $entity): void
    {
        $this->programmersRepository->save($entity);
    }

    protected function remove(int $id): void
    {
        $this->programmersRepository->remove($id);
    }

    private function getLocale(Request $request): ?string
    {
        return $request->query->has('locale') ? (string) $request->query->get('locale') : null;
    }
}
