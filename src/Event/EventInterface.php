<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event;

use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\CreateLineItemEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\DeleteLineItemEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\GetLineItemEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\ListLineItemsEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\ListResultsEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\PublishScoreEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Ags\UpdateLineItemEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome\DeleteResultEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome\ReadResultEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome\ReplaceResultEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\BasicOutcome\SendBasicOutcomeEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Core\RequestEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Nrps\GetContextMembershipEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Nrps\GetResourceLinkMembershipEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Proctoring\SendControlEvent;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    CreateLineItemEvent::TYPE => CreateLineItemEvent::class,
    DeleteLineItemEvent::TYPE => DeleteLineItemEvent::class,
    GetLineItemEvent::TYPE => GetLineItemEvent::class,
    ListLineItemsEvent::TYPE => ListLineItemsEvent::class,
    ListResultsEvent::TYPE => ListResultsEvent::class,
    PublishScoreEvent::TYPE => PublishScoreEvent::class,
    UpdateLineItemEvent::TYPE => UpdateLineItemEvent::class,
    DeleteResultEvent::TYPE => DeleteResultEvent::class,
    ReadResultEvent::TYPE => ReadResultEvent::class,
    ReplaceResultEvent::TYPE => ReplaceResultEvent::class,
    SendBasicOutcomeEvent::TYPE => SendBasicOutcomeEvent::class,
    RequestEvent::TYPE => RequestEvent::class,
    GetContextMembershipEvent::TYPE => GetContextMembershipEvent::class,
    GetResourceLinkMembershipEvent::TYPE => GetResourceLinkMembershipEvent::class,
    SendControlEvent::TYPE => SendControlEvent::class,
])]
interface EventInterface
{
    public function getRegistrationId(): string;
}
