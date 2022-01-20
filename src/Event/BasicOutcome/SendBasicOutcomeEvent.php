<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class SendBasicOutcomeEvent implements EventInterface
{
    public const TYPE = 'basicOutcomeSendBasicOutcome';

    public function __construct(
        private string $registrationId,
        private string $lisOutcomeServiceUrl,
        private string $xml
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLisOutcomeServiceUrl(): string
    {
        return $this->lisOutcomeServiceUrl;
    }

    public function getXml(): string
    {
        return $this->xml;
    }
}

