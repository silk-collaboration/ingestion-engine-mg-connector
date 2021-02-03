<?php

namespace IngestionEngine\Connector\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use IngestionEngine\Connector\Api\ImportRepositoryInterface;
use IngestionEngine\Connector\Converter\ArrayToJsonResponseConverter;
use IngestionEngine\Connector\Helper\Output as OutputHelper;
use IngestionEngine\Connector\Job\Import;
use IngestionEngine\Connector\Job\Product as JobProduct;

/**
 * Class RunProduct
 *
 * @package   IngestionEngine\Connector\Controller\Adminhtml\Import
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 Agence Dn'D
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class RunProduct extends Action
{
    /**
     * This variable contains an OutputHelper
     *
     * @var OutputHelper $outputHelper
     */
    protected $outputHelper;
    /**
     * This variable contains an ImportRepositoryInterface
     *
     * @var ImportRepositoryInterface $importRepository
     */
    protected $importRepository;
    /**
     * This variable contains a ArrayToJsonResponseConverter
     *
     * @var ArrayToJsonResponseConverter $arrayToJsonResponseConverter
     */
    protected $arrayToJsonResponseConverter;
    /**
     * This variable contains a JobProduct
     *
     * @var JobProduct $jobProduct
     */
    protected $jobProduct;

    /**
     * Run constructor.
     *
     * @param Context                      $context
     * @param ImportRepositoryInterface    $importRepository
     * @param OutputHelper                 $output
     * @param ArrayToJsonResponseConverter $arrayToJsonResponseConverter
     * @param JobProduct                   $jobProduct
     */
    public function __construct(
        Context $context,
        ImportRepositoryInterface $importRepository,
        OutputHelper $output,
        ArrayToJsonResponseConverter $arrayToJsonResponseConverter,
        JobProduct $jobProduct
    ) {
        parent::__construct($context);

        $this->outputHelper                 = $output;
        $this->importRepository             = $importRepository;
        $this->arrayToJsonResponseConverter = $arrayToJsonResponseConverter;
        $this->jobProduct                   = $jobProduct;
    }

    /**
     * Action triggered by request
     *
     * @return Json
     */
    public function execute()
    {

        $families = array("1");
//        /** @var string[] $families */
//        $families = $this->jobProduct->getFamiliesToImport();
//
//        if (!count($families)) {
//            $families['message'] = __('No family to import');
//        }

        return $this->arrayToJsonResponseConverter->convert($families);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('IngestionEngine_Connector::import');
    }
}
