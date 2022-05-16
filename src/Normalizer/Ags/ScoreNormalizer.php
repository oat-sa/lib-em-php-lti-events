<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags;

use DateTimeImmutable;
use OAT\Library\Lti1p3Ags\Model\Score\Score;
use OAT\Library\Lti1p3Ags\Model\Score\ScoreInterface;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ScoreNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    private const PARAM_USER_IDENTIFIER = 'userId';
    private const PARAM_ACTIVITY_PROGRESS = 'activityProgress';
    private const PARAM_GRADING_PROGRESS = 'gradingProgress';
    private const PARAM_LINE_ITEM_IDENTIFIER = 'lineItemIdentifier';
    private const PARAM_SCORE_GIVEN = 'scoreGiven';
    private const PARAM_SCORE_MAXIMUM = 'scoreMaximum';
    private const PARAM_COMMENT = 'comment';
    private const PARAM_TIMESTAMP = 'timestamp';
    private const PARAM_ADDITIONAL_PROPERTIES = 'additionalProperties';

    /**
     * @param ScoreInterface $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_USER_IDENTIFIER => $object->getUserIdentifier(),
            self::PARAM_ACTIVITY_PROGRESS => $object->getActivityProgressStatus(),
            self::PARAM_GRADING_PROGRESS => $object->getGradingProgressStatus(),
            self::PARAM_LINE_ITEM_IDENTIFIER => $object->getLineItemIdentifier(),
            self::PARAM_SCORE_GIVEN => $object->getScoreGiven(),
            self::PARAM_SCORE_MAXIMUM => $object->getScoreMaximum(),
            self::PARAM_COMMENT => $object->getComment(),
            self::PARAM_TIMESTAMP => $this->normalizer->normalize($object->getTimestamp(), $format, $context),
            self::PARAM_ADDITIONAL_PROPERTIES => $this->normalizer->normalize($object->getAdditionalProperties(), $format, $context),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof ScoreInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): Score
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        return new Score(
            $data[self::PARAM_USER_IDENTIFIER],
            $data[self::PARAM_ACTIVITY_PROGRESS] ?? ScoreInterface::ACTIVITY_PROGRESS_STATUS_INITIALIZED,
            $data[self::PARAM_GRADING_PROGRESS] ?? ScoreInterface::GRADING_PROGRESS_STATUS_NOT_READY,
            $data[self::PARAM_LINE_ITEM_IDENTIFIER] ?? null,
            $data[self::PARAM_SCORE_GIVEN] ?? null,
            $data[self::PARAM_SCORE_MAXIMUM] ?? null,
            $data[self::PARAM_COMMENT] ?? null,
            ($data[self::PARAM_TIMESTAMP] ?? null) !== null ? $this->denormalizer->denormalize($data[self::PARAM_TIMESTAMP], DateTimeImmutable::class, $format, $context) : null,
            $this->denormalizer->denormalize($data[self::PARAM_ADDITIONAL_PROPERTIES] ?? [], Collection::class, $format, $context)?->all(),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, ScoreInterface::class, true);
    }
}
