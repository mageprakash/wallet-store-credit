<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Api;

interface BalanceManagementInterface
{
    /**
     * @param int $cartId
     * @param float $amount
     *
     * @return float
     */
    public function apply($cartId, $amount);

    /**
     * @param int $cartId
     *
     * @return float
     */
    public function remove($cartId);
}
