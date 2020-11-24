<?php

namespace IngestionEngine\Connector\Block\Adminhtml\System\Config\Api;

use IngestionEngine\Connector\Helper\Config as ConfigHelper;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Backend\Block\Widget\Button;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Test
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Block\Adminhtml\System\Config\Api
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Test extends Field
{
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * Test constructor
     *
     * @param Context $context
     * @param ConfigHelper $configHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        ConfigHelper $configHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->configHelper = $configHelper;
    }

    /**
     * Retrieve element HTML markup
     *
     * @param AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        /** @var \Magento\Backend\Block\Widget\Button $buttonBlock  */
        $buttonBlock = $this->getForm()->getLayout()->createBlock(Button::class);

        $website = $buttonBlock->getRequest()->getParam('website');
        $store   = $buttonBlock->getRequest()->getParam('store');

        $params = [
            'website' => $website,
            'store'   => $store
        ];

        $data = [
            'label' => $this->getLabel(),
            'onclick' => "setLocation('" . $this->getTestUrl($params) . "')",
        ];

        /** @var string $baseUri */
        $baseUri = $this->configHelper->getIngestionEngineApiBaseUrl();
        /** @var string $clientId */
        $clientId = $this->configHelper->getIngestionEngineApiClientId();
        /** @var string $secret */
        $secret = $this->configHelper->getIngestionEngineApiClientSecret();
        /** @var string $username */
        $username = $this->configHelper->getIngestionEngineApiUsername();
        /** @var string $password */
        $password = $this->configHelper->getIngestionEngineApiPassword();

        if (!$baseUri || !$clientId || !$secret || !$username || !$password) {
            $data['disabled'] = true;
        }

        $html = $buttonBlock->setData($data)->toHtml();

        return $html;
    }

    /**
     * Retrieve button label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return  __('Test');
    }

    /**
     * Retrieve Button URL
     *
     * @param array
     * @return string
     */
    public function getTestUrl($params = [])
    {
        return $this->getUrl('ingestionengine_connector/test', $params);
    }
}
