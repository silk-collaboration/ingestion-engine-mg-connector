<?php

namespace IngestionEngine\Connector\Helper;

use IngestionEngine\Connector\Helper\Authenticator as Authenticator;

/**
 * Class Locales
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Helper
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Locales
{
    /**
     * This variable contains a Authenticator
     *
     * @var Authenticator $authenticator
     */
    protected $authenticator;

    /**
     * Locales constructor
     *
     * @param Authenticator $authenticator
     */
    public function __construct(
        Authenticator $authenticator
    ) {
        $this->authenticator = $authenticator;
    }

    /**
     * Get active IngestionEngine locales
     *
     * @return string[]
     * @throws IngestionEngine_Connector_Api_Exception
     */
    public function getIngestionEngineLocales()
    {
        /** @var IngestionEngine\Pim\ApiClient\IngestionEnginePimClientInterface $apiClient */
        $apiClient = $this->authenticator->getIngestionEngineApiClient();
        /** @var \IngestionEngine\Pim\ApiClient\Api\LocaleApiInterface $localeApi */
        $localeApi = $apiClient->getLocaleApi();
        /** @var IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface $locales */
        $locales = $localeApi->all(
            10,
            [
                'search' => [
                    'enabled' => [
                        [
                            'operator' => '=',
                            'value'    => true,
                        ],
                    ],
                ],
            ]
        );

        /** @var string[] $ingestionengineLocales */
        $ingestionengineLocales = [];
        /** @var mixed[] $locale */
        foreach ($locales as $locale) {
            if (empty($locale['code'])) {
                continue;
            }
            $ingestionengineLocales[] = $locale['code'];
        }

        return $ingestionengineLocales;
    }
}
