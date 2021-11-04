<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Core;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class RequestEvent implements EventInterface
{
    public const TYPE = 'coreRequest';

    public function __construct(
        private string $registrationId,
        private string $method,
        private string $uri,
        private array $options = [],
        private array $scopes = []
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }
}
