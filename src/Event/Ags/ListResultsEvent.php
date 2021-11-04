<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class ListResultsEvent implements EventInterface
{
    public const TYPE = 'agsListResults';

    public function __construct(
        private string $registrationId,
        private string $lineItemUrl,
        private ?string $userIdentifier = null,
        private ?int $limit = null,
        private ?int $offset = null,
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLineItemUrl(): string
    {
        return $this->lineItemUrl;
    }

    public function getUserIdentifier(): ?string
    {
        return $this->userIdentifier;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }
}
