<?php

namespace IngestionEngine\Connector\Api\Data;

/**
 * Interface ImportInterface
 *
 * @category  Interface
 * @package   IngestionEngine\Connector\Api\Data
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
interface ImportInterface
{
    /**
     * @var int IMPORT_SUCCESS
     */
    const IMPORT_SUCCESS = 1;
    /**
     * @var int IMPORT_ERROR
     */
    const IMPORT_ERROR = 2;
    /**
     * @var int IMPORT_PROCESSING
     */
    const IMPORT_PROCESSING = 3;
}
