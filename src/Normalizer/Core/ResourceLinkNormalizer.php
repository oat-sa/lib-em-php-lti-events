<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core;

use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLinkInterface;
use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ResourceLinkNormalizer implements NormalizerInterface, DenormalizerInterface, NormalizerAwareInterface, DenormalizerAwareInterface
{
    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    private const PARAM_IDENTIFIER = 'identifier';
    private const PARAM_PROPERTIES = 'properties';

    /**
     * @param LtiResourceLinkInterface $object
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_IDENTIFIER => $object->getIdentifier(),
            self::PARAM_PROPERTIES => $this->normalizer->normalize($object->getProperties(), $format, $context),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof LtiResourceLinkInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): LtiResourceLink
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        return new LtiResourceLink(
            $data[self::PARAM_IDENTIFIER],
            $this->denormalizer->denormalize($data[self::PARAM_PROPERTIES] ?? [], Collection::class, $format, $context)->all(),
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, LtiResourceLinkInterface::class, true);
    }
}
