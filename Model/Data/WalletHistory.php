<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model\Data;

use MagePrakash\Wallet\Api\Data\WalletHistoryInterface;

class WalletHistory extends \Magento\Framework\Api\AbstractExtensibleObject implements WalletHistoryInterface
{

    /**
     * Get wallet_history_id
     * @return string|null
     */
    public function getWalletHistoryId()
    {
        return $this->_get(self::WALLET_HISTORY_ID);
    }

    /**
     * Set wallet_history_id
     * @param string $walletHistoryId
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setWalletHistoryId($walletHistoryId)
    {
        return $this->setData(self::WALLET_HISTORY_ID, $walletHistoryId);
    }

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
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setWalletId($walletId)
    {
        return $this->setData(self::WALLET_ID, $walletId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \MagePrakash\Wallet\Api\Data\WalletHistoryExtensionInterface $extensionAttributes
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
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get balance_amount
     * @return string|null
     */
    public function getBalanceAmount()
    {
        return $this->_get(self::BALANCE_AMOUNT);
    }

    /**
     * Set balance_amount
     * @param string $balanceAmount
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setBalanceAmount($balanceAmount)
    {
        return $this->setData(self::BALANCE_AMOUNT, $balanceAmount);
    }

    /**
     * Get balance_difference
     * @return string|null
     */
    public function getBalanceDifference()
    {
        return $this->_get(self::BALANCE_DIFFERENCE);
    }

    /**
     * Set balance_difference
     * @param string $balanceDifference
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setBalanceDifference($balanceDifference)
    {
        return $this->setData(self::BALANCE_DIFFERENCE, $balanceDifference);
    }

    /**
     * Get action
     * @return string|null
     */
    public function getAction()
    {
        return $this->_get(self::ACTION);
    }

    /**
     * Set action
     * @param string $action
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setAction($action)
    {
        return $this->setData(self::ACTION, $action);
    }

    /**
     * Get additional_info
     * @return string|null
     */
    public function getAdditionalInfo()
    {
        return $this->_get(self::ADDITIONAL_INFO);
    }

    /**
     * Set additional_info
     * @param string $additionalInfo
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setAdditionalInfo($additionalInfo)
    {
        return $this->setData(self::ADDITIONAL_INFO, $additionalInfo);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \MagePrakash\Wallet\Api\Data\WalletHistoryInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
