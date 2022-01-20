<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\LineItemNormalizer;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\ScoreNormalizer;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Ags\SubmissionReviewNormalizer;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core\CollectionNormalizer;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Core\ResourceLinkNormalizer;
use OAT\Library\EnvironmentManagementLtiEvents\Normalizer\Proctoring\AcsControlNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class LtiSerializerFactory
{
    public static function create(): SerializerInterface
    {
        $classMetaDataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $classDiscriminator = new ClassDiscriminatorFromClassMetadata($classMetaDataFactory);
        return new Serializer(
            [
                new LineItemNormalizer(),
                new SubmissionReviewNormalizer(),
                new CollectionNormalizer(),
                new ScoreNormalizer(),
                new AcsControlNormalizer(),
                new ResourceLinkNormalizer(),
                new DateTimeNormalizer(),
                new ObjectNormalizer(
                    $classMetaDataFactory,
                    null,
                    null,
                    null,
                    $classDiscriminator
                ),
            ],
            [
                new JsonEncoder(),
            ],
        );
    }
}
