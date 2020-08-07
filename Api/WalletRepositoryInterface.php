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

interface WalletRepositoryInterface
{

    /**
     * Save wallet
     * @param \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
    );

    /**
     * Retrieve wallet
     * @param string $walletId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($walletId);

    /**
     * Retrieve wallet matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MagePrakash\Wallet\Api\Data\WalletSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete wallet
     * @param \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \MagePrakash\Wallet\Api\Data\WalletInterface $wallet
    );

    /**
     * Delete wallet by ID
     * @param string $walletId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($walletId);
}
