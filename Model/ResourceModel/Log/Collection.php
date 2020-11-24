<?php

namespace IngestionEngine\Connector\Model\ResourceModel\Log;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\ResourceModel\Log
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Collection extends AbstractCollection
{
    /**
     * This variable contains a string value
     *
     * @var string $_idFieldName
     */
    protected $_idFieldName = 'log_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\IngestionEngine\Connector\Model\Log::class, \IngestionEngine\Connector\Model\ResourceModel\Log::class);
    }
}
