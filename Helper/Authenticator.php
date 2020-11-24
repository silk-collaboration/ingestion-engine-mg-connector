<?php

namespace IngestionEngine\Connector\Helper;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use Http\Adapter\Guzzle6\Client;
use Http\Message\StreamFactory\GuzzleStreamFactory;
use Http\Message\MessageFactory\GuzzleMessageFactory;

/**
 * Class Authenticator
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Helper
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Authenticator
{
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Authenticator constructor
     *
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        ConfigHelper $configHelper
    ) {
        $this->configHelper = $configHelper;
    }

    /**
     * Retrieve an authenticated IngestionEngine php client
     *
     * @return IngestionEnginePimClientInterface|false
     */
    public function getIngestionEngineApiClient()
    {
        /** @var string $baseUri */
        $baseUri = $this->configHelper->getIngestionEngineApiBaseUrl();
        /** @var string $clientId */
        $clientId = $this->configHelper->getIngestionEngineApiClientId();
        /** @var string $secret */
        $secret = $this->configHelper->getIngestionEngineApiClientSecret();
        /** @var string $username */
        $username = $this->configHelper->getIngestionEngineApiUsername();
        /** @var string $password */
        $password = $this->configHelper->getIngestionEngineApiPassword();

        if (!$baseUri || !$clientId || !$secret || !$username || !$password) {
            return false;
        }

        /** @var IngestionEnginePimClientBuilder $ingestionengineClientBuilder */
//        $ingestionengineClientBuilder = new IngestionEnginePimClientBuilder($baseUri);
//
//        $ingestionengineClientBuilder->setHttpClient(new Client());
//        $ingestionengineClientBuilder->setStreamFactory(new GuzzleStreamFactory());
//        $ingestionengineClientBuilder->setRequestFactory(new GuzzleMessageFactory());
        return false;

//        return $ingestionengineClientBuilder->buildAuthenticatedByPassword($clientId, $secret, $username, $password);
    }
}
