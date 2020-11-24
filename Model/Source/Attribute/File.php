<?php

namespace IngestionEngine\Connector\Model\Source\Attribute;

use IngestionEngine\Connector\Helper\Authenticator;
use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

/**
 * Class File
 *
 * @package   IngestionEngine\Connector\Model\Source\Attribute
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class File extends AbstractSource
{
    /**
     * This variable contains a mixed value
     *
     * @var Authenticator $ingestionengineAuthenticator
     */
    protected $ingestionengineAuthenticator;
    /**
     * This variable contains a IngestionEnginePimClientInterface
     *
     * @var IngestionEnginePimClientInterface $ingestionengineClient
     */
    protected $ingestionengineClient;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * File constructor
     *
     * @param Authenticator $ingestionengineAuthenticator
     * @param ConfigHelper  $configHelper
     */
    public function __construct(
        Authenticator $ingestionengineAuthenticator,
        ConfigHelper $configHelper
    ) {
        $this->ingestionengineAuthenticator = $ingestionengineAuthenticator;
        $this->configHelper        = $configHelper;
    }

    /**
     * Generate array of all file options from connected ingestionengine
     *
     * @return array
     */
    public function getAllOptions()
    {
        /** @var ResourceCursorInterface|mixed[] $attributes */
        $attributes = $this->getAttributes();

        if (!$attributes) {
            return $this->_options;
        }

        foreach ($attributes as $attribute) {
            if ($attribute['type'] != 'pim_catalog_file') {
                continue;
            }
            $this->_options[] = ['label' => $attribute['code'], 'value' => $attribute['code']];
        }

        return $this->_options;
    }

    /**
     * Generate cursor interface of pim file list
     *
     * @return ResourceCursorInterface|mixed[]
     */
    public function getAttributes()
    {
        try {
            /** @var IngestionEnginePimClientInterface $ingestionengineClient */
            $ingestionengineClient = $this->ingestionengineAuthenticator->getIngestionEngineApiClient();

            if (!$ingestionengineClient) {
                return [];
            }
            /** @var string|int $paginationSize */
            $paginationSize = $this->configHelper->getPaginationSize();

            return $ingestionengineClient->getAttributeApi()->all($paginationSize);
        } catch (\Exception $exception) {
            return [];
        }
    }
}
