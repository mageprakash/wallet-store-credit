<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Observer;



class ReturnWallet implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MagePrakash\Wallet\Model\BalanceManagement
     */
    private $balanceManagement;

    /**
     * @var \MagePrakash\Wallet\Helper\Data
     */
    private $walletData;

    public function __construct(
        \MagePrakash\Wallet\Model\BalanceManagement $balanceManagement,
        \MagePrakash\Wallet\Helper\Data $walletData
    ) {
        $this->balanceManagement = $balanceManagement;
        $this->walletData = $walletData;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->walletData->isEnabled()) {
            $order = $observer->getData('order');
            $walletBalanceLeft = $order->getWalletBaseAmount()
                - $order->getWalletRefundedBaseAmount();
            if (($customerId = $order->getCustomerId()) && $walletBalanceLeft > 0) {
                $this->balanceManagement->addOrSubtractWalletBalance(
                    $customerId,
                    $walletBalanceLeft,
                    5,
                    [
                        $order->getIncrementId()
                    ],
                    $order->getStoreId()
                );
                $order->getWalletRefundedAmount($order->getWalletAmount());
                $order->getWalletRefundedBaseAmount($order->getWalletRefundedBaseAmount());
            }
        }
    }
}
