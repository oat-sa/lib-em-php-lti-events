<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Proctoring;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;
use OAT\Library\Lti1p3Proctoring\Model\AcsControlInterface;

class SendControlEvent implements EventInterface
{
    public const TYPE = 'proctoringSendControl';

    public function __construct(
        private string $registrationId,
        private AcsControlInterface $control,
        private string $acsUrl
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getControl(): AcsControlInterface
    {
        return $this->control;
    }

    public function getAcsUrl(): string
    {
        return $this->acsUrl;
    }
}
