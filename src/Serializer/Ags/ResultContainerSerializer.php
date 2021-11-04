<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Serializer\Ags;

use OAT\Library\Lti1p3Ags\Model\Result\ResultContainer;
use OAT\Library\Lti1p3Ags\Model\Result\ResultContainerInterface;
use OAT\Library\Lti1p3Ags\Serializer\Result\ResultCollectionSerializerInterface;
use OAT\Library\Lti1p3Core\Exception\LtiException;

class ResultContainerSerializer
{
    public function __construct(
        private ResultCollectionSerializerInterface $resultCollectionSerializer,
    ) {}

    public function serialize(ResultContainerInterface $resultContainer): string
    {
        return json_encode($resultContainer);
    }

    /**
     * @throws LtiException
     */
    public function deserialize(string $data): ResultContainerInterface
    {
        $data = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new LtiException(
                sprintf('Error during result container deserialization: %s', json_last_error_msg())
            );
        }

        return new ResultContainer(
            $this->resultCollectionSerializer->deserialize($data['results']),
            $data['relationLink'] ?? null,
        );
    }
}
