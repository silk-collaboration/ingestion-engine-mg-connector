<?php

namespace IngestionEngine\Connector\Observer\Deletion;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use IngestionEngine\Connector\Helper\Import\Entities;
use IngestionEngine\Connector\Job\Family as ImportJob;

/**
 * Class FamilyObserver
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Observer\Deletion
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class FamilyObserver implements ObserverInterface
{
    /**
     * This variable contains an Entities
     *
     * @var Entities $entities
     */
    protected $entities;
    /**
     * This variable contains an Attribute
     *
     * @var ImportJob $job
     */
    protected $job;

    /**
     * FamilyObserver Constructor
     *
     * @param Entities $entities
     * @param ImportJob $job
     */
    public function __construct(
        Entities $entities,
        ImportJob $job
    ) {
        $this->entities = $entities;
        $this->job      = $job;
    }
    /**
     * Remove entity relation
     *
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var $attributeSet \Magento\Eav\Model\Entity\Attribute\set */
        $attributeSet = $observer->getEvent()->getObject();

        $this->entities->delete($this->job->getCode(), $attributeSet->getId());
    }
}
