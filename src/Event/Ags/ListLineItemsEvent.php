<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class ListLineItemsEvent implements EventInterface
{
    public const TYPE = 'agsListLineItems';

    public function __construct(
        private string $registrationId,
        private string $lineItemsContainerUrl,
        private ?string $resourceIdentifier = null,
        private ?string $resourceLinkIdentifier = null,
        private ?string $tag = null,
        private ?int $limit = null,
        private ?int $offset = null,
        private ?array $scopes = null
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLineItemsContainerUrl(): string
    {
        return $this->lineItemsContainerUrl;
    }

    public function getResourceIdentifier(): ?string
    {
        return $this->resourceIdentifier;
    }

    public function getResourceLinkIdentifier(): ?string
    {
        return $this->resourceLinkIdentifier;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getScopes(): ?array
    {
        return $this->scopes;
    }
}
