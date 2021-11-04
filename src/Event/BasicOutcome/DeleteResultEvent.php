<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class DeleteResultEvent implements EventInterface
{
    public const TYPE = 'basicOutcomeDeleteResult';

    public function __construct(
        private string $registrationId,
        private string $lisOutcomeServiceUrl,
        private string $lisResultSourcedId
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLisOutcomeServiceUrl(): string
    {
        return $this->lisOutcomeServiceUrl;
    }

    public function getLisResultSourcedId(): string
    {
        return $this->lisResultSourcedId;
    }
}
