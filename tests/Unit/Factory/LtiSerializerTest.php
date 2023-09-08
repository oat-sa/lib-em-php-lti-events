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
 * Copyright (c) 2022 (original work) Open Assessment Technologies SA;
 *
 * @author Sergei Mikhailov <sergei.mikhailov@taotesting.com>
 */

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Tests\Unit\Factory;

use DateTimeImmutable;
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
use OAT\Library\EnvironmentManagementLtiEvents\Event\EventInterface;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Nrps\GetContextMembershipEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Nrps\GetResourceLinkMembershipEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Event\Proctoring\SendControlEvent;
use OAT\Library\EnvironmentManagementLtiEvents\Factory\LtiSerializerFactory;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItem;
use OAT\Library\Lti1p3Ags\Model\Score\Score;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;
use OAT\Library\Lti1p3Proctoring\Model\AcsControl;
use OAT\Library\Lti1p3Proctoring\Model\AcsControlInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class LtiSerializerTest extends TestCase
{
    private SerializerInterface $sut;

    /**
     * @before
     */
    public function init(): void
    {
        $this->sut = LtiSerializerFactory::create();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testEventSerialization(EventInterface $event): void
    {
        $this->assertEquals(
            $event,
            $this->sut->deserialize(
                $this->sut->serialize($event, JsonEncoder::FORMAT),
                EventInterface::class,
                JsonEncoder::FORMAT
            )
        );
    }

    public function dataProvider(): array
    {
        return [
            [new CreateLineItemEvent('test', new LineItem(1, 'test'), 'https://example.com')],
            [new DeleteLineItemEvent('test', 'https://example.com')],
            [new GetLineItemEvent('test', 'https://example.com')],
            [new ListLineItemsEvent('test', 'https://example.com')],
            [new ListResultsEvent('test', 'https://example.com')],
            [new PublishScoreEvent('test', new Score('test'), 'https://example.com')],
            [new UpdateLineItemEvent('test', new LineItem(1, 'test'))],
            [new DeleteResultEvent('test', 'https://example.com', 'test')],
            [new ReadResultEvent('test', 'https://example.com', 'test')],
            [new ReplaceResultEvent('test', 'https://example.com', 'test', 1)],
            [new SendBasicOutcomeEvent('test', 'https://example.com', '<xml/>')],
            [new RequestEvent('test', 'GET', 'https://example.com')],
            [new GetContextMembershipEvent('test', 'https://example.com')],
            [new GetResourceLinkMembershipEvent('test', 'https://example.com', 'test')],
            [
                new SendControlEvent(
                    'test',
                    new AcsControl(
                        new LtiResourceLink('https://example.com'),
                        'test',
                        AcsControlInterface::ACTION_PAUSE,
                        DateTimeImmutable::createFromFormat('U.u', sprintf('%.3f', microtime(true)))
                    ),
                    'https://example.com'
                ),
            ],
        ];
    }
}
