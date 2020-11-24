<?php

namespace IngestionEngine\Connector\Helper;

use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class Output
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Helper
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Output
{

    /**
     * This constant contains a string
     *
     * @var string PREFIX_DATE_FORMAT
     */
    const PREFIX_DATE_FORMAT = 'H:i:s';
    /**
     * This variable contains a DateTime
     *
     * @var DateTime $datetime
     */
    protected $datetime;

    /**
     * Output constructor.
     *
     * @param DateTime $datetime
     */
    public function __construct(
        DateTime $datetime
    ) {
        $this->datetime = $datetime;
    }

    /**
     * Send an error when import code is not found
     *
     * @return array
     */
    public function getNoImportFoundResponse()
    {
        /** @var string $prefix */
        $prefix = $this->getPrefix();

        /** @var array $response */
        $response = [
            'comment'    => __('%1 Start import', $prefix),
            'continue'   => false,
            'identifier' => '',
            'message'    => __('%1 Import code not found', $prefix),
            'next'       => '',
            'status'     => false,
        ];

        return $response;
    }

    /**
     * Get output message prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        /** @var string $date */
        $date = $this->datetime->gmtDate(self::PREFIX_DATE_FORMAT);

        return '[' . $date . '] ';
    }

    /**
     * Send an error if import is already running
     *
     * @return array
     */
    public function getImportAlreadyRunningResponse()
    {
        /** @var string $prefix */
        $prefix = $this->getPrefix();

        /** @var array $response */
        $response = [
            'comment'    => __('%1 Start import', $prefix),
            'continue'   => false,
            'identifier' => '',
            'message'    => __('%1 Import already running', $prefix),
            'next'       => '',
            'status'     => false,
        ];

        return $response;
    }

    /**
     * Send Api Connexion error
     *
     * @return array
     */
    public function getApiConnectionError()
    {
        /** @var string $prefix */
        $prefix = $this->getPrefix();

        /** @var array $response */
        $response = [
            'comment'    => __('%1 Start import', $prefix),
            'continue'   => false,
            'identifier' => '',
            'message'    => __('IngestionEngine API connection error', $prefix),
            'next'       => '',
            'status'     => false,
        ];

        return $response;
    }
}
