<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model;

use MagePrakash\Wallet\Api\Data\WalletHistoryInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use MagePrakash\Wallet\Api\Data\WalletHistoryInterface;
use MagePrakash\Wallet\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;

class WalletHistory extends \Magento\Framework\Model\AbstractModel
{

    protected $wallet_historyDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'mageprakash_wallet_wallet_history';

    private $priceCurrency;

    protected $_customerRepositoryInterface;

    private $email;
    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param WalletHistoryInterfaceFactory $wallet_historyDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \MagePrakash\Wallet\Model\ResourceModel\WalletHistory $resource
     * @param \MagePrakash\Wallet\Model\ResourceModel\WalletHistory\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        WalletHistoryInterfaceFactory $wallet_historyDataFactory,
        DataObjectHelper $dataObjectHelper,
        \MagePrakash\Wallet\Model\ResourceModel\WalletHistory $resource,
        \MagePrakash\Wallet\Model\ResourceModel\WalletHistory\Collection $resourceCollection,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        Email $email,
        Data $walletData,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->priceCurrency = $priceCurrency;        
        $this->wallet_historyDataFactory = $wallet_historyDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->email = $email;
        $this->walletData = $walletData;
        $this->storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve wallet_history model with wallet_history data
     * @return WalletHistoryInterface
     */
    public function getDataModel()
    {
        $wallet_historyData = $this->getData();
        
        $wallet_historyDataObject = $this->wallet_historyDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $wallet_historyDataObject,
            $wallet_historyData,
            WalletHistoryInterface::class
        );
        
        return $wallet_historyDataObject;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageprakash_wallet_wallet_history', 'wallet_history_id');
    }

    public function getFormatDifference($scope = null, $currency = null)
    {
        return $this->priceCurrency->convertAndFormat(
            $this->_getData('balance_difference'),
            false,
            2,
            $scope,
            $currency
        );
    }

    public function getFormatWalletBalance($scope = null, $currency = null)
    {
        return $this->priceCurrency->convertAndFormat(
            $this->_getData('balance_amount'),
            false,
            2,
            $scope,
            $currency
        );
    }

        /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getSmallActionMessage()
    {
        $actionsFull = [
            1 => 'Administrator added %1 balance to your wallet',
            2 => 'Administrator removed %1 balance from your wallet',
            3 => 'You order #%1 was refunded on %2',
            4 => 'Order #%1 was payed with %2 wallet',
            5 => 'Order #%1 was canceled. Returned %2 wallet',
        ];

        $additional_info = $this->_getData('additional_info');
        $additional_info = json_decode($additional_info);
        
        $text            = $actionsFull[$this->_getData('action')];
        if($this->_getData('action') == 3 || $this->_getData('action') == 4 || $this->_getData('action') == 5){
            $text  = __($text,...[$additional_info[0],$this->priceCurrency->convertAndFormat($this->_getData('balance_difference'),false,
                                2)]);
        }else{
             $text = __($text,$this->priceCurrency->convertAndFormat($this->_getData('balance_difference'),false,
                                2));
        }
        return $text;
    }

    public  function  afterSave()
    {
        $vars = [];
        $vars["message"] = $this->getSmallActionMessage();

        $customer = $this->_customerRepositoryInterface->getById($this->getCustomerId());
        $this->email->sendEmail(
            [
                'email' => $customer->getEmail(),
                'name'  => $customer->getFirstname()
            ],
            $vars,
            \Magento\Framework\App\Area::AREA_FRONTEND,
            $this->walletData->getStorename(),
            $this->walletData->getStoreEmail(),
            $this->storeManager->getStore()->getId()
        );
    }

    public function beforeSave()
    {
        $wallet = $this->getWalletModel();
        
        if (!$wallet || !$wallet->getWalletId()) {
            throw new LocalizedException(__('A balance is needed to save a balance history.'));
        }

        $data = $wallet->getWalletHistoryData();
        $amount = isset($data['difference'])?$data['difference']:'';
       
        $this->addData(
            [
                'wallet_id'          => $wallet->getWalletId(),
                'customer_id'        => $wallet->getCustomerId(),
                'balance_amount'     => $wallet->getCurrentBalance(),
                'balance_difference' => abs($data['difference']),
                'is_deduct'          => ($amount < 0),
                'action'             => $data['action'],
                'additional_info'    => json_encode($data['action_data']),
                'comment'            => $data['comment'],
                'created_at'         => time()
            ]
        );
        return parent::beforeSave();
    }
}
