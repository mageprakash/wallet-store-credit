<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Block\Adminhtml;


use Magento\Backend\Block\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\AdminOrder\Create;

class WalletBalanceBillingMethod extends Template
{

    /**
     * @var Create
     */
    private $orderCreate;

    /**
     * @var int
     */
    private $customerId;

    /**
     * @var PriceCurrencyInterface
     */
    private $currency;

    public function __construct(
        Template\Context $context,
        Create $orderCreate,
        PriceCurrencyInterface $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderCreate = $orderCreate;
        $this->currency = $currency;
    }

    public function isUsedWalletBalance()
    {
        return (bool)$this->orderCreate->getQuote()->getData('wallet_use');
    }

    public function getCurrentWalletBalance()
    {
        return $this->orderCreate->getQuote()->getWalletAmount();
    }

    public function getCurrencySymbol()
    {
        if ($symbol = $this->currency->getCurrency(
            null,
                $this->orderCreate->getQuote()->getQuoteCurrencyCode()
            )->getCurrencySymbol()
        ) {
            return $symbol;
        } else {
            return $this->orderCreate->getQuote()->getQuoteCurrencyCode();
        }
    }

    public function getCustomerId()
    {
        if ($this->customerId === null) {
            $this->customerId = $this->orderCreate->getQuote()->getCustomerId();
        }

        return $this->customerId;
    }

    public function canUseWalletBalance()
    {
        return $this->getCustomerId() && $this->orderCreate->getQuote()->getWalletAmount() !== null;
    }
}
