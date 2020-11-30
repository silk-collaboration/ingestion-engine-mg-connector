<?php

namespace IngestionEngine\Connector\Job;

use IngestionEngine\Connector\Helper\ProductFilters;
use IngestionEngine\Connector\Model\Source\Attribute\Metrics as AttributeMetrics;
use IngestionEngine\Pim\ApiClient\Pagination\PageInterface;
use IngestionEngine\Pim\ApiClient\Pagination\ResourceCursorInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Eav\Model\Config;
use IngestionEngine\Connector\Helper\Authenticator;
use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use IngestionEngine\Connector\Helper\Import\Entities as EntitiesHelper;
use IngestionEngine\Connector\Helper\Output as OutputHelper;
use IngestionEngine\Connector\Job\Import;
use RestClient;

/**
 * Class ProductModel
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Job
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class ProductModel extends Import
{
    /**
     * This variable contains a string value
     *
     * @var string $code
     */
    protected $code = 'product_model';
    /**
     * This variable contains a string value
     *
     * @var string $name
     */
    protected $name = 'Product Model';
    /**
     * This variable contains product filters
     *
     * @var mixed[] $filters
     */
    protected $filters;
    /**
     * This variable contains an EntitiesHelper
     *
     * @var EntitiesHelper $entitiesHelper
     */
    protected $entitiesHelper;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;
    /**
     * This variable contains a Config
     *
     * @var Config $eavConfig
     */
    protected $eavConfig;
    /**
     * Description $attributeMetrics field
     *
     * @var AttributeMetrics $attributeMetrics
     */
    protected $attributeMetrics;
    /**
     * This variable contains a ProductFilters
     *
     * @var ProductFilters $productFilters
     */
    protected $productFilters;

    /**
     * ProductModel constructor
     *
     * @param OutputHelper                            $outputHelper
     * @param ManagerInterface                        $eventManager
     * @param Authenticator                           $authenticator
     * @param \IngestionEngine\Connector\Helper\Import\Product $entitiesHelper
     * @param ConfigHelper                            $configHelper
     * @param Config                                  $eavConfig
     * @param ProductFilters                          $productFilters
     * @param AttributeMetrics                        $attributeMetrics
     * @param array                                   $data
     */
    public function __construct(
        OutputHelper $outputHelper,
        ManagerInterface $eventManager,
        Authenticator $authenticator,
        \IngestionEngine\Connector\Helper\Import\Product $entitiesHelper,
        ConfigHelper $configHelper,
        Config $eavConfig,
        ProductFilters $productFilters,
        AttributeMetrics $attributeMetrics,
        array $data = []
    ) {
        parent::__construct($outputHelper, $eventManager, $authenticator, $data);

        $this->entitiesHelper   = $entitiesHelper;
        $this->configHelper     = $configHelper;
        $this->eavConfig        = $eavConfig;
        $this->productFilters   = $productFilters;
        $this->attributeMetrics = $attributeMetrics;
    }

    /**
     * Create temporary table
     *
     * @return void
     */
    public function createTable()
    {
        /** @var mixed[] $filters */
//        $products_json = "[{\"sku\": \"LIVS12HP115V1BH\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/GreeComfort_LIVS12HP115V1BH_Image1.jpg\", \"weight\": \"22.1 Pound\"}, {\"sku\": \"LIVS12HP115V1BO\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/GreeComfort_LIVS12HP115V1BO_Image1.jpg\", \"weight\": \"67.3 Pound\"}, {\"sku\": \"UPS15-58FC\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/Grundfos_UPS1558FC_Image1-REP.jpg\", \"type\": \"Closed System, 3-Speed, Small Stator, Canned Rotor\"}, {\"sku\": \"32001639-002/U\"}, {\"sku\": \"DR120A3000\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100214/Honeywell-ResideoTechnologies_DR120A3000U_Image1.jpg\"}, {\"sku\": \"F300E1001\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100199/Honeywell-ResideoTechnologies_F300E1001U_Image1.jpg\", \"type\": \"High Efficiency\"}, {\"sku\": \"T812A1010\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100199/Honeywell-ResideoTechnologies_T812A1010U_Image1.jpg\", \"type\": \"Non-Programmable, 1-Heat\", \"color\": \"Premier White\"}, {\"sku\": \"V110E1004\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100205/Honeywell-ResideoTechnologies_V110E1004U_Image1.jpg\", \"type\": \"Thermostatic, High Capacity\"}, {\"sku\": \"V8043B1019\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100237/Honeywell-ResideoTechnologies_V8043B1019U_Image1.jpg\", \"type\": \"Low Voltage, 2-Way, Compact, Straight-Through\"}, {\"sku\": \"YTH6320R1001\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100199/Honeywell-ResideoTechnologies_YTH6320R1001U_Image1.jpg\", \"type\": \"5-1-1 Day/5-2 Day Programmable, up to 3-Heat/2-Cool Heat Pump or up to 2-Heat/2-Cool Conventional\", \"color\": \"Premier White\"}, {\"sku\": \"ZD8X10TZ\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100199/Honeywell-ResideoTechnologies_ZD8X10TZU_Image1.jpg\", \"type\": \"Solid, Rectangular, Parallel Blade, Power Closed, Spring Open\"}, {\"sku\": \"1/2X5 NIP B\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100244/Mueller-B&KProducts_583050_Image1.jpg\"}, {\"sku\": \"UDAP100\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100190/NortekGlobalHVAC-Reznor_UDAP100_Image1.jpg\", \"type\": \"High Efficiency, Corrosion Resistant, Power Vented\"}, {\"sku\": \"6030A\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/RheemManufacturing_6030A_Image1.jpg\"}, {\"sku\": \"EGSP6\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/RheemManufacturing_EGSP6_Image1.jpg\", \"type\": \"Commercial, Electric\"}, {\"sku\": \"G100-200 NG\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/RheemManufacturing_G100200NG_Image1.jpg\", \"type\": \"Short, Heavy Duty\"}, {\"sku\": \"PROG40S-38N RH62\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100209/RheemManufacturing_PROG40S38NRH62_Image1.jpg\", \"type\": \"Short\"}, {\"sku\": \"R801SA050314ZSB\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100265/RheemManufacturing_R801SA050314ZSB_Image1.jpg\", \"type\": \"Zero Clearance Downflow, 1-Stage\"}, {\"sku\": \"RA1324BJ1NA\", \"image\": \"http://images.tradeservice.com/ProductImages/DIR100231/RheemManufacturing_RA1324BJ1NA_Image1.jpg\", \"type\": \"1-Stage, Non-Communicating\", \"weight\": \"142 Pound\"}, {\"sku\": \"RACA14036ACD000AA\", \"image\": \".\", \"type\": \"Non-Communicating\", \"weight\": \"411 Pound\"}]";
//        $products = json_decode($products_json);

        // TODO
        $products = [];
        
        $base_url = "https://ie.ingestionengine.net/api/v1/";
        $api = new RestClient([
            'base_url' => $base_url,
        ]);
        $result = $api->post("sessions", json_encode(['email' => "bruce.huang@ingestionengine.net", 'password' => '6BPo2Atc']));
        if($result->info->http_code == 200){
            $token = '';
            $merchant_id = '';
            $resp = $result->decode_response();

            if(isset($resp->data->authToken)){
                $token = $resp->data->authToken;
                $merchant_id = $resp->data->merchantList[0]->id;
            }
            if($token && $merchant_id){


                $result = $api->put("sessions", json_encode(['merchantId' => $merchant_id, 'authToken' => $token]));
                if($result->info->http_code == 200){
                    $resp = $result->decode_response();
                }
                $auth_token = '';
                if(isset($resp->data->authToken)) {
                    $auth_token = $resp->data->authToken;
                }

                if($auth_token)
                {
                    $data_api = new RestClient([
                        'base_url' => $base_url,
                        'headers' => ['authToken' => $auth_token],
                    ]);


                    $result = $data_api->get("products/variants");
                    if($result->info->http_code == 200){
                        $resp = $result->decode_response();
                        $products = $resp->data->list;
                    }

                }
            }
        }
        // TODO




        if (empty($products)) {
            $this->setMessage(__('No results from IngestionEngine for the family: %1', $this->getFamily()));
            $this->stop(true);

            return;
        }

        $productModel = (array) reset($products);

        $this->entitiesHelper->createTmpTableFromApi($productModel, $this->getCode());
    }

    /**
     * Insert data into temporary table
     *
     * @return void
     */
    public function insertData()
    {
        /** @var mixed[] $filters */
//        $filters = $this->getFilters();
//        /** @var string|int $paginationSize */
        $paginationSize = $this->configHelper->getPaginationSize();
//        /** @var int $index */
//        $index = 0;
//        /** @var string[] $attributeMetrics */
//        $attributeMetrics = $this->attributeMetrics->getMetricsAttributes();
//        /** @var mixed[] $metricsConcatSettings */
//        $metricsConcatSettings = $this->configHelper->getMetricsColumns(null, true);
//        /** @var string[] $metricSymbols */
//        $metricSymbols = $this->getMetricsSymbols();

        /** @var mixed[] $filter */
//        foreach ($filters as $filter) {
            /** @var ResourceCursorInterface $productModels */
            $productModels = $this->ingestionengineClient->getProductModelApi()->all($paginationSize, $filter);

            /**
             * @var int   $index
             * @var array $productModel
             */
            foreach ($productModels as $productModel) {
//                foreach ($attributeMetrics as $attributeMetric) {
//                    if (!isset($productModel['values'][$attributeMetric])) {
//                        continue;
//                    }
//
//                    foreach ($productModel['values'][$attributeMetric] as $key => $metric) {
//                        /** @var string|float $amount */
//                        $amount = $metric['data']['amount'];
//                        if ($amount != null) {
//                            $amount = floatval($amount);
//                        }
//
//                        $productModel['values'][$attributeMetric][$key]['data']['amount'] = $amount;
//                    }
//                }
//
//                /**
//                 * @var mixed[] $metricsConcatSetting
//                 */
//                foreach ($metricsConcatSettings as $metricsConcatSetting) {
//                    if (!isset($productModel['values'][$metricsConcatSetting])) {
//                        continue;
//                    }
//
//                    /**
//                     * @var int     $key
//                     * @var mixed[] $metric
//                     */
//                    foreach ($productModel['values'][$metricsConcatSetting] as $key => $metric) {
//                        /** @var string $unit */
//                        $unit = $metric['data']['unit'];
//                        /** @var string|false $symbol */
//                        $symbol = array_key_exists($unit, $metricSymbols);
//
//                        if (!$symbol) {
//                            continue;
//                        }
//
//                        $productModel['values'][$metricsConcatSetting][$key]['data']['amount'] .= ' ' . $metricSymbols[$unit];
//                    }
//                }
                // Set identifier to work with data insertion
                if (isset($productModel['code'])) {
                    $productModel['identifier'] = $productModel['code'];
                }
                $this->entitiesHelper->insertDataFromApi($productModel, $this->getCode());
                $index++;
            }
//        }

        if (empty($index)) {
            $this->setMessage('No Product data to insert in temp table');
            $this->stop(true);

            return;
        }

        $this->setMessage(
            __('%1 line(s) found', $index)
        );
    }

    /**
     * Generate array of metrics with unit in key and symbol for value
     *
     * @return string[]
     */
    public function getMetricsSymbols()
    {
        /** @var string|int $paginationSize */
        $paginationSize = $this->configHelper->getPaginationSize();
        /** @var mixed[] $measures */
        $measures = $this->ingestionengineClient->getMeasureFamilyApi()->all($paginationSize);
        /** @var string[] $metricsSymbols */
        $metricsSymbols = [];
        /** @var mixed[] $measure */
        foreach ($measures as $measure) {
            /** @var mixed[] $unit */
            foreach ($measure['units'] as $unit) {
                $metricsSymbols[$unit['code']] = $unit['symbol'];
            }
        }

        return $metricsSymbols;
    }

    /**
     * Remove columns from product model table
     *
     * @return void
     */
    public function removeColumns()
    {
        /** @var AdapterInterface $connection */
        $connection = $this->entitiesHelper->getConnection();
        /** @var array $except */
        $except = ['code', 'axis'];
        /** @var array $variantTable */
        $variantTable = $this->entitiesHelper->getTable('ingestionengine_connector_product_model');
        /** @var array $tmpTable */
        $tmpTable = $this->entitiesHelper->getTableName($this->getCode());
        /** @var array $columnsTmp */
        $columnsTmp = array_keys($connection->describeTable($tmpTable));
        /** @var array $columns */
        $columns = array_keys($connection->describeTable($variantTable));
        /** @var array $columnsToDelete */
        $columnsToDelete = array_diff($columns, $columnsTmp);

        /** @var string $column */
        foreach ($columnsToDelete as $column) {
            if (in_array($column, $except)) {
                continue;
            }
            $connection->dropColumn($variantTable, $column);
        }
    }

    /**
     * Add columns to product model table
     *
     * @return void
     */
    public function addColumns()
    {
        /** @var AdapterInterface $connection */
        $connection = $this->entitiesHelper->getConnection();
        /** @var array $tmpTable */
        $tmpTable = $this->entitiesHelper->getTableName($this->getCode());
        /** @var array $except */
        $except = ['code', 'axis', 'type', '_entity_id', '_is_new'];
        /** @var array $variantTable */
        $variantTable = $this->entitiesHelper->getTable('ingestionengine_connector_product_model');
        /** @var array $columnsTmp */
        $columnsTmp = array_keys($connection->describeTable($tmpTable));
        /** @var array $columns */
        $columns = array_keys($connection->describeTable($variantTable));
        /** @var array $columnsToAdd */
        $columnsToAdd = array_diff($columnsTmp, $columns);

        /** @var string $column */
        foreach ($columnsToAdd as $column) {
            if (in_array($column, $except)) {
                continue;
            }
            $connection->addColumn($variantTable, $this->_columnName($column), 'text');
        }
        if (!$connection->tableColumnExists($tmpTable, 'axis')) {
            $connection->addColumn($tmpTable, 'axis', [
                'type' => 'text',
                'length' => 255,
                'default' => '',
                'COMMENT' => ' '
            ]);
        }
    }

    /**
     * Add or update data in product model table
     *
     * @return void
     */
    public function updateData()
    {
        /** @var AdapterInterface $connection */
        $connection = $this->entitiesHelper->getConnection();
        /** @var int $updateLength */
        $batchSize = $this->configHelper->getAdvancedPmBatchSize();
        /** @var array $tmpTable */
        $tmpTable = $this->entitiesHelper->getTableName($this->getCode());
        /** @var array $variantTable */
        $variantTable = $this->entitiesHelper->getTable('ingestionengine_connector_product_model');
        /** @var array $variant */
        $variant = $connection->query(
            $connection->select()->from($tmpTable)
        );
        /** @var array $attributes */
        $attributes = $connection->fetchPairs(
            $connection->select()->from(
                $this->entitiesHelper->getTable('eav_attribute'),
                ['attribute_code', 'attribute_id']
            )->where('entity_type_id = ?', $this->getEntityTypeId())
        );
        /** @var array $columns */
        $columns = array_keys($connection->describeTable($tmpTable));
        /** @var array $values */
        $values = [];
        /** @var int $i */
        $i = 0;
        /** @var array $keys */
        $keys = [];
        while (($row = $variant->fetch())) {
            $values[$i] = [];
            /** @var int $column */
            foreach ($columns as $column) {
                if ($connection->tableColumnExists($variantTable, $this->_columnName($column))) {
                    if ($column != 'axis') {
                        $values[$i][$this->_columnName($column)] = $row[$column];
                    }
                    if ($column == 'axis' && !$connection->tableColumnExists($tmpTable, 'family_variant')) {
                        /** @var array $axisAttributes */
                        $axisAttributes = explode(',', $row['axis']);
                        /** @var array $axis */
                        $axis = [];
                        /** @var string $code */
                        foreach ($axisAttributes as $code) {
                            if (isset($attributes[$code])) {
                                $axis[] = $attributes[$code];
                            }
                        }
                        $values[$i][$column] = join(',', $axis);
                    }
                    $keys = array_keys($values[$i]);
                }
            }
            $i++;
            if (count($values) > $batchSize) {
                if (0 == $batchSize) {
                    $this->sliceInsertOnDuplicate($variantTable, $values);
                } else {
                    $connection->insertOnDuplicate($variantTable, $values, $keys);
                }
                $values = [];
                $i      = 0;
            }
        }
        if (count($values) > 0) {
            if (0 == $batchSize) {
                $this->sliceInsertOnDuplicate($variantTable, $values);
            } else {
                $connection->insertOnDuplicate($variantTable, $values, $keys);
            }
        }
    }

    /**
     * Drop temporary table
     *
     * @return void
     */
    public function dropTable()
    {
        $this->entitiesHelper->dropTable($this->getCode());
    }

    /**
     * Replace column name
     *
     * @param string $column
     *
     * @return string
     */
    protected function _columnName($column)
    {
        /** @var array $matches */
        $matches = [
            'label' => 'name',
        ];
        /**
         * @var string $name
         * @var string $replace
         */
        foreach ($matches as $name => $replace) {
            if (preg_match('/^' . $name . '/', $column)) {
                /** @var string $column */
                $column = preg_replace('/^' . $name . '/', $replace, $column);
            }
        }

        return $column;
    }

    /**
     * Get the product entity type id
     *
     * @return string
     */
    protected function getEntityTypeId()
    {
        /** @var string $productEntityTypeId */
        $productEntityTypeId = $this->eavConfig->getEntityType(ProductAttributeInterface::ENTITY_TYPE_CODE)
            ->getEntityTypeId();

        return $productEntityTypeId;
    }

    /**
     * Retrieve product filters
     *
     * @return mixed[]
     */
    protected function getFilters()
    {
        /** @var mixed[] $filters */
        $filters = $this->productFilters->getModelFilters();
        if (array_key_exists('error', $filters)) {
            $this->setMessage($filters['error']);
            $this->stop(true);
        }

        $this->filters = $filters;

        return $this->filters;
    }
}
