<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */


namespace MagePrakash\Wallet\Observer;

class ManageWalletBalance implements \Magento\Framework\Event\ObserverInterface
{
    private $balanceManagement;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

  
    private $walletRepository;


    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \MagePrakash\Wallet\Helper\Data
     */
    private $walletData;

    public function __construct(
        \MagePrakash\Wallet\Model\BalanceManagement $balanceManagement,
        \Magento\Framework\AuthorizationInterface $authorization,
        \MagePrakash\Wallet\Model\WalletRepository $walletRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MagePrakash\Wallet\Helper\Data $walletData
    ) {
        $this->balanceManagement = $balanceManagement;
        $this->authorization = $authorization;
        $this->walletRepository = $walletRepository;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->walletData = $walletData;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->walletData->isEnabled()) {
            $request = $observer->getData('request');
            /** @var \Magento\Customer\Api\Data\CustomerInterface $customer */
            $customer = $observer->getData('customer');
            if ($addOrSubtract = $request->getParam('add_balace')) {
                $addOrSubtract = $this->priceCurrency->round(
                    $addOrSubtract / $this->storeManager->getStore()->getBaseCurrency()->getRate(
                        $this->storeManager->getStore()->getCurrentCurrencyCode()
                    )
                );
                $currentAmount = $this->walletRepository->loadByCustomerId($customer->getId())->getCurrentBalance();
                if (($currentAmount + $addOrSubtract) < 0) {
                    $addOrSubtract = -$currentAmount;
                }

                
                $this->balanceManagement->addOrSubtractWalletBalance(
                    $customer->getId(),
                    $addOrSubtract,
                    (
                        $addOrSubtract < 0 ? 2 : 1
                    ),
                    [$addOrSubtract < 0
                            ? __('Administrator removed %1 balance from your wallet',$addOrSubtract)
                            : __('Administrator added %1 balance to your wallet',$addOrSubtract)],
                    null,
                    $request->getParam('comment', '')
                );
            }
        }
    }
}
