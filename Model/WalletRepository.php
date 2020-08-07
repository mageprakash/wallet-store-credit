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
use MagePrakash\Wallet\Model\ResourceModel\Wallet\CollectionFactory as WalletCollectionFactory;
use MagePrakash\Wallet\Api\WalletRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use MagePrakash\Wallet\Api\Data\WalletInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use MagePrakash\Wallet\Model\ResourceModel\Wallet as ResourceWallet;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Store\Model\StoreManagerInterface;
use MagePrakash\Wallet\Api\Data\WalletSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class WalletRepository implements WalletRepositoryInterface
{

    protected $extensionAttributesJoinProcessor;

    protected $dataObjectProcessor;

    private $storeManager;

    protected $dataWalletFactory;

    protected $searchResultsFactory;

    private $collectionProcessor;

    protected $dataObjectHelper;

    protected $resource;

    protected $walletFactory;

    protected $walletCollectionFactory;

    protected $extensibleDataObjectConverter;
        /**
     * @var array
     */
    private $wallets = [];
    /**
     * @param ResourceWallet $resource
     * @param WalletFactory $walletFactory
     * @param WalletInterfaceFactory $dataWalletFactory
     * @param WalletCollectionFactory $walletCollectionFactory
     * @param WalletSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceWallet $resource,
        WalletFactory $walletFactory,
        WalletInterfaceFactory $dataWalletFactory,
        WalletCollectionFactory $walletCollectionFactory,
        WalletSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter      
    ) {
        $this->storeManager = $storeManager;
        $this->resource = $resource;
        $this->walletFactory = $walletFactory;
        $this->walletCollectionFactory = $walletCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataWalletFactory = $dataWalletFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
    ) {
        /* if (empty($wallet->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $wallet->setStoreId($storeId);
        } */
        
        $walletData = $this->extensibleDataObjectConverter->toNestedArray(
            $wallet,
            [],
            \MagePrakash\Wallet\Api\Data\WalletInterface::class
        );
        
        $walletModel = $this->walletFactory->create()->setData($walletData);
        
        try {
            $this->resource->save($walletModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the wallet: %1',
                $exception->getMessage()
            ));
        }
        return $walletModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($walletId)
    {
        $wallet = $this->walletFactory->create();
        $this->resource->load($wallet, $walletId);
        if (!$wallet->getId()) {
            throw new NoSuchEntityException(__('wallet with id "%1" does not exist.', $walletId));
        }
        return $wallet->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->walletCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \MagePrakash\Wallet\Api\Data\WalletInterface::class
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
        \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
    ) {
        try {
            $walletModel = $this->walletFactory->create();
            $this->resource->load($walletModel, $wallet->getWalletId());
            $this->resource->delete($walletModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the wallet: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($walletId)
    {
        return $this->delete($this->getById($walletId));
    }

    public function loadByCustomerId($customerId)
    {

        // if (!empty($this->wallets[$customerId])) {
        //     return $this->wallets[$customerId];
        // }
        
        if ($wallet = $this->walletFactory->create()->loadByCustomerId($customerId,$this->storeManager->getStore()->getWebsiteId())) {
            if($wallet->getWalletId())
            {
                $this->wallets[$customerId] = $wallet;
                return $wallet;
            }
        }

        $wallet = $this->walletFactory->create();
        $wallet->setCustomerId($customerId)
                ->setWebsiteId($this->storeManager->getStore()->getWebsiteId())        
                ->setCurrentBalance('0.00');

        $this->wallets[$customerId] = $wallet;

        return $wallet;
    }

}
