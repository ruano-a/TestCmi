<?php

namespace App\Service;

use App\Service\ApiSerializerInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiJsonSerializerService implements ApiSerializerInterface
{
    protected function serializeGroups($data, string|array $groups): array
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoder = new JsonEncoder();
        $defaultContext = [
                AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer], [$encoder]);
        $serializedData = $serializer->normalize($data, null, ['groups' => $groups]);
        return $serializedData;
    }

    public function serialize($data, string|array $groups = 'read'): array
    {
        return $this->serializeGroups($data, $groups);
    }
}
