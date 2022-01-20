<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Event\Nrps;

use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;

class GetContextMembershipEvent implements EventInterface
{
    public const TYPE = 'nrpsGetContextMembership';

    public function __construct(
        private string $registrationId,
        private string $membershipServiceUrl,
        private ?string $role = null,
        private ?int $limit = null
    ) {}

    public function getRegistrationId(): string
    {
        return $this->registrationId;
    }

    public function getMembershipServiceUrl(): string
    {
        return $this->membershipServiceUrl;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }
}
