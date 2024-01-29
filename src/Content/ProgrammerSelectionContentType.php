<?php

declare(strict_types=1);

namespace App\Content;

use App\Entity\Programmers;
use App\Repository\ProgrammersRepository;
use Sulu\Component\Content\Compat\PropertyInterface;
use Sulu\Component\Content\SimpleContentType;

class ProgrammerSelectionContentType extends SimpleContentType
{
    public function __construct(
        private readonly ProgrammersRepository $programmersRepository,
    ) {
        parent::__construct('programmer_selection');
    }

    /**
     * @return Programmers[]
     */
    public function getContentData(PropertyInterface $property): array
    {
        $ids = $property->getValue();


        $programmers = [];
        foreach ($ids ?: [] as $id) {
            $programmer = $this->programmersRepository->findById((int) $id);
            if ($programmer) {
                $programmers[] = $programmer;
            }
        }

        return $programmers;
    }

    /**
     * @return mixed[]
     */
    public function getViewData(PropertyInterface $property): array
    {
        return $property->getValue();
    }
}
