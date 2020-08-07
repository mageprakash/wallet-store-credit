/*
 * *
 *  * @author MagePrakash Wallet
 *  * @copyright Copyright (c) 2020 MagePrakash (https://www.mageprakash.com)
 *  * @package MagePrakash_Wallet
 *
 */

define(
    [
        'uiClass',
        'jquery'
    ],
    function (Class, $) {
        'use strict';

        return Class.extend({
            element: null,
            checkbox: null,
            button: null,

            initialize: function (config, el) {
                this.element = '#' + $(el).attr('id');
                this.checkbox = config.checkbox;
                this.button = config.button;
                $(document).off('click', this.checkbox);
                $(document).on('click', this.checkbox,this.updateState.bind(this, config.checkbox));

                $(document).off('click', config.button);
                $(document).on('click', config.button, this.updatePayment.bind(this));


                $(document).off('blur', this.element);
                $(document).on('blur', this.element, this.checkUpdateButton.bind(this));
            },
            updateState: function (checkbox) {
                $(this.element).attr('disabled', !$(checkbox).prop('checked'));
                this.updatePayment(true);
            },
            updatePayment: function (calcAmount) {
                //$(document).off('click', this.checkbox);
                var params = {};
                params['mp_use_wallet'] = +!$(this.element).attr('disabled');
                params['mp_wallet_amount'] = $(this.element).val();
                if (typeof calcAmount === 'undefined') {
                    params['mp_walle_calc_amount'] = 1;
                }

                window.order.loadArea(['totals', 'billing_method'], true, params);
            },
            checkUpdateButton: function () {
                if ($(this.element).val() !== $('#current_wallet_balance').val()) {
                    $(this.button).attr('disabled', false);
                }
            }
        });
    }
);
