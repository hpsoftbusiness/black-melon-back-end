<?php

declare(strict_types=1);

namespace App\Content;

use App\Entity\Programmers;
use JMS\Serializer\Annotation as Serializer;
use Sulu\Component\SmartContent\ItemInterface;

class ProgrammerDataItem
{
    public function __construct(
        /**
         * @Serializer\Exclude
         */
        private readonly Programmers $entity,
    ) {
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getId()
    {
        return (string) $this->entity->getId();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getName()
    {
        return (string) $this->entity->getName();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getSurname()
    {
        return (string) $this->entity->getSurname();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getBirthDate()
    {
        return $this->entity->getBirthDate() ? $this->entity->getBirthDate()->format('c') : null;
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getHeight()
    {
        return $this->entity->getHeight();
    }

    /**
     * @Serializer\VirtualProperty
     */
    public function getLinkToGithub()
    {
        return $this->entity->getLinkToGithub();
    }

    public function getResource(): Programmers
    {
        return $this->entity;
    }
}
