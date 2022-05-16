<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags;

use DateTimeImmutable;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItem;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemInterface;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReview;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class LineItemNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    private const PARAM_SCORE_MAXIMUM = 'scoreMaximum';
    private const PARAM_LABEL = 'label';
    private const PARAM_IDENTIFIER = 'identifier';
    private const PARAM_RESOURCE_IDENTIFIER = 'resourceIdentifier';
    private const PARAM_RESOURCE_LINK_IDENTIFIER = 'resourceLinkIdentifier';
    private const PARAM_TAG = 'tag';
    private const PARAM_START_DATE_TIME = 'startDateTime';
    private const PARAM_END_DATE_TIME = 'endDateTime';
    private const PARAM_SUBMISSION_REVIEW = 'submissionReview';
    private const PARAM_ADDITIONAL_PROPERTIES = 'additionalProperties';

    /**
     * @param LineItemInterface $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_SCORE_MAXIMUM => $object->getScoreMaximum(),
            self::PARAM_LABEL => $object->getLabel(),
            self::PARAM_IDENTIFIER => $object->getIdentifier(),
            self::PARAM_RESOURCE_IDENTIFIER => $object->getResourceIdentifier(),
            self::PARAM_RESOURCE_LINK_IDENTIFIER => $object->getResourceLinkIdentifier(),
            self::PARAM_TAG => $object->getTag(),
            self::PARAM_START_DATE_TIME => $this->normalizer->normalize($object->getStartDateTime(), $format, $context),
            self::PARAM_END_DATE_TIME => $this->normalizer->normalize($object->getEndDateTime(), $format, $context),
            self::PARAM_SUBMISSION_REVIEW => $this->normalizer->normalize($object->getSubmissionReview(), $format, $context),
            self::PARAM_ADDITIONAL_PROPERTIES => $this->normalizer->normalize($object->getAdditionalProperties(), $format, $context),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof LineItemInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): LineItemInterface
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        return new LineItem(
            $data[self::PARAM_SCORE_MAXIMUM],
            $data[self::PARAM_LABEL],
            $data[self::PARAM_IDENTIFIER] ?? null,
            $data[self::PARAM_RESOURCE_IDENTIFIER] ?? null,
            $data[self::PARAM_RESOURCE_LINK_IDENTIFIER] ?? null,
            $data[self::PARAM_TAG] ?? null,
            ($data[self::PARAM_START_DATE_TIME] ?? null) !== null ? $this->denormalizer->denormalize($data[self::PARAM_START_DATE_TIME], DateTimeImmutable::class, $format, $context) : null,
            ($data[self::PARAM_END_DATE_TIME] ?? null) !== null ? $this->denormalizer->denormalize($data[self::PARAM_END_DATE_TIME], DateTimeImmutable::class, $format, $context) : null,
            ($data[self::PARAM_SUBMISSION_REVIEW] ?? null) !== null ? $this->denormalizer->denormalize($data[self::PARAM_SUBMISSION_REVIEW], LineItemSubmissionReview::class, $format, $context) : null,
            $this->denormalizer->denormalize($data[self::PARAM_ADDITIONAL_PROPERTIES] ?? [], Collection::class, $format, $context)->all(),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, LineItemInterface::class, true);
    }
}
