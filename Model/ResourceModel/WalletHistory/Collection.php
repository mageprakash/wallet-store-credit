<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model\ResourceModel\WalletHistory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \MagePrakash\Wallet\Model\WalletHistory::class,
            \MagePrakash\Wallet\Model\ResourceModel\WalletHistory::class
        );
    }

    public function setDashboarFilters($customerId)
    {
        $this->addFieldToFilter('main_table.customer_id', $customerId);
    }
}
