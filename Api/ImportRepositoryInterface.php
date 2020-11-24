<?php

namespace IngestionEngine\Connector\Api;

use Magento\Framework\DataObject;
use IngestionEngine\Connector\Api\Data\ImportInterface;

/**
 * Interface ImportRepositoryInterface
 *
 * @category  Interface
 * @package   IngestionEngine\Connector\Api
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
interface ImportRepositoryInterface
{

    /**
     * Description add function
     *
     * @param DataObject $import
     *
     * @return void
     */
    public function add(DataObject $import);

    /**
     * Description getByCode function
     *
     * @param string $code
     *
     * @return ImportInterface
     */
    public function getByCode($code);

    /**
     * Description getList function
     *
     * @return Iterable
     */
    public function getList();

    /**
     * Description deleteByCode function
     *
     * @param string $code
     *
     * @return void
     */
    public function deleteByCode($code);
}
