<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Plugin;

class RefundToWallet
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $currency;

    /**
     * @var \MagePrakash\Wallet\Model\BalanceManagement
     */
    private $balanceManagement;


    /**
     * @var \Magento\Sales\Model\OrderRepository
     */
    private $orderRepository;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Pricing\PriceCurrencyInterface $currency,
        \MagePrakash\Wallet\Model\BalanceManagement $balanceManagement,
        \Magento\Sales\Model\OrderRepository $orderRepository
    ) {
        $this->request = $request;
        $this->currency = $currency;
        $this->balanceManagement = $balanceManagement;
        $this->orderRepository = $orderRepository;
    }

    public function beforeRefund(
        \Magento\Sales\Model\Service\CreditmemoService $subject,
        \Magento\Sales\Api\Data\CreditmemoInterface $creditmemo,
        $offlineRequested = false
    ) {
            if ($amount = $creditmemo->getWalletAmount()) {
                
                $order = $creditmemo->getOrder();
                $order->setWalletRefundedAmount($order->getWalletRefundedAmount() + $amount);
                $order->setWalletRefundedBaseAmount(
                    $order->getWalletRefundedBaseAmount() + $creditmemo->getWalletBaseAmount()
                );
                
                $this->balanceManagement->addOrSubtractWalletBalance(
                    $creditmemo->getCustomerId(),
                    $amount,
                    3,
                    [$this->orderRepository->get($creditmemo->getOrderId())->getIncrementId()],
                    $creditmemo->getStoreId()
                );
            }

        return [$creditmemo, $offlineRequested];
    }
}
