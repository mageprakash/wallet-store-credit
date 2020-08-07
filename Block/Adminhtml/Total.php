<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Block\Adminhtml;
use MagePrakash\Wallet\Helper\Data;

class Total extends \Magento\Sales\Block\Adminhtml\Order\Totals\Item
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        Data $walletData,
        array $data = []
    ) {
        $this->walletData = $walletData;
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->priceCurrency = $priceCurrency;
        $this->setInitialFields();
    }

    protected function _initTotals()
    {
        parent::_initTotals();

        $this->addTotal(
            new \Magento\Framework\DataObject(
                [
                    'code' => 'wallet',
                    'strong' => $this->getStrong(),
                    'value' => $this->getSource()->getData($this->getAmountField()),
                    'base_value' => $this->getSource()->getData($this->getBaseAmountField()),
                    'label' => __($this->getLabel()),
                ]
            ),
            $this->getAfter()
        );

        return $this;
    }

    public function getWalletBalance()
    {
        return $this->getSource()->getData('wallet_amount');
    }

    public function getFormatWalletBalance()
    {
        $source = $this->getSource();
        $result = $this->priceCurrency->format(
            ($this->getMinus() ? -1 : 1) * $source->getData($this->getBaseAmountField()),
            null,
            null,
            null,
            $source->getBaseCurrencyCode()
        );
        if ($this->getStrong()) {
            $result = '<strong>' . $result . '</strong>';
        }
        if ($source->getBaseCurrencyCode() !== $source->getOrderCurrencyCode()) {
            $result .= '<br>[<span class="price">'
                . $this->priceCurrency->format(
                    ($this->getMinus() ? -1 : 1) * $source->getData($this->getAmountField()),
                    true,
                    2,
                    null,
                    $source->getOrderCurrencyCode()
                )
                . ']</span>';
        }

        return $result;
    }

    public function getTitle(){
        return __($this->walletData->getTitle());
    }
    

    public function setInitialFields()
    {
        if (!$this->getAmountField()) {
            $this->setAmountField('wallet_amount');
        }

        if (!$this->getBaseAmountField()) {
            $this->setBaseAmountField('wallet_base_amount');
        }

       if (!$this->getLabel()) {
            $this->setLabel($this->getTitle());
        }


        if ($this->getMinus() === null) {
            $this->setMinus(true);
        }

        if ($this->getStrong() === null) {
            $this->setStrong(false);
        }
    }
}
