<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Block\Adminhtml\Edit\Tab\View\Renderer;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Backend\Block\Context;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Input
{
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->priceCurrency = $priceCurrency;
    }

    public function render(\Magento\Framework\DataObject $row)
    {
        $actionsSmall = [
            1  => 'Changed By Admin',
            2  => 'Changed By Admin',
            3  => 'Refunded',
            4  => 'Order Paid',
            5  => 'Order Canceled'
        ];
        
        return  $actionsSmall[$row->getData('action')];
    }
}
