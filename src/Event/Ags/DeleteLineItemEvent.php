<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class DeleteLineItemEvent implements EventInterface
{
    public const TYPE = 'agsDeleteLineItem';

    public function __construct(
        private string $registrationId,
        private string $lineItemUrl,
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLineItemUrl(): string
    {
        return $this->lineItemUrl;
    }
}
