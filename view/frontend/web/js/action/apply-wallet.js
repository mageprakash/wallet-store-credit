/*
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/url-builder',
    'Magento_Checkout/js/model/error-processor',
    'mage/storage',
    'Magento_Ui/js/model/messageList',
    'mage/translate',
    'Magento_Checkout/js/model/full-screen-loader',
    'Magento_Checkout/js/action/get-payment-information',
    'Magento_Checkout/js/model/totals',
    'Magento_Checkout/js/model/cart/totals-processor/default',
    'Magento_Checkout/js/model/cart/cache'
], function (
    $,
    ko,
    quote,
    urlBuilder,
    errorProcessor,
    storage,
    messageList,
    $t,
    fullScreenLoader,
    getPaymentInformationAction,
    totals,
    defaultTotal,
    cartCache
) {
    'use strict';

    return function (amount) {
        var result = $.Deferred();
        var message = $t('Your Wallet was successfully applied');

        messageList.clear();
        fullScreenLoader.startLoader();

        storage.post(
            urlBuilder.createUrl('/carts/mine/wallet/applyy', {}),
            JSON.stringify({'amount': amount})
        ).done(function (response) {
            var deferred;

            if (response) {
                deferred = $.Deferred();
                totals.isLoading(true);
                getPaymentInformationAction(deferred);
                $.when(deferred).done(function () {
                    totals.isLoading(false);
                });
                
                cartCache.set('totals',null);
                defaultTotal.estimateTotals();

                messageList.addSuccessMessage({
                    'message': message
                });
                result.resolve(response);
            }
        }).fail(function (response) {
            totals.isLoading(false);
            errorProcessor.process(response);
            result.fail();
        }).always(function () {
            fullScreenLoader.stopLoader();
        });

        return result.promise();
    };
});
