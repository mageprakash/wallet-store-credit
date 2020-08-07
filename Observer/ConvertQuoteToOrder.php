<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Observer;

class ConvertQuoteToOrder implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MagePrakash\Wallet\Api\ManageCustomerWalletInterface
     */
    private $balanceManagement;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

    public function __construct(
        \MagePrakash\Wallet\Model\BalanceManagement $balanceManagement
    ) {
        $this->balanceManagement = $balanceManagement;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getData('quote');
        if ($quote->getWalletUse()) 
        {
            $order->setWalletUse($quote->getWalletUse());
            $order->setWalletBaseAmount($quote->getWalletBaseAmount());
            $order->setWalletAmount($quote->getWalletAmount());

            $this->balanceManagement->addOrSubtractWalletBalance(
                $quote->getCustomerId(),
                -$quote->getWalletBaseAmount(),
                4,
                [$order->getIncrementId()],
                $quote->getStoreId()
            );
        }
    }
}
