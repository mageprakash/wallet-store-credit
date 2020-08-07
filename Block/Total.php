<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Block;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use MagePrakash\Wallet\Helper\Data;

class Total extends Template
{

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \MagePrakash\Wallet\Helper\Data
     */
    private $walletData;

    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        Template\Context $context,
        Data $walletData,
        array $data = []
    ) {
        $this->walletData = $walletData;
        parent::__construct($context, $data);
        $this->setInitialFields();
        $this->priceCurrency = $priceCurrency;
        
    }

    public function initTotals()
    {
        if (!empty($this->getParentBlock()->getSource()->getData($this->getAmountField()))) {
            $this->getParentBlock()->addTotal(
                new \Magento\Framework\DataObject(
                    [
                        'code' => 'wallet',
                        'strong' => $this->getStrong(),
                        'value' => ($this->getMinus() ? -1 : 1)
                            * $this->getParentBlock()->getSource()->getData($this->getAmountField()),
                        'base_value' => ($this->getMinus() ? -1 : 1)
                            * $this->getParentBlock()->getSource()->getData($this->getBaseAmountField()),
                        'label' => __($this->getLabel()),
                    ]
                ),
                $this->getAfter()
            );
        }

        return $this;
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
          
            $this->setLabel(__($this->walletData->getTitle()));
        }

        if ($this->getMinus() === null) {
            $this->setMinus(true);
        }

        if ($this->getStrong() === null) {
            $this->setStrong(false);
        }
    }
}
