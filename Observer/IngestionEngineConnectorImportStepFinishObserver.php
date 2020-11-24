<?php

namespace IngestionEngine\Connector\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use IngestionEngine\Connector\Api\Data\ImportInterface;
use IngestionEngine\Connector\Api\Data\LogInterface;
use IngestionEngine\Connector\Api\LogRepositoryInterface;

/**
 * Class IngestionEngineConnectorImportStepFinishObserver
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Observer
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class IngestionEngineConnectorImportStepFinishObserver implements ObserverInterface
{
    /**
     * This variable contains a LogRepositoryInterface
     *
     * @var LogRepositoryInterface $logRepository
     */
    protected $logRepository;

    /**
     * IngestionEngineConnectorImportStepFinishObserver constructor
     *
     * @param LogRepositoryInterface $logRepository
     */
    public function __construct(
        LogRepositoryInterface $logRepository
    ) {
        $this->logRepository = $logRepository;
    }

    /**
     * Log end of the step
     *
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {

//        echo "step finish observer start";
        /** @var $import ImportInterface */
        $import = $observer->getEvent()->getImport();
        /** @var LogInterface $log */
        $log = $this->logRepository->getByIdentifier($import->getIdentifier());

        if (!$log->hasData()) {
            return $this;
        }

        if ($import->getStep() + 1 == $import->countSteps()) {
            $log->setStatus(ImportInterface::IMPORT_SUCCESS); // Success
            $this->logRepository->save($log);
        }

        if ($import->isDone() && !$import->getStatus()) {
            $log->setStatus(ImportInterface::IMPORT_ERROR); // Error
            $this->logRepository->save($log);
        }

        $log->addStep(
            [
                'log_id' => $log->getId(),
                'identifier' => $import->getIdentifier(),
                'number' => $import->getStep(),
                'method' => $import->getMethod(),
                'message' => $import->getMessage(),
                'continue' => $import->isDone() ? 0 : 1,
                'status' => $import->getStatus() ? 1 : 0,
            ]
        );

        return $this;
    }
}
