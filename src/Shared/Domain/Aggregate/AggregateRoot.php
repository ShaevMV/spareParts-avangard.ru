<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Domain\Aggregate;

use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

abstract class AggregateRoot extends AbstractionEntity
{
    private array $domainEvents = [];

    /**
     * @return DomainEvent[]
     */
    final public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
