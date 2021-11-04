<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Ags;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;
use OAT\Library\Lti1p3Ags\Model\Score\ScoreInterface;

class PublishScoreEvent implements EventInterface
{
    public const TYPE = 'agsPublishScore';

    public function __construct(
        private string $registrationId,
        private ScoreInterface $score,
        private string $lineItemUrl
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getScore(): ScoreInterface
    {
        return $this->score;
    }

    public function getLineItemUrl(): string
    {
        return $this->lineItemUrl;
    }
}
