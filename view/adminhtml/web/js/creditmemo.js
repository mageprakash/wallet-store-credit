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
                $(document).on('click', config.checkbox, this.updateState.bind(this, config.checkbox));
                $(document).on('click', config.button, this.updateWalletBalance.bind(this, config.checkbox));
                $(document).on('blur', this.element, this.checkUpdateButton.bind(this));
                $('#edit_form').submit(function () {
                    if ($('[name="mpwallet_new"]').length) {
                        return true;
                    }
                    $(this.element).append('<input type="hidden" name="mpwallet_new" value="1" />');
                    $('#edit_form').submit();
                    return false;
                }.bind(this));
            },
            updateState: function (checkbox) {
                $(this.element).attr('disabled', !$(checkbox).prop('checked'));
                this.updateCreditMemo();
            },
            updateCreditMemo: function () {
                $('.update-button').attr('disabled', false).click();
            },
            updateWalletBalance: function () {
                $(this.element).prepend('<input type="hidden" name="mpwallet_new" value="1" />');
                this.updateCreditMemo();
                $('[name="mpwallet_new"]').replaceWith('');
            },
            checkUpdateButton: function () {
                if ($(this.element).val() !== $('#current_wallet_balance').val()) {
                    $(this.button).attr('disabled', false);
                }
            }
        });
    }
);
