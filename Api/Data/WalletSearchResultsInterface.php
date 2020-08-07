<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Api\Data;

interface WalletSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get wallet list.
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface[]
     */
    public function getItems();

    /**
     * Set website_id list.
     * @param \MagePrakash\Wallet\Api\Data\WalletInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
