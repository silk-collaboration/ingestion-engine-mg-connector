<?php

namespace IngestionEngine\Connector\Model\Source\Filters;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Framework\Option\ArrayInterface;
use Psr\Log\LoggerInterface as Logger;
use IngestionEngine\Connector\Helper\Authenticator;

/**
 * Class Category
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\Source\Filters
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Category implements ArrayInterface
{
    /**
     * This variable is used for IngestionEngine Authenticator
     *
     * @var Authenticator $ingestionengineAuthenticator
     */
    protected $ingestionengineAuthenticator;
    /**
     * Description $logger field
     *
     * @var Logger $logger
     */
    protected $logger;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Category constructor
     *
     * @param Authenticator $ingestionengineAuthenticator
     * @param Logger        $logger
     * @param ConfigHelper  $configHelper
     */
    public function __construct(
        Authenticator $ingestionengineAuthenticator,
        Logger $logger,
        ConfigHelper $configHelper
    ) {
        $this->ingestionengineAuthenticator = $ingestionengineAuthenticator;
        $this->logger              = $logger;
        $this->configHelper        = $configHelper;
    }

    /**
     * Initialize options
     *
     * @return ResourceCursorInterface|array
     */
    public function getCategories()
    {
        /** @var array $categories */
        $categories = [];

        try {
            /** @var IngestionEnginePimClientInterface $client */
            $client = $this->ingestionengineAuthenticator->getIngestionEngineApiClient();
            if (empty($client)) {
                return $categories;
            }
            /** @var string|int $paginationSize */
            $paginationSize = $this->configHelper->getPaginationSize();
            /** @var ResourceCursorInterface $categories */
            $ingestionengineCategories = $client->getCategoryApi()->all($paginationSize);
            /** @var mixed[] $category */
            foreach ($ingestionengineCategories as $category) {
                if (!isset($category['code']) || isset($category['parent'])) {
                    continue;
                }
                $categories[$category['code']] = $category['code'];
            }
        } catch (\Exception $exception) {
            $this->logger->warning($exception->getMessage());
        }

        return $categories;
    }

    /**
     * Retrieve options value and label in an array
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @var array $categories */
        $categories = $this->getCategories();
        /** @var array $optionArray */
        $optionArray = [];

        /**
         * @var int    $optionValue
         * @var string $optionLabel
         */
        foreach ($categories as $optionValue => $optionLabel) {
            $optionArray[] = [
                'value' => $optionValue,
                'label' => $optionLabel,
            ];
        }

        return $optionArray;
    }
}
