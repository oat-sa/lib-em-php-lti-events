<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core;

use OAT\Library\Lti1p3Core\Util\Collection\Collection;
use OAT\Library\Lti1p3Core\Util\Collection\CollectionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CollectionNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private const PARAM_ITEMS = 'items';

    /**
     * @param CollectionInterface $object
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            self::PARAM_ITEMS => $object->all(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof CollectionInterface;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): Collection
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf('Data expected to be an array, "%s" given.', get_debug_type($data)));
        }

        $collection = new Collection();

        $collection->add($data[self::PARAM_ITEMS] ?? []);

        return $collection;
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return is_a($type, CollectionInterface::class, true);
    }
}
