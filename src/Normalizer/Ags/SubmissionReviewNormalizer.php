<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags;

use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReview;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemSubmissionReviewInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class SubmissionReviewNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private const PARAM_REVIEWABLE_STATUSES = 'reviewableStatuses';
    private const PARAM_LABEL = 'label';
    private const PARAM_URL = 'url';
    private const PARAM_CUSTOM_PROPERTIES = 'customProperties';

    /**
     * @param LineItemSubmissionReviewInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_REVIEWABLE_STATUSES => $object->getReviewableStatuses(),
            self::PARAM_LABEL => $object->getLabel(),
            self::PARAM_URL => $object->getUrl(),
            self::PARAM_CUSTOM_PROPERTIES => $object->getCustomProperties(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof LineItemSubmissionReviewInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): LineItemSubmissionReview
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        return new LineItemSubmissionReview(
            $data[self::PARAM_REVIEWABLE_STATUSES] ?? [],
            $data[self::PARAM_LABEL] ?? null,
            $data[self::PARAM_URL] ?? null,
            $data[self::PARAM_CUSTOM_PROPERTIES] ?? [],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, LineItemSubmissionReviewInterface::class, true);
    }
}
