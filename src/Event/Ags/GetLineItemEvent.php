<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class GetLineItemEvent implements EventInterface
{
    public const TYPE = 'agsGetLineItem';

    public function __construct(
        private string $registrationId,
        private string $lineItemUrl,
        private array $scopes = [],
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLineItemUrl(): string
    {
        return $this->lineItemUrl;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }
}
