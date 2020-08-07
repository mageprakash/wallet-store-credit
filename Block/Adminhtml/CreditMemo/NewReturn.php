<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Block\Adminhtml\CreditMemo;

use Magento\Backend\Block\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Registry;

class NewReturn extends Template
{
    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @var PriceCurrencyInterface
     */
    private $currency;

    /**
     * @var ConfigProvider
     */
   // private $configProvider;

    public function __construct(
        Registry $coreRegistry,
        Template\Context $context,
        PriceCurrencyInterface $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->coreRegistry = $coreRegistry;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $memo = $this->coreRegistry->registry('current_creditmemo');
        if (!($memo && $memo->getCustomerId())
        ) {
            return '';
        }

        return parent::toHtml();
    }

    /**
     * @return float|int
     */
    public function getMaxWalletBalance()
    {
        if ($this->getReturnValue()) {
            return $this->currency->round($this->getReturnValue());
        }
        return 0;
    }

    public function getReturnValue()
    {
        $creditMemo = $this->coreRegistry->registry('current_creditmemo');

        $bsCustomerBalTotalRefunded = $creditMemo->getWalletBaseAmount();
        $customerBalance = !empty($bsCustomerBalTotalRefunded)
            ? $bsCustomerBalTotalRefunded
            : $creditMemo->getBaseCustomerBalanceReturnMax();
        return $creditMemo->getBaseCustomerBalanceReturnMax();
    }
}
