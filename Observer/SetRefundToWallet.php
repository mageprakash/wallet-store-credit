<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Class SetRefundToWallet
 *
 * @package MagePrakash\Wallet\Observer
 */
class SetRefundToWallet implements ObserverInterface
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param RequestInterface $request
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        RequestInterface $request
    ) {
        $this->priceCurrency = $priceCurrency;
        $this->request = $request;
    }

    /**
     * Set refund flag to creditmemo based on user input
     * used for event: adminhtml_sales_order_creditmemo_register_before
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        if ($this->request->getActionName() == 'updateQty') {
            return $this;
        }

        $input = $observer->getEvent()->getInput();
        $creditMemo = $observer->getEvent()->getCreditmemo();
        $order = $creditMemo->getOrder();

        if (isset($input['refund_to_wallet_enable']))
        {
            $enable = $input['refund_to_wallet_enable'];
            $amount = $input['refund_to_store_credit'];

            if ($enable == 1 && is_numeric($amount)) {
                if ((string)(float)$amount > (string)(float)$creditMemo->getBaseCustomerBalanceReturnMax()) {
                    $maxAllowedAmount = $order->getBaseCurrency()
                        ->format($creditMemo->getBaseCustomerBalanceReturnMax(), null, false);
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('Maximum wallet balance allowed to refund is: %1', $maxAllowedAmount)
                    );
                } else {
                    $amount = $this->priceCurrency->round($amount);

                    $creditMemo->setWalletBaseAmount($amount);

                    $amount = $this->priceCurrency->round(
                        $amount * $creditMemo->getOrder()->getBaseToOrderRate()
                    );
                    $creditMemo->setWalletAmount($amount);
                }
            }
        }else{
            $creditMemo->setWalletBaseAmount(0);
            $creditMemo->setWalletAmount(0);
        }


        return $this;
    }
}
