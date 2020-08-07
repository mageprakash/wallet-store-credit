<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */
namespace MagePrakash\Wallet\Model\Plugin;

use Magento\Quote\Model\Quote;

class TotalsCollector
{
    /**
     * Reset quote reward point amount
     *
     * @param \Magento\Quote\Model\Quote\TotalsCollector $subject
     * @param Quote $quote
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCollect(
        \Magento\Quote\Model\Quote\TotalsCollector $subject,
        Quote $quote
    ) {
        //$quote->setWalletAmount(0);
       // $quote->setWalletBaseAmount(0);
    }
}
