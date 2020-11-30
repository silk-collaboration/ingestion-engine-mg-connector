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

        error_log( ">>> getting auth ..... .......");

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
        error_log( ">>> got auth ..... .......");
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
            error_log( ">> has auth token");
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
                    $total_count = $pagination->totalCount; //debug
                    $total_count = 2000;
                    $retries = 0;
                    $max_retries = 5;
                    while($offset < $total_count){
                        error_log( ">>> get all products begin ..... $offset .......");

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

                        error_log( ">>> get all products end ..... $offset .......");
                    }

                }


            }


        }
        return $products;
    }


    /**
     * Retrieve IngestionEngine products total
     *
     * @return getIngestionEngineProducts
     */
    public function getProductsTotal()
    {
        $total = 0;
        if ($this->authToken) {
            $this->getIngestionEngineClient();
        }
        if ($this->authToken) {
            /** @var string $baseUri */
            $baseUri = $this->configHelper->getIngestionEngineApiBaseUrl();
            $data_api = new RestClient([
                'base_url' => $baseUri,
                'headers' => ['authToken' => $this->authToken],
            ]);

            $result = $data_api->get("products/variants", ['limit' => 1]);
            if ($result->info->http_code == 200) {
                $resp = $result->decode_response();
                $total = $resp->data->pagination->totalCount;
            }
        }
        return $total;
    }
    /**
     * Retrieve IngestionEngine products
     *
     * @return getIngestionEngineProducts
     */
    public function getProductsPagination($offset=0)
    {
        error_log( ">>> get Pagination products begin ..... $offset .......");
        $resp = null;
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

            $page_limit = $this->configHelper->getPaginationSize();

            error_log( ">>> get Pagination products request start ..... $offset .......");
            $result = $data_api->get("products/variants",['limit' => $page_limit, 'offset' => $offset]);
            error_log( ">>> get Pagination products request finish ..... $offset .......");
            if($result->info->http_code == 200){
                $resp = $result->decode_response();
            }

        }
        error_log( ">>> get Pagination products end ..... $offset .......");
        return $resp;
    }

    /**
     * Retrieve IngestionEngine products from file
     *
     * @return getIngestionEngineProducts
     */
    public function getProductsFromFile()
    {
        error_log( ">>> get file products begin  .......");
        $ret = null;
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

            $page_limit = $this->configHelper->getPaginationSize();
            error_log( ">>> get file products request start .......");
            $result = $data_api->get("products/files", ['type' => 1 ]);
            error_log( ">>> get file products request finish ...");
            if($result->info->http_code == 200){
                $resp = $result->decode_response();
                $file_url = $resp->data->fileUrl;
                $file_url = '/Users/bruce/Downloads/all_products_032ea806-9b03-4164-8497-f5db7cb740ae.json'; // debug
                $ctx = stream_context_create(array('http'=>
                    array(
                        'timeout' => 2400,  //2400 Seconds is 40 Minutes
                    )
                ));

                error_log( ">>> get file products request finish .... $file_url ...");
                $json_str = file_get_contents($file_url, false, $ctx);
                $ret = json_decode($json_str, true);
            }

        }
        error_log( ">>> get file products end .......");
        return $ret;
    }

}
