<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model\Data;

use MagePrakash\Wallet\Api\Data\WalletInterface;

class Wallet extends \Magento\Framework\Api\AbstractExtensibleObject implements WalletInterface
{

    /**
     * Get wallet_id
     * @return string|null
     */
    public function getWalletId()
    {
        return $this->_get(self::WALLET_ID);
    }

    /**
     * Set wallet_id
     * @param string $walletId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setWalletId($walletId)
    {
        return $this->setData(self::WALLET_ID, $walletId);
    }

    /**
     * Get website_id
     * @return string|null
     */
    public function getWebsiteId()
    {
        return $this->_get(self::WEBSITE_ID);
    }

    /**
     * Set website_id
     * @param string $websiteId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setWebsiteId($websiteId)
    {
        return $this->setData(self::WEBSITE_ID, $websiteId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \MagePrakash\Wallet\Api\Data\WalletExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \MagePrakash\Wallet\Api\Data\WalletExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MagePrakash\Wallet\Api\Data\WalletExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get current_balance
     * @return string|null
     */
    public function getCurrentBalance()
    {
        return $this->_get(self::CURRENT_BALANCE);
    }

    /**
     * Set current_balance
     * @param string $currentBalance
     * @return \MagePrakash\Wallet\Api\Data\WalletInterface
     */
    public function setCurrentBalance($currentBalance)
    {
        return $this->setData(self::CURRENT_BALANCE, $currentBalance);
    }
}
