<?php

namespace IngestionEngine\Connector\Model\Source\Filters;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Framework\Option\ArrayInterface;
use IngestionEngine\Connector\Helper\Authenticator;

/**
 * Class Channel
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\Source\Filters
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Channel implements ArrayInterface
{
    /**
     * This variable contains a mixed value
     *
     * @var Authenticator $ingestionengineAuthenticator
     */
    protected $ingestionengineAuthenticator;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Family constructor
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
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        /** @var ResourceCursorInterface $channels */
        $channels = $this->getChannels();
        /** @var array $options */
        $options = [];
        foreach ($channels as $channel) {
            $options[] = [
                'label' => $channel['code'],
                'value' => $channel['code'],
            ];
        }

        return $options;
    }

    /**
     * Retrieve the channels from ingestionengine using the configured API. If the credentials are not configured or are wrong, return an empty array
     *
     * @return ResourceCursorInterface|array
     */
    public function getChannels()
    {
        try {
            /** @var IngestionEnginePimClientInterface $ingestionengineClient */
            $ingestionengineClient = $this->ingestionengineAuthenticator->getIngestionEngineApiClient();

            if (!$ingestionengineClient) {
                return [];
            }
            /** @var string|int $paginationSize */
            $paginationSize = $this->configHelper->getPaginationSize();

            return $ingestionengineClient->getChannelApi()->all($paginationSize);
        } catch (\Exception $exception) {
            return [];
        }
    }
}
