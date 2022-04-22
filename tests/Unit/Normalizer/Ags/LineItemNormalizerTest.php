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
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\LineItemNormalizer;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItem;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemInterface;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReviewInterface;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class LineItemNormalizerTest extends TestCase
{
    private LineItemNormalizer $subject;
    private MockObject | NormalizerInterface $normalizerMock;
    private MockObject | DenormalizerInterface $denormalizerMock;

    protected function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);
        $this->denormalizerMock = $this->createMock(DenormalizerInterface::class);

        $this->subject = new LineItemNormalizer();
        $this->subject->setNormalizer($this->normalizerMock);
        $this->subject->setDenormalizer($this->denormalizerMock);
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization(new LineItem(10, 'Test Line Item')));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $startDate = new DateTimeImmutable();
        $endDate = new DateTimeImmutable('+2hours');
        $submissionReviewMock = $this->createMock(LineItemSubmissionReviewInterface::class);
        $additionalProps = [
            'prop1' => 'val1',
            'prop2' => 'val2',
        ];

        $lineItem = new LineItem(
            10.00,
            'Test Line Item',
            'id-tl1',
            'rid1',
            'rlid1',
            'tag1',
            $startDate,
            $endDate,
            $submissionReviewMock,
            $additionalProps
        );

        $this->normalizerMock->expects($this->exactly(4))
            ->method('normalize')
            ->withConsecutive(
                [$startDate],
                [$endDate],
                [$submissionReviewMock],
                [$lineItem->getAdditionalProperties()]
            )
            ->willReturnOnConsecutiveCalls(
                $startDate->format('Ymd H:i:s'),
                $endDate->format('Ymd H:i:s'),
                'submissionReviewMock',
                $additionalProps
            );

        $this->assertEquals(
            [
                'scoreMaximum' => $lineItem->getScoreMaximum(),
                'label' => $lineItem->getLabel(),
                'identifier' => $lineItem->getIdentifier(),
                'resourceIdentifier' => $lineItem->getResourceIdentifier(),
                'resourceLinkIdentifier' => $lineItem->getResourceLinkIdentifier(),
                'tag' => $lineItem->getTag(),
                'startDateTime' => $lineItem->getStartDateTime()->format('Ymd H:i:s'),
                'endDateTime' => $lineItem->getEndDateTime()->format('Ymd H:i:s'),
                'submissionReview' => 'submissionReviewMock',
                'additionalProperties' => [
                    'prop1' => 'val1',
                    'prop2' => 'val2',
                ],
            ],
            $this->subject->normalize($lineItem)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], LineItem::class));
        $this->assertTrue($this->subject->supportsDenormalization([], LineItemInterface::class));
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

        $this->assertNull($this->subject->denormalize($data, LineItem::class));
    }

    public function testDenormalizeForSuccess(): void
    {
        $startDate = new DateTimeImmutable();
        $endDate = new DateTimeImmutable('+2hours');
        $submissionReviewMock = $this->createMock(LineItemSubmissionReviewInterface::class);

        $rawData = [
            'scoreMaximum' => 10.00,
            'label' => 'Test Line Item',
            'identifier' => 'id-tl1',
            'resourceIdentifier' => 'rid1',
            'resourceLinkIdentifier' => 'rlid1',
            'tag' => 'tag1',
            'startDateTime' => $startDate->format('Ymd H:i:s'),
            'endDateTime' => $endDate->format('Ymd H:i:s'),
            'submissionReview' => 'submissionReviewMock',
            'additionalProperties' => [
                'prop1' => 'val1',
                'prop2' => 'val2',
            ],
        ];

        $this->denormalizerMock->expects($this->exactly(4))
            ->method('denormalize')
            ->withConsecutive(
                [$rawData['startDateTime']],
                [$rawData['endDateTime']],
                [$rawData['submissionReview']],
                [$rawData['additionalProperties']]
            )
            ->willReturnOnConsecutiveCalls(
                $startDate,
                $endDate,
                $submissionReviewMock,
                (new Collection())->add($rawData['additionalProperties'])
            );

        $lineItem = $this->subject->denormalize($rawData, LineItem::class);

        $this->assertSame($rawData['scoreMaximum'], $lineItem->getScoreMaximum());
        $this->assertSame($rawData['label'], $lineItem->getLabel());
        $this->assertSame($rawData['identifier'], $lineItem->getIdentifier());
        $this->assertSame($rawData['resourceIdentifier'], $lineItem->getResourceIdentifier());
        $this->assertSame($rawData['resourceLinkIdentifier'], $lineItem->getResourceLinkIdentifier());
        $this->assertSame($rawData['tag'], $lineItem->getTag());
        $this->assertSame($startDate, $lineItem->getStartDateTime());
        $this->assertSame($endDate, $lineItem->getEndDateTime());
        $this->assertSame($submissionReviewMock, $lineItem->getSubmissionReview());
        $this->assertSame($rawData['additionalProperties'], $lineItem->getAdditionalProperties()->all());
    }
}
