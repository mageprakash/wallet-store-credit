<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Api\Data;

interface WalletInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const WALLET_ID = 'wallet_id';
    const CUSTOMER_ID = 'customer_id';
    const CURRENT_BALANCE = 'current_balance';
    const WEBSITE_ID = 'website_id';

    /**
     * Get wallet_id
     * @return string|null
     */
    public function getWalletId();

    /**
     * Set wallet_id
     * @param string $walletId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setWalletId($walletId);

    /**
     * Get website_id
     * @return string|null
     */
    public function getWebsiteId();

    /**
     * Set website_id
     * @param string $websiteId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setWebsiteId($websiteId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \MagePrakash\Wallet\Api\Data\WalletExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \MagePrakash\Wallet\Api\Data\WalletExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MagePrakash\Wallet\Api\Data\WalletExtensionInterface $extensionAttributes
    );

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param string $customerId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get current_balance
     * @return string|null
     */
    public function getCurrentBalance();

    /**
     * Set current_balance
     * @param string $currentBalance
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setCurrentBalance($currentBalance);
}
