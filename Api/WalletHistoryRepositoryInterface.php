<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface WalletHistoryRepositoryInterface
{

    /**
     * Save wallet_history
     * @param \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
    );

    /**
     * Retrieve wallet_history
     * @param string $walletHistoryId
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($walletHistoryId);

    /**
     * Retrieve wallet_history matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MagePrakash\Wallet\Api\Data\WalletHistorySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete wallet_history
     * @param \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \MagePrakash\Wallet\Api\Data\WalletHistoryInterface $walletHistory
    );

    /**
     * Delete wallet_history by ID
     * @param string $walletHistoryId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($walletHistoryId);
}
