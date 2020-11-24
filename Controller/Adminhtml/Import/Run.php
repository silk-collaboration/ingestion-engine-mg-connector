<?php

namespace IngestionEngine\Connector\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use IngestionEngine\Connector\Api\ImportRepositoryInterface;
use IngestionEngine\Connector\Converter\ArrayToJsonResponseConverter;
use IngestionEngine\Connector\Helper\Output as OutputHelper;
use IngestionEngine\Connector\Job\Import;
use RestClient;

/**
 * Class Run
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Controller\Adminhtml\Import
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Run extends Action
{

    /**
     * This variable contains an OutputHelper
     *
     * @var OutputHelper $outputHelper
     */
    protected $outputHelper;
    /**
     * This variable contains an ImportRepositoryInterface
     *
     * @var ImportRepositoryInterface $importRepository
     */
    protected $importRepository;
    /**
     * This variable contains a ArrayToJsonResponseConverter
     *
     * @var ArrayToJsonResponseConverter $arrayToJsonResponseConverter
     */
    protected $arrayToJsonResponseConverter;

    /**
     * Run constructor.
     *
     * @param Context $context
     * @param ImportRepositoryInterface $importRepository
     * @param OutputHelper $output
     * @param ArrayToJsonResponseConverter $arrayToJsonResponseConverter
     */
    public function __construct(
        Context $context,
        ImportRepositoryInterface $importRepository,
        OutputHelper $output,
        ArrayToJsonResponseConverter $arrayToJsonResponseConverter
    ) {
        parent::__construct($context);

        $this->outputHelper                 = $output;
        $this->importRepository             = $importRepository;
        $this->arrayToJsonResponseConverter = $arrayToJsonResponseConverter;
    }

    /**
     * Action triggered by request
     *
     * @return Json
     */
    public function execute()
    {
        // TODO
//        $products = [];
//
//        $base_url = "https://ie.ingestionengine.net/api/v1/";
//        $api = new RestClient([
//            'base_url' => $base_url,
//        ]);
//        $result = $api->post("sessions", json_encode(['email' => "bruce.huang@ingestionengine.net", 'password' => '6BPo2Atc']));
//        if($result->info->http_code == 200){
//            $token = '';
//            $merchant_id = '';
//            $resp = $result->decode_response();
//
//            if(isset($resp->data->authToken)){
//                $token = $resp->data->authToken;
//                $merchant_id = $resp->data->merchantList[0]->id;
//            }
//            if($token && $merchant_id){
//
//
//                $result = $api->put("sessions", json_encode(['merchantId' => $merchant_id, 'authToken' => $token]));
//                if($result->info->http_code == 200){
//                    $resp = $result->decode_response();
//                }
//                $auth_token = '';
//                if(isset($resp->data->authToken)) {
//                    $auth_token = $resp->data->authToken;
//                }
//
//                if($auth_token)
//                {
//                    $data_api = new RestClient([
//                        'base_url' => $base_url,
//                        'headers' => ['authToken' => $auth_token],
//                    ]);
//
//                    $page_limit = 10;
//                    $result = $data_api->get("products/variants",['limit' => $page_limit]);
//                    if($result->info->http_code == 200){
//                        $resp = $result->decode_response();
//                        $products = $resp->data->list;
//                        $pagination = $resp->data->pagination;
//                        $offset = $page_limit;
//                        $total_count = $pagination->totalCount;
//                        while($offset < $total_count){
//
//
//                            $result = $data_api->get("products/variants",['limit' => $page_limit, 'offset' => $offset]);
//                            if($result->info->http_code == 200){
//                                $resp = $result->decode_response();
//                                $products = array_merge($resp->data->list, $products);
//                                $pagination = $resp->data->pagination;
//                                $offset = $pagination->offset + $page_limit;
//                            }else{
//                                break;
//                            }
//                        }
//
//                    }
//
//                }
//            }
//        }
//        var_dump($products); die;
        // TODO
//        die;
//        echo "test run \n";
        /** @var RequestInterface $request */
        $request = $this->getRequest();
        /** @var int $step */
        $step = (int)$request->getParam('step');
        /** @var string $code */
        $code = $request->getParam('code');
        /** @var string $identifier */
        $identifier = $request->getParam('identifier');
        /** @var string $family */
        $family = $request->getParam('family');
        /** @var Import $import */
        $import = $this->importRepository->getByCode($code);

        if (!$import) {
            /** @var array $response */
            $response = $this->outputHelper->getNoImportFoundResponse();

            return $this->arrayToJsonResponseConverter->convert($response);
        }

        $import->setIdentifier($identifier)->setStep($step)->setSetFromAdmin(true);

        if ($family) {
            $import->setFamily($family);
        }
        /** @var array $response */
        $response = $import->execute();

        return $this->arrayToJsonResponseConverter->convert($response);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('IngestionEngine_Connector::import');
    }
}
