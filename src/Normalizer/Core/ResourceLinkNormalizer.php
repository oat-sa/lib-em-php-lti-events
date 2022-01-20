<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core;

use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLink;
use OAT\Library\Lti1p3Core\Resource\LtiResourceLink\LtiResourceLinkInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ResourceLinkNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private const PARAM_IDENTIFIER = 'identifier';
    private const PARAM_PROPERTIES = 'properties';

    /**
     * @param LtiResourceLinkInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_IDENTIFIER => $object->getIdentifier(),
            self::PARAM_PROPERTIES => $object->getProperties()->all(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof LtiResourceLinkInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): LtiResourceLink
    {
        return new LtiResourceLink(
            $data[self::PARAM_IDENTIFIER],
            $data[self::PARAM_PROPERTIES] ?? [],
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === LtiResourceLink::class;
    }
}
