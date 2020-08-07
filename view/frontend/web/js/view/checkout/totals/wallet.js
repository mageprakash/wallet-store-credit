/*
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals',
    'uiRegistry'
], function (Component, totals, registry) {
    'use strict';

    return Component.extend({
        defaults: {
            walletTitle  : window.checkoutConfig.mageprakashsWallet.walletTitle,
            template: 'MagePrakash_Wallet/checkout/totals/wallet'
        },
        totals: totals.totals(),

        /**
         * Used balance without any formatting
         *
         * @return {Number}
         */
        getWalletValueFormat: function () {
            return this.getFormattedPrice(this.getWalletValue());
        },

        /**
         * Used balance with currency sign and localization
         *
         * @return {String}
         */
        getWalletValue: function () {
            var price = 0;
            if (this.totals) {
                if (totals.getSegment('wallet').value) {
                    price = totals.getSegment('wallet').value;
                }
            }
            return price;
        },

        /**
         * Availability status
         *
         * @returns {Boolean}
         */
        isAvailable: function () {
            return this.isFullMode() && this.getWalletValue() != 0;
        },
        
        getWalletTitle: function () {
            return this.walletTitle;
        }
    });
});
