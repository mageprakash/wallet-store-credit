<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Api\Data;

interface WalletHistoryInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const BALANCE_AMOUNT = 'balance_amount';
    const WALLET_HISTORY_ID = 'wallet_history_id';
    const ACTION = 'action';
    const CREATED_AT = 'created_at';
    const WALLET_ID = 'wallet_id';
    const CUSTOMER_ID = 'customer_id';
    const ADDITIONAL_INFO = 'additional_info';
    const BALANCE_DIFFERENCE = 'balance_difference';

    /**
     * Get wallet_history_id
     * @return string|null
     */
    public function getWalletHistoryId();

    /**
     * Set wallet_history_id
     * @param string $walletHistoryId
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setWalletHistoryId($walletHistoryId);

    /**
     * Get wallet_id
     * @return string|null
     */
    public function getWalletId();

    /**
     * Set wallet_id
     * @param string $walletId
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setWalletId($walletId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface $extensionAttributes
    );

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get balance_amount
     * @return string|null
     */
    public function getBalanceAmount();

    /**
     * Set balance_amount
     * @param string $balanceAmount
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setBalanceAmount($balanceAmount);

    /**
     * Get balance_difference
     * @return string|null
     */
    public function getBalanceDifference();

    /**
     * Set balance_difference
     * @param string $balanceDifference
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setBalanceDifference($balanceDifference);

    /**
     * Get action
     * @return string|null
     */
    public function getAction();

    /**
     * Set action
     * @param string $action
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setAction($action);

    /**
     * Get additional_info
     * @return string|null
     */
    public function getAdditionalInfo();

    /**
     * Set additional_info
     * @param string $additionalInfo
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setAdditionalInfo($additionalInfo);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setCreatedAt($createdAt);
}
