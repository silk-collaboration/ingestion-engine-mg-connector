<?php

namespace IngestionEngine\Connector\Job;

use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Data\CollectionFactory as CollectionFactory;
use IngestionEngine\Connector\Api\Data\ImportInterface;
use IngestionEngine\Connector\Api\ImportRepositoryInterface;
use IngestionEngine\Connector\Helper\Config as ConfigHelper;

/**
 * Class ImportRepository
 *
 * @category  Class
 * @package   IngestionEngine\Connector\Job
 * @author    IngestionEngine <sales@silksoftware.com>
 * @copyright 2020 IngestionEngine
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://www.silksoftware.com/
 */
class ImportRepository implements ImportRepositoryInterface
{

    /**
     * This variable contains an EntityFactoryInterface
     *
     * @var EntityFactoryInterface $entityFactory
     */
    protected $entityFactory;
    /**
     * This variable contains a Collection
     *
     * @var Collection $collection
     */
    protected $collection;
    /**
     * This variable contains a ConfigHelper
     *
     * @var ConfigHelper $configHelper
     */
    protected $configHelper;

    /**
     * ImportRepository constructor.
     *
     * @param EntityFactoryInterface $entityFactory
     * @param CollectionFactory $collectionFactory
     * @param ConfigHelper $configHelper
     * @param array $data
     *
     * @throws \Exception
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        CollectionFactory $collectionFactory,
        ConfigHelper $configHelper,
        $data = []
    ) {
        $this->entityFactory = $entityFactory;
        $this->collection    = $collectionFactory->create();
        $this->configHelper  = $configHelper;

        $this->initCollection($data);
    }

    /**
     * Load available imports
     *
     * @param array $data
     *
     * @return void
     * @throws \Exception
     */
    public function initCollection($data)
    {
        /** @var Import $import */
        foreach ($data as $id => $import) {
            $import->setData('id', $id);
            $this->add($import);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add(DataObject $import)
    {
        $this->collection->addItem($import);
    }

    /**
     * {@inheritdoc}
     */
    public function getByCode($code)
    {
        /** @var ImportInterface $import */
        $import = $this->collection->getItemById($code);

        return $import;
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        return $this->collection;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteByCode($code)
    {
        $this->collection->removeItemByKey($code);
    }
}
