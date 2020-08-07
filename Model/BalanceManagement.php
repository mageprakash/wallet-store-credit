<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Model;

use MagePrakash\Wallet\Api\BalanceManagementInterface;
use MagePrakash\Wallet\Model\ResourceModel\Wallet as WalletResource;
use MagePrakash\Wallet\Model\WalletHistoryRepository;
use MagePrakash\Wallet\Model\WalletHistory;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use MagePrakash\Wallet\Helper\Data;

class BalanceManagement implements BalanceManagementInterface
{
    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    private $cartRepository;

    public function __construct(
        \Magento\Quote\Api\CartRepositoryInterface $cartRepository,
        \MagePrakash\Wallet\Api\WalletRepositoryInterface $walletRepository,
        WalletResource $wallet,
        WalletHistoryRepository $walletHistoryRepository,
        WalletHistoryFactory $walletHistoryFactory,
        CustomerRepository $customerRepository,
        Data $walletData
    ) {
        $this->cartRepository               = $cartRepository;
        $this->walletRepository             = $walletRepository;
        $this->wallet                       = $wallet;
        $this->walletHistoryRepository      = $walletHistoryRepository;
        $this->walletHistoryFactory         = $walletHistoryFactory;
        $this->customerRepository           = $customerRepository;
        $this->walletData                   = $walletData;
    }

    /**
     * @inheritdoc
     */
    public function apply($cartId, $amount)
    {
        $quote = $this->cartRepository->get($cartId);
        $quote->setWalletUse(1);
        $quote->setWalletAmount($amount);
        $quote->setWalletBaseAmount($amount);
        $quote->collectTotals();
        $quote->save();
        return $quote->getWalletAmount();
    }

    /**
     * @inheritdoc
     */
    public function remove($cartId)
    {
        $quote = $this->cartRepository->get($cartId);
        $quote->setWalletUse(0);
        $quote->setWalletAmount(0);
        $quote->setWalletBaseAmount(0);
        $quote->collectTotals();
        $quote->save();
        
        return $quote->getWalletAmount();
    }

    public function addOrSubtractWalletBalance(
        $customerId,
        $amount,
        $action,
        $actionData = [],
        $storeId = 0,
        $message = ''
    ) {
        $wallet = $this->walletRepository->loadByCustomerId($customerId);
        $newWallet = $wallet->getCurrentBalance() + (float)$amount;
        if ($newWallet < 0) {
            throw new LocalizedException(__('Wallet couldn\'t be less than zero.'));
        }
     
        $data = [
            'difference'  => $amount,
            'store_id'    => $storeId,
            'action'      => $action,
            'action_data' => $actionData,
            'comment'     => $message
        ];

        $wallet->setWalletHistoryData($data);
        $wallet->setCurrentBalance($newWallet);
        
        try {
            $this->wallet->save($wallet);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Unable to save Wallet. Error: %1', $e->getMessage()));
        }
    }
}
