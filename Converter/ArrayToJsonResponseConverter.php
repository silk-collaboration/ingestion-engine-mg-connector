<?php

namespace IngestionEngine\Connector\Converter;

use Magento\Framework\Controller\Result\Json as ResultJson;
use Magento\Framework\Controller\Result\JsonFactory as ResultJsonFactory;

/**
 * Class ArrayToJsonResponseConverter
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Converter
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class ArrayToJsonResponseConverter
{

    /**
     * This variable contains a ResultJsonFactory
     *
     * @var ResultJsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * ArrayToJsonResponseConverter constructor.
     *
     * @param ResultJsonFactory $resultJsonFactory
     */
    public function __construct(
        ResultJsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * This function convert an array to json
     *
     * @param array $data
     *
     * @return ResultJson
     */
    public function convert(array $data)
    {
        /** @var ResultJson $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        return $resultJson->setData($data);
    }
}
