<?php

namespace IngestionEngine\Connector\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Attribute
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Block\Adminhtml\System\Config\Form\Field
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class Attribute extends AbstractFieldArray
{
    /**
     * Initialise form fields
     *
     * @return void
     */
    protected function _construct()
    {
        $this->addColumn('ingestionengine_attribute', ['label' => __('IngestionEngine')]);
        $this->addColumn('magento_attribute', ['label' => __('Magento')]);
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add');

        parent::_construct();
    }
}
