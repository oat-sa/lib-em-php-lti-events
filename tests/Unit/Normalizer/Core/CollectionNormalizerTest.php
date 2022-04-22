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

namespace OAT\Library\EnvironmentManagementLtiEvents\Tests\Unit\Normalizer\Core;

use JetBrains\PhpStorm\Pure;
use JsonSerializable;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core\CollectionNormalizer;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use OAT\Library\Lti1p3Core\Util\Collection\CollectionInterface;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;

final class CollectionNormalizerTest extends TestCase
{
    private CollectionNormalizer $subject;

    protected function setUp(): void
    {
        $this->subject = new CollectionNormalizer();
    }

    public function testSupportsNormalization(): void
    {
        $this->assertTrue($this->subject->supportsNormalization(new Collection()));
        $this->assertFalse($this->subject->supportsNormalization('fake data'));
    }

    public function testNormalize(): void
    {
        $collection = new Collection();

        $collection->set('k1', 'v1');
        $collection->set('k2', 'v2');

        $this->assertEquals(
            [
                'items' => $collection->all(),
            ],
            $this->subject->normalize($collection)
        );
    }

    public function testSupportsDenormalization(): void
    {
        $this->assertTrue($this->subject->supportsDenormalization([], Collection::class));
        $this->assertTrue($this->subject->supportsDenormalization([], CollectionInterface::class));
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

        $this->assertNull($this->subject->denormalize($data, Collection::class));
    }


    public function testDenormalizeForSuccess(): void
    {
        $rawData = [
            'items' => [
                'k1' => 'v1',
            ],
        ];

        $collection = $this->subject->denormalize($rawData, Collection::class);

        $this->assertSame('v1', $collection->get('k1'));
    }
}
