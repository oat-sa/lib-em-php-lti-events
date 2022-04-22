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

use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\SubmissionReviewNormalizer;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReview;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReviewInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

class SubmissionReviewNormalizerTest extends TestCase
{
    private SubmissionReviewNormalizer $subject;

    protected function setUp(): void
    {
        $this->subject = new SubmissionReviewNormalizer();
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization(new LineItemSubmissionReview(['Initialized'])));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $review = new LineItemSubmissionReview(['Initialized']);

        $this->assertEquals(
            [
                'reviewableStatuses' => $review->getReviewableStatuses(),
                'label' => $review->getLabel(),
                'url' => $review->getUrl(),
                'customProperties' => $review->getCustomProperties(),
            ],
            $this->subject->normalize($review)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], LineItemSubmissionReview::class));
        $this->assertTrue($this->subject->supportsDenormalization([], LineItemSubmissionReviewInterface::class));
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

        $this->assertNull($this->subject->denormalize($data, LineItemSubmissionReview::class));
    }


    public function testDenormalizeForSuccess(): void
    {
        $rawData = [
            'reviewableStatuses' => ['Initialized'],
            'label' => 'R',
            'url' => 'http://l.loc',
            'customProperties' => ['l'],
        ];

        $review = $this->subject->denormalize($rawData, LineItemSubmissionReview::class);

        $this->assertSame($rawData['reviewableStatuses'], $review->getReviewableStatuses());
        $this->assertSame($rawData['label'], $review->getLabel());
        $this->assertSame($rawData['url'], $review->getUrl());
        $this->assertSame($rawData['customProperties'], $review->getCustomProperties());
    }
}
