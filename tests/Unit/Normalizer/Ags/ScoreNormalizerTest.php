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

namespace OAT\Library\EnvironmentManagementLtiEvents\Tests\Unit\Normalizer\Ags;

use DateTimeImmutable;
use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\ScoreNormalizer;
use OAT\Library\Lti1p3Ags\Model\Score\Score;
use OAT\Library\Lti1p3Ags\Model\Score\ScoreInterface;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ScoreNormalizerTest extends TestCase
{
    private ScoreNormalizer $subject;
    private MockObject | NormalizerInterface $normalizerMock;
    private MockObject | DenormalizerInterface $denormalizerMock;

    protected function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);
        $this->denormalizerMock = $this->createMock(DenormalizerInterface::class);

        $this->subject = new ScoreNormalizer();
        $this->subject->setNormalizer($this->normalizerMock);
        $this->subject->setDenormalizer($this->denormalizerMock);
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization(new Score('u1')));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $score = new Score('u1');

        $this->normalizerMock->expects($this->exactly(2))
            ->method('normalize')
            ->withConsecutive(
                [$score->getTimestamp()],
                [$score->getAdditionalProperties()]
            )
            ->willReturnOnConsecutiveCalls(
                $score->getTimestamp()->format('Ymd H:i:s'),
                []
            );

        $this->assertEquals(
            [
                'userId' => $score->getUserIdentifier(),
                'activityProgress' => $score->getActivityProgressStatus(),
                'gradingProgress' => $score->getGradingProgressStatus(),
                'lineItemIdentifier' => $score->getLineItemIdentifier(),
                'scoreGiven' => $score->getScoreGiven(),
                'scoreMaximum' => $score->getScoreMaximum(),
                'comment' => $score->getComment(),
                'timestamp' => $score->getTimestamp()->format('Ymd H:i:s'),
                'additionalProperties' => $score->getAdditionalProperties()->all(),
            ],
            $this->subject->normalize($score)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], Score::class));
        $this->assertTrue($this->subject->supportsDenormalization([], ScoreInterface::class));
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

        $this->assertNull($this->subject->denormalize($data, Score::class));
    }

    public function testDenormalizeForSuccess(): void
    {
        $timestamp = new DateTimeImmutable();

        $rawData = [
            'userId' => 'u1',
            'activityProgress' => 'Initialized',
            'gradingProgress' => 'Pending',
            'lineItemIdentifier' => 'l',
            'scoreGiven' => 4.00,
            'scoreMaximum' => 5.00,
            'comment' => 'c',
            'timestamp' => $timestamp->format('Ymd H:i:s'),
            'additionalProperties' => ['k1' => 'v1'],
        ];

        $this->denormalizerMock->expects($this->exactly(2))
            ->method('denormalize')
            ->withConsecutive(
                [$rawData['timestamp']],
                [$rawData['additionalProperties']]
            )
            ->willReturnOnConsecutiveCalls(
                $timestamp,
                (new Collection())->add($rawData['additionalProperties'])
            );


        $score = $this->subject->denormalize($rawData, Score::class);

        $this->assertSame($rawData['userId'], $score->getUserIdentifier());
        $this->assertSame($rawData['activityProgress'], $score->getActivityProgressStatus());
        $this->assertSame($rawData['gradingProgress'], $score->getGradingProgressStatus());
        $this->assertSame($rawData['lineItemIdentifier'], $score->getLineItemIdentifier());
        $this->assertSame($rawData['scoreGiven'], $score->getScoreGiven());
        $this->assertSame($rawData['scoreMaximum'], $score->getScoreMaximum());
        $this->assertSame($rawData['comment'], $score->getComment());
        $this->assertSame($timestamp, $score->getTimestamp());
        $this->assertSame($rawData['additionalProperties'], $score->getAdditionalProperties()->all());
    }
}
