<?php

namespace IngestionEngine\Connector\Controller\Adminhtml\Log;

use Magento\Backend\App\Action;

/**
 * Class View
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Controller\Adminhtml\Log
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class View extends Action
{
    /**
     * Action
     *
     * @return void
     */
    public function execute()
    {
        $this->_view->loadLayout();

        /* @var $block \IngestionEngine\Connector\Block\Adminhtml\Log\View */
        $block = $this->_view->getLayout()->getBlock('adminhtml.ingestionengine_connector.log.view');
        $block->setLogId(
            $this->getRequest()->getParam('log_id')
        );

        $this->_setActiveMenu('Magento_Backend::system');
        $this->_addBreadcrumb(__('IngestionEngine Connector'), __('Log'));

        $this->_view->renderLayout();
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('IngestionEngine_Connector::ingestionengine_connector_log');
    }
}
