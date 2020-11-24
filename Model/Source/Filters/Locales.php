<?php

namespace IngestionEngine\Connector\Model\Source\Filters;

use IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Framework\Option\ArrayInterface;
use IngestionEngine\Connector\Helper\Authenticator;

/**
 * Class Locales
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\Source\Filters
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Locales implements ArrayInterface
{
    /**
     * This variable contains a mixed value
     *
     * @var Authenticator $ingestionengineAuthenticator
     */
    protected $ingestionengineAuthenticator;

    /**
     * Family constructor
     *
     * @param Authenticator $ingestionengineAuthenticator
     */
    public function __construct(
        Authenticator $ingestionengineAuthenticator
    ) {
        $this->ingestionengineAuthenticator = $ingestionengineAuthenticator;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        /** @var ResourceCursorInterface $locales */
        $locales = $this->getLocales();
        /** @var array $options */
        $options = [];
        foreach ($locales as $locale) {
            $options[] = [
                'label' => $locale['code'],
                'value' => $locale['code'],
            ];
        }

        return $options;
    }

    /**
     * Retrieve the locales from ingestionengine using the configured API. If the credentials are not configured or are wrong, return an empty array
     *
     * @return ResourceCursorInterface|array
     */
    public function getLocales()
    {
        try {
            /** @var IngestionEnginePimClientInterface $ingestionengineClient */
            $ingestionengineClient = $this->ingestionengineAuthenticator->getIngestionEngineApiClient();

            if (!$ingestionengineClient) {
                return [];
            }

            return $ingestionengineClient->getLocaleApi()->all(10, [
                'search' => [
                    'enabled' => [
                        [
                            'operator' => '=',
                            'value' => true,
                        ]
                    ]
                ]
            ]);

        } catch (\Exception $exception) {
            return [];
        }
    }
}
