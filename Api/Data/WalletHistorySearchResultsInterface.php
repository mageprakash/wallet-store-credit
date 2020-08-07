<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Api\Data;

interface WalletHistorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get wallet_history list.
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface[]
     */
    public function getItems();

    /**
     * Set wallet_id list.
     * @param \MagePrakash\Wallet\Api\Data\WalletHistoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
