<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Model\Total\Invoice;

use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Wallet extends AbstractTotal
{
    /**
     * Customer balance data
     *
     * @var \Magento\CustomerBalance\Helper\Data
     */
    protected $walletData = null;

    /**
     * @param \Magento\CustomerBalance\Helper\Data $customerBalanceData
     * @param array $data
     */
    public function __construct(\MagePrakash\Wallet\Helper\Data $walletData, array $data = [])
    {
        $this->walletData = $walletData;
        parent::__construct($data);
    }

    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        if (!$this->walletData->isEnabled()) {
            return $this;
        }

        $order = $invoice->getOrder();
        if ($order->getWalletAmount() > 0) {
            $invoiceBaseGrandTotal = $invoice->getBaseGrandTotal();
            $invoiceGrandTotal = $invoice->getGrandTotal();

            $leftWallet = $order->getWalletAmount() - $order->getWalletInvoicedAmount();
            $leftBaseWallet = $order->getWalletBaseAmount() - $order->getWalletInvoicedBaseAmount();

            if ($leftBaseWallet > $invoiceBaseGrandTotal) {
                $invoice->setWalletBaseAmount($invoiceBaseGrandTotal);
                $invoice->setWalletAmount($invoiceGrandTotal);
                $invoiceBaseGrandTotal = 0;
                $invoiceGrandTotal = 0;
            } else {
                $invoiceGrandTotal -= $leftWallet;
                $invoice->setWalletAmount($leftWallet);
                $invoiceBaseGrandTotal -= $leftBaseWallet;
                $invoice->setWalletBaseAmount($leftBaseWallet);
            }

            if ($invoiceGrandTotal < 0.0001) {
                $invoiceGrandTotal = $invoiceBaseGrandTotal = 0;
            }

            $order->setWalletInvoicedBaseAmount(
                $order->getWalletInvoicedBaseAmount() + $invoice->getWalletBaseAmount()
            );

            $order->setWalletInvoicedAmount(
                $order->getWalletInvoicedAmount() + $invoice->getWalletAmount()
            );

            $invoice->setBaseGrandTotal($invoiceBaseGrandTotal);
            $invoice->setGrandTotal($invoiceGrandTotal);
        }
        return $this;
    }
}
