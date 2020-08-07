<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Observer;


class RemoveWalletBalanceFromPayment implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MagePrakash\Wallet\Helper\Data
     */
    private $walletData;

    public function __construct(
        \MagePrakash\Wallet\Helper\Data $walletData
    ) {
        $this->walletData = $walletData;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->walletData->isEnabled()) {
            $cart = $observer->getData('cart');
            $salesEntity = $cart->getSalesModel();
            if ($salesEntity->getDataUsingMethod('entity_type') === 'order'
                || $salesEntity->getDataUsingMethod('wallet_use')
            ) {
                $value = abs($salesEntity->getDataUsingMethod('wallet_base_amount'));
                if ($value > 0.0001) {
                    $cart->addDiscount((double)$value);
                }
            }
        }
    }
}
