<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model\Total\CreditMemo;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order\Creditmemo;
use MagePrakash\Wallet\Helper\Data;

class Wallet extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var PriceCurrencyInterface
     */
    private $walletData;

    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        RequestInterface $request,
        Data $walletData,
        array $data = []
    ) {
        parent::__construct($data);
        $this->request = $request;
        $this->walletData = $walletData;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo  $creditMemo
     * @return $this
     */

    public function collect(Creditmemo $creditMemo)
    {
        if (!$this->walletData->isEnabled()) {
            return $this;
        }

        $creditMemo->setWalletUse(false);
        $creditMemo->setWalletAmount(0);
        $creditMemo->setWalletBaseAmount(0);

        $creditMemo->setBaseCustomerBalanceReturnMax(0);
        $creditMemo->setCustomerBalanceReturnMax(0);

        $order = $creditMemo->getOrder();

        if ($order->getWalletBaseAmount() && $order->getWalletInvoicedBaseAmount() != 0) {
            $baseBalanceLeft = $order->getWalletInvoicedBaseAmount() - $order->getWalletRefundedBaseAmount();
            $balanceLeft = $order->getWalletInvoicedAmount() - $order->getWalletRefundedAmount();

            if( $baseBalanceLeft > 0)
            {
                if (abs($baseBalanceLeft) >= $creditMemo->getBaseGrandTotal()) {
                    $baseBalanceLeft = $creditMemo->getBaseGrandTotal() ?: $baseBalanceLeft;
                    $balanceLeft = $creditMemo->getGrandTotal() ?: $balanceLeft;

                    $creditMemo->setBaseGrandTotal(0);
                    $creditMemo->setGrandTotal(0);

                    $creditMemo->setAllowZeroGrandTotal(true);
                } else {

                    $creditMemo->setBaseGrandTotal($creditMemo->getBaseGrandTotal() - $baseBalanceLeft);
                    $creditMemo->setGrandTotal($creditMemo->getGrandTotal() - $balanceLeft);
                }

                $creditMemo->setWalletBaseAmount($baseBalanceLeft);
                $creditMemo->setWalletAmount($balanceLeft);

                $creditMemo->setBaseCustomerBalanceReturnMax(
                    $creditMemo->getBaseCustomerBalanceReturnMax()
                    + $creditMemo->getBaseGrandTotal()
                    + $creditMemo->getWalletBaseAmount()
                );
               $creditMemo->setCustomerBalanceReturnMax(
                    $creditMemo->getCustomerBalanceReturnMax()
                    + $creditMemo->getGrandTotal()
                    + $creditMemo->getWalletAmount()
                );
            }
        }
        return $this;
    }
}
