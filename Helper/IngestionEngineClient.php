<?php

namespace IngestionEngine\Connector\Helper;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use RestClient;

/**
 * Class IngestionEngineClient
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Helper
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class IngestionEngineClient
{
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;


    /**
     * This variable contains an authToken
     *
     * @var $authToken
     */
    protected $authToken;

    /**
     * This variable contains a merchartId
     *
     * @var $merchartId
     */
    protected $merchartId;

    /**
     * Authenticator constructor
     *
     * @param ConfigHelper $configHelper
     */
    public function __construct(
        ConfigHelper $configHelper
    ) {
        $this->configHelper = $configHelper;
        $this->getIngestionEngineClient();

    }

    /**
     * Retrieve an authenticated IngestionEngine php client
     *
     * @return getIngestionEngineClient|false
     */
    public function getIngestionEngineClient()
    {
        if($this->authToken) {
            return false;
        }
        /** @var string $baseUri */
        $baseUri = $this->configHelper->getIngestionEngineApiBaseUrl();
        /** @var string $username */
        $username = $this->configHelper->getIngestionEngineApiUsername();
        /** @var string $password */
        $password = $this->configHelper->getIngestionEngineApiPassword();



        if (!$baseUri || !$username || !$password) {
            return false;
        }

        $api = new RestClient([
            'base_url' => $baseUri,
        ]);
        $result = $api->post("sessions", json_encode(['email' => $username, 'password' => $password]));

        if($result->info->http_code == 200) {
            $token = '';
            $merchant_id = '';
            $resp = $result->decode_response();

            if (isset($resp->data->authToken)) {
                $token = $resp->data->authToken;
                $merchant_id = $resp->data->merchantList[0]->id;
            }
            if ($token && $merchant_id) {

                $result = $api->put("sessions", json_encode(['merchantId' => $merchant_id, 'authToken' => $token]));
                if ($result->info->http_code == 200) {
                    $resp = $result->decode_response();
                }
                if (isset($resp->data->authToken)) {
                    $this->authToken = $resp->data->authToken;
                    $this->merchartId = $merchant_id;
                }
            }
        }
    }

    /**
     * Retrieve IngestionEngine products
     *
     * @return getIngestionEngineProducts
     */
    public function getProducts($top_one=true)
    {
        $products = [];
        if($this->authToken) {
            $this->getIngestionEngineClient();
        }
        if($this->authToken)
        {
            /** @var string $baseUri */
            $baseUri = $this->configHelper->getIngestionEngineApiBaseUrl();
            $data_api = new RestClient([
                'base_url' => $baseUri,
                'headers' => ['authToken' => $this->authToken],
            ]);

            if($top_one){
                $result = $data_api->get("products/variants",['limit' => 1]);
                if($result->info->http_code == 200){
                    $resp = $result->decode_response();
                    $products = $resp->data->list;
                }
            }
            else {

                $data_api = new RestClient([
                    'base_url' => $baseUri,
                    'headers' => ['authToken' => $this->authToken],
                ]);

                $page_limit = $this->configHelper->getPaginationSize();
                $result = $data_api->get("products/variants",['limit' => $page_limit]);
                if($result->info->http_code == 200){
                    $resp = $result->decode_response();
                    $products = $resp->data->list;
                    $pagination = $resp->data->pagination;
                    $offset = $page_limit;
                    $total_count = $pagination->totalCount;
                    $retries = 0;
                    $max_retries = 5;
                    while($offset < $total_count){


                        $result = $data_api->get("products/variants",['limit' => $page_limit, 'offset' => $offset]);
                        if($result->info->http_code == 200){
                            $resp = $result->decode_response();
                            if(!$resp || !$resp->data){
                                if($max_retries <= $retries){
                                    $retries++;
                                    sleep(5);
                                    continue;
                                }else {
                                    break;
                                }

                            }
                            $products = array_merge($resp->data->list, $products);
                            $pagination = $resp->data->pagination;
                            $offset = $pagination->offset + $page_limit;
                        }else{
                            break;
                        }
                    }

                }


            }


        }
        return $products;
    }

}
