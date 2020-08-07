/*
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Catalog/js/price-utils',
    'MagePrakash_Wallet/js/action/apply-wallet',
    'MagePrakash_Wallet/js/action/cancel-wallet',
    'underscore'
], function ($, Component, quote, customer, priceUtils, applyWallet, cancelWallet, _) {
    'use strict';

    return Component.extend({
        defaults: {
            template  : 'MagePrakash_Wallet/checkout/payment/wallet',
            isVisible : window.checkoutConfig.mageprakashsWallet.isVisible,
            available : window.checkoutConfig.mageprakashsWallet.walletAmountAvailable,
            amount    : window.checkoutConfig.mageprakashsWallet.walletAmount,
            walletTitle  : window.checkoutConfig.mageprakashsWallet.walletTitle,
            checkoutTabTitle  : window.checkoutConfig.mageprakashsWallet.checkoutTabTitle,
            checkoutTitle  : window.checkoutConfig.mageprakashsWallet.checkoutTitle,
            checkoutAppliedTitle  : window.checkoutConfig.mageprakashsWallet.checkoutAppliedTitle,
            appliedAmount: 0,
            isApplied: window.checkoutConfig.mageprakashsWallet.walletUsed
        },

        initObservable: function () {
            if (this.isApplied) {
                this.available = this.available - this.amount;
            }

            this.appliedAmount = parseFloat(this.amount);
            var priceFormat = _.clone(quote.getPriceFormat());
            priceFormat.pattern = '%s';
            this.amount = priceUtils.formatPrice(this.amount,  priceFormat, false);

            if(this.amount <=0){
                if(this.available > quote.totals().base_grand_total){
                    this.amount = quote.totals().base_grand_total;
                    console.log(quote.totals());                    
                }else{
                    this.amount = this.available;
                }
            }
        
            this._super();
            this.observe(['isVisible', 'available', 'isApplied', 'amount']);

            return this;
        },
        getTabTitle: function (){
            return this.checkoutTabTitle;
        },
        getTitle: function (){
            return this.checkoutTitle.replace("%s", this.getWalletLeft());
        },
        getAppliedTitle: function (){
            return this.checkoutAppliedTitle.replace("%s", this.getFormatAmount());
        },
        inputPlaceholder: function (){
            return this.checkoutInputPlaceholder;
        },
        getFormatAmount: function () {
            return priceUtils.formatPrice(this.amount(),  quote.getPriceFormat(), false);
        },
        getWalletLeft: function () {
            return priceUtils.formatPrice(this.available(),  quote.getPriceFormat(), false);
        },
        applyWallet: function () {
            applyWallet(this.amount())
                .done(function (response) {
                    this.available(this.available() - parseFloat(response));
                    this.amount(response);
                    this.appliedAmount = this.amount();
                    this.isApplied(true);
                }.bind(this))
                .fail(function () {

                });
        },
        cancelWallet: function () {
            cancelWallet()
                .done(function (response) {
                    this.available(this.available() + this.appliedAmount);
                    //this.appliedAmount(0);
                    this.amount(this.amount());
                    this.isApplied(false);
                }.bind(this));
        }
    });
});
