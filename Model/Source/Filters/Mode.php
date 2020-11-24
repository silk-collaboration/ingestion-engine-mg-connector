<?php

namespace IngestionEngine\Connector\Model\Source\Filters;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Mode
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Model\Source\Filters
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Mode implements ArrayInterface
{
    /** const keys */
    const STANDARD = 'standard';
    const ADVANCED = 'advanced';

    /**
     * Return array of options for the filter mode
     *
     * @return array Format: array('<value>' => '<label>', ...)
     */
    public function toOptionArray()
    {
        return [
            self::STANDARD => __('Standard'),
            self::ADVANCED => __('Advanced'),
        ];
    }
}
