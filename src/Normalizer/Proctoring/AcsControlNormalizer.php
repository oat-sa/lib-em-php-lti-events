<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Proctoring;

use DateTimeImmutable;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;
use OAT\Library\Lti1p3Proctoring\Model\AcsControl;
use OAT\Library\Lti1p3Proctoring\Model\AcsControlInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
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
    private const PARAM_ISSUER_IDENTIFIER = 'issuerIdentifier';
    private const PARAM_EXTRA_TIME = 'extraTime';
    private const PARAM_INCIDENT_SEVERITY = 'incidentSeverity';
    private const PARAM_REASON_CODE = 'reasonCode';
    private const PARAM_REASON_MESSAGE = 'reasonMessage';

    /**
     * @param AcsControlInterface $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_RESOURCE_LINK => $this->normalizer->normalize($object->getResourceLink(), $format, $context),
            self::PARAM_USER_IDENTIFIER => $object->getUserIdentifier(),
            self::PARAM_ACTION => $object->getAction(),
            self::PARAM_INCIDENT_TIME => $this->normalizer->normalize($object->getIncidentTime(), $format, $context),
            self::PARAM_ATTEMPT_NUMBER => $object->getAttemptNumber(),
            self::PARAM_ISSUER_IDENTIFIER => $object->getIssuerIdentifier(),
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
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        return new AcsControl(
            $this->denormalizer->denormalize($data[self::PARAM_RESOURCE_LINK], LtiResourceLink::class, $format, $context),
            $data[self::PARAM_USER_IDENTIFIER],
            $data[self::PARAM_ACTION],
            $this->denormalizer->denormalize($data[self::PARAM_INCIDENT_TIME], DateTimeImmutable::class, $format, $context),
            $data[self::PARAM_ATTEMPT_NUMBER],
            $data[self::PARAM_ISSUER_IDENTIFIER] ?? null,
            $data[self::PARAM_EXTRA_TIME] ?? null,
            $data[self::PARAM_INCIDENT_SEVERITY] ?? null,
            $data[self::PARAM_REASON_CODE] ?? null,
            $data[self::PARAM_REASON_MESSAGE] ?? null,
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return in_array($type, [
            AcsControlInterface::class,
            AcsControl::class,
        ]);
    }
}
