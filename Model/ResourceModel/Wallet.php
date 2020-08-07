<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Model\ResourceModel;

class Wallet extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageprakash_wallet_wallet', 'wallet_id');
    }

    public function loadByCustomerId(\MagePrakash\Wallet\Model\Wallet $wallet, $customerId,$WebsiteId)
    {
        $connection = $this->getConnection();
        $bind = ['customer_id' => $customerId];
        $select = $connection->select()->from(
            'mageprakash_wallet_wallet',
            ['wallet_id']
        )->where(
            'customer_id = :customer_id'
        );
		$bind['website_id'] = (int)$WebsiteId;
		$select->where('website_id = :website_id');

        $walletId = $connection->fetchOne($select, $bind);

        if ($walletId) {
            $this->load($wallet, $walletId);
        } else {
            $wallet->setData([]);
        }

        return $this;
    }
}
