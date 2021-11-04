<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Proctoring;

use OAT\Library\Lti1p3Proctoring\Model\AcsControl;
use OAT\Library\Lti1p3Proctoring\Model\AcsControlInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AcsControlNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    private const PARAM_RESOURCE_LINK = 'resourceLink';
    private const PARAM_USER_IDENTIFIER = 'userIdentifier';
    private const PARAM_ACTION = 'action';
    private const PARAM_INCIDENT_TIME = 'incidentTime';
    private const PARAM_ATTEMPT_NUMBER = 'attemptNumber';
    private const PARAM_EXTRA_TIME = 'extraTime';
    private const PARAM_INCIDENT_SEVERITY = 'incidentSeverity';
    private const PARAM_REASON_CODE = 'reasonCode';
    private const PARAM_REASON_MESSAGE = 'reasonMessage';

    /**
     * @param AcsControlInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_RESOURCE_LINK => $this->normalizer->normalize($object->getResourceLink(), $format, $context),
            self::PARAM_USER_IDENTIFIER => $object->getUserIdentifier(),
            self::PARAM_ACTION => $object->getAction(),
            self::PARAM_INCIDENT_TIME => $object->getIncidentTime(),
            self::PARAM_ATTEMPT_NUMBER => $object->getAttemptNumber(),
            self::PARAM_EXTRA_TIME => $object->getExtraTime(),
            self::PARAM_INCIDENT_SEVERITY => $object->getIncidentSeverity(),
            self::PARAM_REASON_CODE => $object->getReasonCode(),
            self::PARAM_REASON_MESSAGE => $object->getReasonMessage(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof AcsControlInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): AcsControl
    {
        return new AcsControl(
            $this->denormalizer->denormalize($data[self::PARAM_RESOURCE_LINK], AcsControl::class, $format, $context),
            $data[self::PARAM_USER_IDENTIFIER],
            $data[self::PARAM_ACTION],
            $data[self::PARAM_INCIDENT_TIME],
            $data[self::PARAM_ATTEMPT_NUMBER],
            $data[self::PARAM_EXTRA_TIME],
            $data[self::PARAM_INCIDENT_SEVERITY],
            $data[self::PARAM_REASON_CODE],
            $data[self::PARAM_REASON_MESSAGE],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === AcsControl::class;
    }
}
