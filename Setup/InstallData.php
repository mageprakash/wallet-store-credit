<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    private $quoteSetupFactory;
    private $salesSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Sales\Setup\SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->salesSetupFactory = $salesSetupFactory;

        $this->quoteSetupFactory = $quoteSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_use',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_invoiced_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_invoiced_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_refunded_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('order', 'wallet_refunded_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('invoice', 'wallet_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('invoice', 'wallet_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('creditmemo', 'wallet_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);
        $salesSetup->addAttribute('creditmemo', 'wallet_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $quoteSetup->addAttribute('quote', 'wallet_use',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $quoteSetup->addAttribute('quote', 'wallet_base_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );

        $quoteSetup = $this->quoteSetupFactory->create(['setup' => $setup]);
        $quoteSetup->addAttribute('quote', 'wallet_amount',
            [
                'type' => 'varchar',
                'length' => 255,
                'visible' => false,
                'required' => false,
                'grid' => false
            ]
        );
    }
}
