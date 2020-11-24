<?php

declare(strict_types=1);

namespace IngestionEngine\Connector\Model\Source;

use IngestionEngine\Connector\Api\Data\ImportInterface;
use Magento\Framework\Option\ArrayInterface;

/**
 * Class Status
 *
 * @package   IngestionEngine\Connector\Model\Source
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2004-present Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Status implements ArrayInterface
{
    /**
     * Return array of options for the status filter
     *
     * @return array Format: array('<value>' => '<label>', ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __(' '),
                'value' => '',
            ],
            [
                'label' => __('Success'),
                'value' => ImportInterface::IMPORT_SUCCESS,
            ],
            [
                'label' => __('Error'),
                'value' => ImportInterface::IMPORT_ERROR,
            ],
            [
                'label' => __('Processing'),
                'value' => ImportInterface::IMPORT_PROCESSING,
            ],
        ];
    }
}
