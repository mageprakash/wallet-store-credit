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

class AdditionalInfo extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Input
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

        $actionsFull = [
            1 => 'Administrator added %1 balance to your wallet',
            2 => 'Administrator removed %1 balance from your wallet',
            3 => 'You order #%1 was refunded on %2',
            4 => 'Order #%1 was payed with %2 wallet',
            5 => 'Order #%1 was canceled. Returned %2 wallet',
        ];

        $additional_info = $row->getData('additional_info');
        $additional_info = json_decode($additional_info);
        
        $text            = $actionsFull[$row->getData('action')];
        if($row->getData('action') == 3 || $row->getData('action') == 4 || $row->getData('action') == 5){
            $text  = __($text,...[$additional_info[0],$this->priceCurrency->convertAndFormat($row->getData('balance_difference'),false,
                                2)]);
        }else{
             $text = __($text,$this->priceCurrency->convertAndFormat($row->getData('balance_difference'),false,
                                2));
        }

        return $text;
    }
}
