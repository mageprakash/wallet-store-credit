<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Plugin;

use Magento\Quote\Model\Quote;

class ResetWalletBalanceAfterItemDelete
{
    public function afterRemoveItem(Quote $quote, Quote $result)
    {
        $result->setData('wallet_use', 0);
        return $result;
    }
}
