<?php

declare(strict_types=1);

namespace OAT\Library\EnvironmentManagementLtiEvents\Serializer\Ags;

use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemContainer;
use OAT\Library\Lti1p3Ags\Model\LineItem\LineItemContainerInterface;
use OAT\Library\Lti1p3Ags\Serializer\LineItem\LineItemCollectionSerializerInterface;
use OAT\Library\Lti1p3Core\Exception\LtiException;

class LineItemContainerSerializer
{
    public function __construct(
        private LineItemCollectionSerializerInterface $lineItemCollectionSerializer,
    ) {}

    public function serialize(LineItemContainerInterface $lineItemContainer): string
    {
        return json_encode($lineItemContainer);
    }

    /**
     * @throws LtiException
     */
    public function deserialize(string $data): LineItemContainerInterface
    {
        $data = json_decode($data, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new LtiException(
                sprintf('Error during line item container deserialization: %s', json_last_error_msg())
            );
        }

        return new LineItemContainer(
            $this->lineItemCollectionSerializer->deserialize($data['lineItems']),
            $data['relationLink'] ?? null,
        );
    }
}
