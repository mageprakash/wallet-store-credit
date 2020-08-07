<?php
/**
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

namespace MagePrakash\Wallet\Helper;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const WALLET_GENERAL_ENABLED = 'wallet/general/is_enabled';

    const WALLET_GENERAL_TITLE = 'wallet/general/title';

    const WALLET_CUSTOMER_NOTIFICATION_ENABLE   = 'wallet/email/is_cn_enabled';

    const WALLET_CUSTOMER_EMAIL_TEMPLATE  = 'wallet/email/customer_notification_template';

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::WALLET_GENERAL_ENABLED, ScopeInterface::SCOPE_STORE) == 1;
    }

    public function getTitle()
    {
        return $this->scopeConfig->getValue(self::WALLET_GENERAL_TITLE, ScopeInterface::SCOPE_STORE);
    }

    public function isEmailEnabled()
    {
        return $this->scopeConfig->getValue(self::WALLET_CUSTOMER_NOTIFICATION_ENABLE, ScopeInterface::SCOPE_STORE) == 1;
    }

    public function getEmailTemplate()
    {
        return $this->scopeConfig->getValue(self::WALLET_CUSTOMER_EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE);
    }

    public function getStorename()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getStoreEmail()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getXmlTitle()
    {
        return __($this->getTitle().' %1',"Total Refunded");
    }

    public function getMessage()
    {
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getCheckoutTabTitle(){
        return __("Apply %1",$this->getTitle());
    }

    public function getCheckoutTitle(){
        return __("You have  %s  on your  %1 account",$this->getTitle());
    }

    public function getCheckoutInputPlaceholder(){
        return __("Enter %1",$this->getTitle());
    }

    public function getCheckoutAppliedTitle(){
        return __("Applied %1  amount is %s",$this->getTitle());
    }

    public function getCustomerAccountTabTitle(){
        return __($this->getTitle());
    }
}