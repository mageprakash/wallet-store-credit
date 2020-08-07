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

class BalanceDifference extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Input
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
        $difference = $this->priceCurrency->convertAndFormat(
            $row->getData('balance_difference'),
            false,
            2
        );

        if ($row->getData('is_deduct')) {
            $difference = '<span class="price" style="color:red">-' . $difference . '</span>';
        } else {
            $difference = '<span class="price" style="color:green">+' . $difference . '</span>';
        }
//         $difference = '<span class="price" style="color:green">+' . $difference . '</span>';
        return $difference;
    }
}
