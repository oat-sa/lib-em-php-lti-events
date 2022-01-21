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
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core\ResourceLinkNormalizer;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLinkInterface;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ResourceLinkNormalizerTest extends TestCase
{
    private ResourceLinkNormalizer $subject;
    private MockObject | NormalizerInterface $normalizerMock;
    private MockObject | DenormalizerInterface $denormalizerMock;

    protected function setUp(): void
    {
        $this->normalizerMock = $this->createMock(NormalizerInterface::class);
        $this->denormalizerMock = $this->createMock(DenormalizerInterface::class);

        $this->subject = new ResourceLinkNormalizer();
        $this->subject->setNormalizer($this->normalizerMock);
        $this->subject->setDenormalizer($this->denormalizerMock);
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization(new  LtiResourceLink('l1', ['p1'])));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $link = new LtiResourceLink('l1', ['p1']);

        $this->normalizerMock->expects($this->once())
            ->method('normalize')
            ->with($link->getProperties())
            ->willReturn($link->getProperties()->all());

        $this->assertEquals(
            [
                'identifier' => $link->getIdentifier(),
                'properties' => $link->getProperties()->all(),
            ],
            $this->subject->normalize($link)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], LtiResourceLink::class));
        $this->assertTrue($this->subject->supportsDenormalization([], LtiResourceLinkInterface::class));
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

        $this->assertNull($this->subject->denormalize($data, LtiResourceLink::class));
    }

    public function testDenormalizeForSuccess(): void
    {
        $rawData = [
            'identifier' => 'id1',
            'properties' => ['l'],
        ];

        $this->denormalizerMock->expects($this->once())
            ->method('denormalize')
            ->with($rawData['properties'])
            ->willReturn((new Collection())->add($rawData['properties']));

        $link = $this->subject->denormalize($rawData, LtiResourceLink::class);

        $this->assertSame($rawData['identifier'], $link->getIdentifier());
        $this->assertSame($rawData['properties'], $link->getProperties()->all());
    }
}
