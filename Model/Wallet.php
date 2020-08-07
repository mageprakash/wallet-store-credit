<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model;

use Magento\Framework\Api\DataObjectHelper;
use MagePrakash\Wallet\Api\Data\WalletInterface;
use MagePrakash\Wallet\Api\Data\WalletInterfaceFactory;

class Wallet extends \Magento\Framework\Model\AbstractModel
{

    protected $_eventPrefix = 'mageprakash_wallet_wallet';
    protected $dataObjectHelper;

    protected $walletDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param WalletInterfaceFactory $walletDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \MagePrakash\Wallet\Model\ResourceModel\Wallet $resource
     * @param \MagePrakash\Wallet\Model\ResourceModel\Wallet\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        WalletInterfaceFactory $walletDataFactory,
        DataObjectHelper $dataObjectHelper,
        \MagePrakash\Wallet\Model\ResourceModel\Wallet $resource,
        \MagePrakash\Wallet\Model\ResourceModel\Wallet\Collection $resourceCollection,
        WalletHistoryFactory $walletHistoryFactory,
        array $data = []
    ) {
        $this->walletHistoryFactory = $walletHistoryFactory;        
        $this->walletDataFactory    = $walletDataFactory;
        $this->dataObjectHelper     = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve wallet model with wallet data
     * @return WalletInterface
     */
    public function getDataModel()
    {
        $walletData = $this->getData();
        
        $walletDataObject = $this->walletDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $walletDataObject,
            $walletData,
            WalletInterface::class
        );
        
        return $walletDataObject;
    }

    public function loadByCustomerId($customerId,$WebsiteId){
        $this->_getResource()->loadByCustomerId($this, $customerId,$WebsiteId);
        return $this;
    }

    public function afterSave()
    {
        parent::afterSave();
        $this->walletHistoryFactory->create()->setWalletModel($this)->save();
        return $this;
    }
}
