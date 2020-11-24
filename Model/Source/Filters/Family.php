<?php

namespace IngestionEngine\Connector\Model\Source\Filters;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Framework\Option\ArrayInterface;
use Psr\Log\LoggerInterface as Logger;
use IngestionEngine\Connector\Helper\Authenticator;

/**
 * Class Family
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\Source\Filters
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Family implements ArrayInterface
{
    /**
     * This variable contains a mixed value
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
     * Family constructor
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
    public function getFamilies()
    {
        /** @var array $families */
        $families = [];

        try {
            /** @var IngestionEnginePimClientInterface $client */
            $client = $this->ingestionengineAuthenticator->getingestionengineApiClient();

            if (empty($client)) {
                return $families;
            }

            /** @var string|int $paginationSize */
            $paginationSize = $this->configHelper->getPaginationSize();
            /** @var ResourceCursorInterface $families */
            $ingestionengineFamilies = $client->getFamilyApi()->all($paginationSize);
            /** @var mixed[] $family */
            foreach ($ingestionengineFamilies as $family) {
                if (!isset($family['code'])) {
                    continue;
                }
                $families[$family['code']] = $family['code'];
            }
        } catch (\Exception $exception) {
            $this->logger->warning($exception->getMessage());
        }

        return $families;
    }

    /**
     * Retrieve options value and label in an array
     *
     * @return array
     */
    public function toOptionArray()
    {
        /** @var array $families */
        $families = $this->getFamilies();
        /** @var array $optionArray */
        $optionArray = [];
        /**
         * @var int    $optionValue
         * @var string $optionLabel
         */
        foreach ($families as $optionValue => $optionLabel) {
            $optionArray[] = [
                'value' => $optionValue,
                'label' => $optionLabel,
            ];
        }

        return $optionArray;
    }
}
