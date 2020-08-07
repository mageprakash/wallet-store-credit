<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Model\Total\Quote;

use Magento\Customer\Model\Address\AbstractAddress;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

class Wallet extends AbstractTotal
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;

    /**
     * @var ConfigProvider
     */
/*    private $configProvider;*/

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    private $walletRepository;
    private $walletData;

    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \MagePrakash\Wallet\Api\WalletRepositoryInterface $walletRepository,
        \Magento\Framework\App\RequestInterface $request,
        \MagePrakash\Wallet\Helper\Data $walletData
    ) {
        $this->setCode('wallet');
        $this->request = $request;
        $this->walletData = $walletData;
        $this->priceCurrency = $priceCurrency;
        $this->walletRepository = $walletRepository;
    }

    /**
     * @inheritdoc
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        if ($this->walletData->isEnabled() && $shippingAssignment->getShipping()->getAddress()->getAddressType() == AbstractAddress::TYPE_SHIPPING
            && $quote->getCustomerId()
            && $quote->getBaseToQuoteRate()
            && $total->getGrandTotal() > 0
        ) {

            $baseWallet = $wallet = 0;

            if ($quote->getCustomer()->getId()) {
                $baseWallet = $this->walletRepository->loadByCustomerId($quote->getCustomerId())->getCurrentBalance();
                $wallet = $this->priceCurrency->convert($baseWallet, $quote->getStore());
            }
            if ($quote->isVirtual() || !$wallet) {
                return $this;
            }

            $baseTotalUsed = $totalUsed = $baseUsed = $used = 0;

            if ($quote->getWalletUse()) 
            {
                if ($quote->getWalletBaseAmount() >= $total->getBaseGrandTotal())
                {
                    $baseUsed = $total->getBaseGrandTotal();
                    $used     = $total->getGrandTotal();
                    $total->setBaseGrandTotal(0);
                    $total->setGrandTotal(0);
                } else {

                    $baseUsed = $quote->getWalletBaseAmount();
                    $used     = $quote->getWalletAmount();
                    $total->setBaseGrandTotal($total->getBaseGrandTotal() - $baseUsed);
                    $total->setGrandTotal($total->getGrandTotal() - $used);
                }

                $quote->setWalletAmount($used);
                $quote->setWalletBaseAmount($baseUsed);
                $total->setWalletAmount($used);
                $total->setWalletBaseAmount($baseUsed);
            }else{

                $quote->setWalletAmount(0);
                $quote->setWalletBaseAmount(0);
                $total->setWalletAmount(0);
                $total->setWalletBaseAmount(0);
            }

        }
        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        if ($this->walletData->isEnabled()) {
            if ($quote->getWalletUse()) {
                return [
                    'code' => $this->getCode(),
                    'title' => __($this->walletData->getTitle()),
                    'value' => -$quote->getWalletAmount()
                ];
            } else {
                return [
                    'code' => $this->getCode(),
                    'title' => __($this->walletData->getTitle()),
                    'value' => $quote->getWalletAmount()
                ];
            }
        }
        return null;
    }
}
