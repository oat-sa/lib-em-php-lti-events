<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class ReplaceResultEvent implements EventInterface
{
    public const TYPE = 'basicOutcomeReplaceResult';

    public function __construct(
        private string $registrationId,
        private string $lisOutcomeServiceUrl,
        private string $lisResultSourcedId,
        private float $score,
        private string $language = 'en'
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

    public function getScore(): float
    {
        return $this->score;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }
}
