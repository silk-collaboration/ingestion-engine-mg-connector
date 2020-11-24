<?php

namespace IngestionEngine\Connector\Controller\Adminhtml\Test;

use IngestionEngine\Connector\Helper\Authenticator;
use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultInterface;
use Exception;

/**
 * Class Index
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Controller\Adminhtml\Test
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magento_Backend::system';
    /**
     * @var Authenticator $authenticator
     */
    protected $authenticator;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Index constructor
     *
     * @param Action\Context $context
     * @param Authenticator  $authenticator
     * @param ConfigHelper   $configHelper
     */
    public function __construct(
        Action\Context $context,
        Authenticator $authenticator,
        ConfigHelper $configHelper
    ) {
        parent::__construct($context);

        $this->authenticator = $authenticator;
        $this->configHelper  = $configHelper;
    }

    /**
     * Execute API test
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $this->messageManager->addErrorMessage('debug');
        echo "test debug"; die;
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $client = $this->authenticator->getIngestionEngineApiClient();
            if (!$client) {
                $this->messageManager->addErrorMessage(__('IngestionEngine API connection error'));
            } else {
                /** @var string|int $paginationSize */
                $paginationSize = $this->configHelper->getPaginationSize();
                $client->getChannelApi()->all($paginationSize);
                $this->messageManager->addSuccessMessage(__('The connection is working fine'));
            }
        } catch (Exception $ext) {
            $this->messageManager->addErrorMessage($ext->getMessage());
        }

        return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    }
}
