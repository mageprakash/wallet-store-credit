<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use MagePrakash\Wallet\Model\ResourceModel\WalletHistory as ResourceWalletHistory;
use MagePrakash\Wallet\Api\Data\WalletHistorySearchResultsInterfaceFactory;
use MagePrakash\Wallet\Api\WalletHistoryRepositoryInterface;
use MagePrakash\Wallet\Api\Data\WalletHistoryInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use MagePrakash\Wallet\Model\ResourceModel\WalletHistory\CollectionFactory as WalletHistoryCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class WalletHistoryRepository implements WalletHistoryRepositoryInterface
{

    protected $extensionAttributesJoinProcessor;

    protected $dataObjectProcessor;

    private $storeManager;

    protected $dataWalletHistoryFactory;

    protected $walletHistoryFactory;

    protected $searchResultsFactory;

    private $collectionProcessor;

    protected $walletHistoryCollectionFactory;

    protected $dataObjectHelper;

    protected $resource;

    protected $extensibleDataObjectConverter;

    /**
     * @param ResourceWalletHistory $resource
     * @param WalletHistoryFactory $walletHistoryFactory
     * @param WalletHistoryInterfaceFactory $dataWalletHistoryFactory
     * @param WalletHistoryCollectionFactory $walletHistoryCollectionFactory
     * @param WalletHistorySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceWalletHistory $resource,
        WalletHistoryFactory $walletHistoryFactory,
        WalletHistoryInterfaceFactory $dataWalletHistoryFactory,
        WalletHistoryCollectionFactory $walletHistoryCollectionFactory,
        WalletHistorySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->walletHistoryFactory = $walletHistoryFactory;
        $this->walletHistoryCollectionFactory = $walletHistoryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWalletHistoryFactory = $dataWalletHistoryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
    ) {
        /* if (empty($walletHistory->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $walletHistory->setStoreId($storeId);
        } */
        
        $walletHistoryData = $this->extensibleDataObjectConverter->toNestedArray(
            $walletHistory,
            [],
            \MagePrakash\Wallet\Api\Data\WalletHistoryInterface::class
        );
        
        $walletHistoryModel = $this->walletHistoryFactory->create()->setData($walletHistoryData);
        
        try {
            $this->resource->save($walletHistoryModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the walletHistory: %1',
                $exception->getMessage()
            ));
        }
        return $walletHistoryModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($walletHistoryId)
    {
        $walletHistory = $this->walletHistoryFactory->create();
        $this->resource->load($walletHistory, $walletHistoryId);
        if (!$walletHistory->getId()) {
            throw new NoSuchEntityException(__('wallet_history with id "%1" does not exist.', $walletHistoryId));
        }
        return $walletHistory->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->walletHistoryCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \MagePrakash\Wallet\Api\Data\WalletHistoryInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
    ) {
        try {
            $walletHistoryModel = $this->walletHistoryFactory->create();
            $this->resource->load($walletHistoryModel, $walletHistory->getWalletHistoryId());
            $this->resource->delete($walletHistoryModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the wallet_history: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($walletHistoryId)
    {
        return $this->delete($this->getById($walletHistoryId));
    }
}
