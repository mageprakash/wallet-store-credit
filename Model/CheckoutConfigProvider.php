<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Model;


use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\ConfigProviderInterface;
use MagePrakash\Wallet\Helper\Data;

class CheckoutConfigProvider implements ConfigProviderInterface
{

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StoreManagerInterface
     */
    private $walletData;

    public function __construct(
        Session $customerSession,
        CheckoutSession $checkoutSession,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        WalletRepository $walletRepository,
        Data $walletData
    ) {
        $this->customerSession = $customerSession;
        $this->walletRepository = $walletRepository;
        $this->quote = $checkoutSession->getQuote();
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->walletData = $walletData;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $result = [];
        $balance = ($this->customerSession->isLoggedIn())
            ? $this->priceCurrency->convertAndRound(
                $this->walletRepository->loadByCustomerId($this->customerSession->getCustomerId())
                    ->getCurrentBalance()
            )
            : 0;

        $applyBalance = 0;
        $result['mageprakashsWallet'] = [
            'isVisible'                 => $this->walletData->isEnabled(),
            'walletUsed'                => (bool)$this->quote->getWalletUse(),
            'walletAmount'              => ($this->quote->getWalletAmount())?$this->quote->getWalletAmount():$applyBalance,
            'walletAmountAvailable'     => $balance,
            'walletTitle'               => $this->walletData->getTitle(),
            'checkoutTabTitle'          => $this->walletData->getCheckoutTabTitle(),
            'checkoutTitle'             => $this->walletData->getCheckoutTitle(),
            'checkoutInputPlaceholder'  => $this->walletData->getCheckoutInputPlaceholder(),
            'checkoutAppliedTitle'  => $this->walletData->getCheckoutAppliedTitle()
        ];

        return $result;
    }
}
