<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Observer;

use Magento\Sales\Model\Order;

class IsCanCreditMemo implements \Magento\Framework\Event\ObserverInterface
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
            $order = $observer->getData('order');

            if ($order->canUnhold()) {
                return;
            }

            if ($order->isCanceled() || $order->getState() === Order::STATE_CLOSED) {
                return;
            }

            if (($order->getWalletInvoicedAmount() - $order->getWalletRefundedAmount()) > 0) {
                $order->setForcedCanCreditmemo(true);
            } elseif ($order->getWalletInvoicedAmount()) {
                $hide = true;
                foreach ($order->getItems() as $item) {
                    $qty = (double)$item->getQtyOrdered() - (double)$item->getQtyRefunded();
                    if ($qty > 0.0001) {
                        $hide = false;
                        $order->setForcedCanCreditmemo(true);
                        break;
                    }
                }

                if ($hide) {
                    $order->setForcedCanCreditmemo(false);
                }
            }
        }
    }
}
