//* NOTICE OF LICENSE
//*
//* This source file is subject to the GNU General Public License v3 (GPL 3.0)
//* that is bundled with this package in the file LICENSE.txt.
//* It is also available through the world-wide-web at this URL:
//* https://www.gnu.org/licenses/gpl-3.0.en.html
//*
//* @category BelSmol
//* @package BelSmol_VivaWallet
//* @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)

// This file handles the frontend logic specific to the new payment method:
// validation on the form fields and accessor methods for the Knockout template data and so on.

define([
    'jquery',
    'Magento_Payment/js/view/payment/cc-form',
    'Magento_Payment/js/model/credit-card-validation/credit-card-data',
    'Magento_Vault/js/view/payment/vault-enabler',
    'Magento_Payment/js/model/credit-card-validation/validator',
], function ($, Component, creditCardData, VaultEnabler) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'BelSmol_VivaWallet/payment/viva-wallet',
            creditCardType: '',
            creditCardExpYear: '',
            creditCardExpMonth: '',
            creditCardNumber: '',
            creditCardSsStartMonth: '',
            creditCardSsStartYear: '',
            creditCardSsIssue: '',
            creditCardVerificationNumber: '',
            creditCardOwner: '',
            selectedCardType: null,
            redirectAfterPlaceOrder: false
        },

        /** @inheritdoc */
        initObservable: function () {
            this._super()
                .observe([
                    'creditCardType',
                    'creditCardExpYear',
                    'creditCardExpMonth',
                    'creditCardNumber',
                    'creditCardVerificationNumber',
                    'creditCardSsStartMonth',
                    'creditCardSsStartYear',
                    'creditCardSsIssue',
                    'creditCardOwner',
                    'selectedCardType'
                ]);

            return this;
        },

        /**
         * Init component
         */
        initialize: function () {
            this._super();

            this.vaultEnabler = new VaultEnabler();
            this.vaultEnabler.setPaymentCode(this.getVaultCode());

            //Set card owner to credit card data object
            this.creditCardOwner.subscribe(function (value) {
                creditCardData.creditCardOwner = value;
            });
        },

        /**
         * @returns {Boolean}
         */
        isVaultEnabled: function () {
            return this.vaultEnabler.isVaultEnabled();
        },

        /**
         * Returns vault code.
         *
         * @returns {String}
         */
        getVaultCode: function () {
            return window.checkoutConfig.payment[this.getCode()].ccVaultCode;
        },

        /**
         * Get data
         * @returns {Object}
         */
        getData: function () {
            var data =  {
                'method': this.item.method,
                'additional_data': {
                    'cc_cid': this.creditCardVerificationNumber(),
                    'cc_ss_start_month': this.creditCardSsStartMonth(),
                    'cc_ss_start_year': this.creditCardSsStartYear(),
                    'cc_ss_issue': this.creditCardSsIssue(),
                    'cc_type': this.creditCardType(),
                    'cc_exp_year': this.creditCardExpYear(),
                    'cc_exp_month': this.creditCardExpMonth(),
                    'cc_number': this.creditCardNumber(),
                    'cc_owner': this.creditCardOwner()
                }
            };
            this.vaultEnabler.visitAdditionalData(data);
            return data;
        },

        /**
         * Get payment code
         * @returns {String}
         */
        getCode: function() {
            return window.checkoutConfig.payment.viva_wallet.code;
        },

        /**
         * Get payment status
         * @returns {Boolean}
         */
        isActive: function() {
            return window.checkoutConfig.payment.viva_wallet.isActive;
        },

        /**
         * Get payment title
         * @returns {String}
         */
        getTitle: function() {
            return window.checkoutConfig.payment.viva_wallet.title;
        },

        /**
         * Get list of available credit card types
         * @returns {Object}
         */
        getCcAvailableTypes: function () {
            return window.checkoutConfig.payment.viva_wallet.availableTypes[this.getCode()];
        },

        /**
         * Get list of months
         * @returns {Object}
         */
        getCcMonths: function () {
            return window.checkoutConfig.payment.viva_wallet.months[this.getCode()];
        },

        /**
         * Get list of years
         * @returns {Object}
         */
        getCcYears: function () {
            return window.checkoutConfig.payment.viva_wallet.years[this.getCode()];
        },

        /**
         * Get access token
         * @returns {string}
         */
        getAccessToken: function() {
            return window.checkoutConfig.payment.viva_wallet.accessToken;
        },

        /**
         * Get charge url
         * @returns {string}
         */
        getChargeUrl: function() {
            return window.checkoutConfig.payment.viva_wallet.chargeUrl;
        },

        /**
         * Check if current payment has verification
         * @returns {Boolean}
         */
        hasVerification: function () {
            return window.checkoutConfig.payment.viva_wallet.hasVerification[this.getCode()];
        },

        /**
         * Validate payment form
         * @returns {Boolean}
         */
        validate: function() {
            var $form = $('#' + this.getCode() + '-form');
            return $form.validation() && $form.validation('isValid');
        },

        /**
         * After place order callback
         */
        afterPlaceOrder: function () {
            event.preventDefault();
            $('body').trigger('processStart'); // display loader
            try {
                $.ajax({
                    type: "POST",
                    url: this.getChargeUrl(),
                    success: function (response) {
                        $('body').trigger('processStop'); // stop loader
                        if (response.redirectToACSForm) {
                            $('body').append(response.redirectToACSForm);
                        } else {
                            $('body').trigger('processStop'); // stop loader
                            this._resolveError();
                        }
                    }.bind(this),
                    error: function (response) {
                        $('body').trigger('processStop'); // stop loader
                        this._resolveError();
                    }.bind(this),
                })
            } catch (Error) {
                this._resolveError();
            }
        },

        /**
         * Redirect user to payment form
         * @private
         */
        _redirectToPayment: function(response) {
            if (response.redirectToACSForm) {
                $('body').append(data.redirectToACSForm);
            } else {
               this._resolveError();
            }
        },

        /**
         * Resolve error during authorize
         * @private
         */
        _resolveError: function() {
            this.displayErrorMsg();
            window.location.href = '/sales/order/history/';
        },

        /**
         * Display error message
         */
        displayErrorMsg: function () {
            window.scrollTo(0, 0); //scroll to the top of page, because user can't see notifications
            alert('Your order was created, but payment failed. Please, try again or liaise with us for details.');
        }
    });
});
