<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA;
 */

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Tests\Unit\Normalizer\Proctoring;

use DateTimeImmutable;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Proctoring\AcsControlNormalizer;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLinkInterface;
use OAT\Library\Lti1p3Proctoring\Model\AcsControl;
use OAT\Library\Lti1p3Proctoring\Model\AcsControlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class AcsControlNormalizerTest extends TestCase
{
    private AcsControlNormalizer $subject;
    private MockObject | NormalizerInterface $normalizerMock;
    private MockObject | DenormalizerInterface $denormalizerMock;

    protected function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);
        $this->denormalizerMock = $this->createMock(DenormalizerInterface::class);

        $this->subject = new AcsControlNormalizer();
        $this->subject->setNormalizer($this->normalizerMock);
        $this->subject->setDenormalizer($this->denormalizerMock);
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization($this->createMock(AcsControlInterface::class)));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $incidentTime = new DateTimeImmutable();
        $linkMock = $this->createMock(LtiResourceLinkInterface::class);

        $acs = new AcsControl($linkMock, 'u1', 'pause', $incidentTime);

        $this->normalizerMock->expects($this->exactly(2))
            ->method('normalize')
            ->withConsecutive(
                [$linkMock],
                [$incidentTime],
            )
            ->willReturnOnConsecutiveCalls(
                'link-normalized',
                $incidentTime->format('Ymd H:i:s'),
            );

        $this->assertEquals(
            [
                'resourceLink' => 'link-normalized',
                'userIdentifier' => $acs->getUserIdentifier(),
                'action' => $acs->getAction(),
                'incidentTime' => $acs->getIncidentTime()->format('Ymd H:i:s'),
                'attemptNumber' => $acs->getAttemptNumber(),
                'issuerIdentifier' => $acs->getIssuerIdentifier(),
                'extraTime' => $acs->getExtraTime(),
                'incidentSeverity' => $acs->getIncidentSeverity(),
                'reasonCode' => $acs->getReasonCode(),
                'reasonMessage' => $acs->getReasonMessage(),
            ],
            $this->subject->normalize($acs)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], AcsControl::class));
        $this->assertTrue($this->subject->supportsDenormalization([], AcsControlInterface::class));
        $this->assertFalse($this->subject->supportsDenormalization([], JsonSerializable::class));
        $this->assertFalse($this->subject->supportsDenormalization([], stdClass::class));
    }

    #[Pure]
    public function invalidDataForDenormalization(): array
    {
        return [
            [
                null,
                'Data expected to be an array, "null" given.'
            ],
            [
                'fake data',
                'Data expected to be an array, "string" given.'
            ],
            [
                new stdClass(),
                'Data expected to be an array, "stdClass" given.'
            ],
        ];
    }

    /**
     * @dataProvider invalidDataForDenormalization
     */
    public function testDenormalizeWithInvalidData($data, string $errMsg): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($errMsg);

        $this->assertNull($this->subject->denormalize($data, AcsControl::class));
    }

    public function testDenormalizeForSuccess(): void
    {
        $incidentTime = new DateTimeImmutable();
        $linkMock = $this->createMock(LtiResourceLinkInterface::class);

        $rawData = [
            'resourceLink' => 'link-normalized',
            'userIdentifier' => 'u1',
            'action' => 'pause',
            'incidentTime' => $incidentTime->format('Ymd H:i:s'),
            'attemptNumber' => 2,
            'issuerIdentifier' => 'issue-1',
            'extraTime' => 33,
            'incidentSeverity' => 44.1,
            'reasonCode' => 'r1',
            'reasonMessage' => 'rm',
        ];

        $this->denormalizerMock->expects($this->exactly(2))
            ->method('denormalize')
            ->withConsecutive(
                [$rawData['resourceLink']],
                [$rawData['incidentTime']],
            )
            ->willReturnOnConsecutiveCalls(
                $linkMock,
                $incidentTime,
            );

        $acs = $this->subject->denormalize($rawData, AcsControl::class);

        $this->assertSame($linkMock, $acs->getResourceLink());
        $this->assertSame($rawData['userIdentifier'], $acs->getUserIdentifier());
        $this->assertSame($rawData['action'], $acs->getAction());
        $this->assertSame($incidentTime, $acs->getIncidentTime());
        $this->assertSame($rawData['attemptNumber'], $acs->getAttemptNumber());
        $this->assertSame($rawData['issuerIdentifier'], $acs->getIssuerIdentifier());
        $this->assertSame($rawData['extraTime'], $acs->getExtraTime());
        $this->assertSame($rawData['incidentSeverity'], $acs->getIncidentSeverity());
        $this->assertSame($rawData['reasonCode'], $acs->getReasonCode());
        $this->assertSame($rawData['reasonMessage'], $acs->getReasonMessage());

    }
}
