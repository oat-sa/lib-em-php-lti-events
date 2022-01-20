<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemInterface;

class CreateLineItemEvent implements EventInterface
{
    public const TYPE = 'agsCreateLineItem';

    public function __construct(
        private string $registrationId,
        private LineItemInterface $lineItem,
        private string $lineItemsContainerUrl,
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getLineItem(): LineItemInterface
    {
        return $this->lineItem;
    }

    public function getLineItemsContainerUrl(): string
    {
        return $this->lineItemsContainerUrl;
    }
}
