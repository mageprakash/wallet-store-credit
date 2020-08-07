<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_mageprakash_wallet_wallet = $setup->getConnection()->newTable($setup->getTable('mageprakash_wallet_wallet'));

        $table_mageprakash_wallet_wallet->addColumn(
            'wallet_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_mageprakash_wallet_wallet->addColumn(
            'website_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'website_id'
        );

        $table_mageprakash_wallet_wallet->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'customer_id'
        );

        $table_mageprakash_wallet_wallet->addColumn(
            'current_balance',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'current_balance'
        );

        $table_mageprakash_wallet_wallet_history = $setup->getConnection()->newTable($setup->getTable('mageprakash_wallet_wallet_history'));

        $table_mageprakash_wallet_wallet_history->addColumn(
            'wallet_history_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'wallet_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'wallet_id'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'customer_id'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'balance_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'balance_amount'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'balance_difference',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            [],
            'balance_difference'
        );
        
        $table_mageprakash_wallet_wallet_history->addColumn(
            'is_deduct',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [],
            'is_deduct'
        );
        
        $table_mageprakash_wallet_wallet_history->addColumn(
            'action',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            '12,4',
            [],
            'action'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'additional_info',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'additional_info'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'comment',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'comment'
        );

        $table_mageprakash_wallet_wallet_history->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [],
            'created_at'
        );

        $setup->getConnection()->createTable($table_mageprakash_wallet_wallet_history);

        $setup->getConnection()->createTable($table_mageprakash_wallet_wallet);
    }
}
