<?php

declare(strict_types=1);

namespace App\Content;

use App\Entity\Programmers;
use Sulu\Component\SmartContent\Orm\BaseDataProvider;

class ProgrammerDataProvider extends BaseDataProvider
{
    public function getConfiguration()
    {
        if (null === $this->configuration) {
            $this->configuration = self::createConfigurationBuilder()
                ->enableLimit()
                ->enablePagination()
                ->enableSorting(
                    [
                        ['column' => 'name', 'title' => 'sulu_admin.name'],
                        // Add more sorting options as needed
                    ],
                )
                ->getConfiguration();
        }

        return parent::getConfiguration();
    }

    /**
     * @param Programmers[] $data
     */
    protected function decorateDataItems(array $data): array
    {
        return \array_map(
            fn (Programmers $item) => new ProgrammerDataItem($item),
            $data,
        );
    }
}
