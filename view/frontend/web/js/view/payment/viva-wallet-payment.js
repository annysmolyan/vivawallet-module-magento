//* NOTICE OF LICENSE
//*
//* This source file is subject to the GNU General Public License v3 (GPL 3.0)
//* that is bundled with this package in the file LICENSE.txt.
//* It is also available through the world-wide-web at this URL:
//* https://www.gnu.org/licenses/gpl-3.0.en.html
//*
//* @category Smolyan
//* @package Smolyan_VivaWallet
//* @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)
//*/

// Payment method registration here
// type - name of method which is defined in checkout_index_index inside methods node

define([
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ], function (Component, rendererList) {
        'use strict';

        rendererList.push(
            {
                type: 'viva_wallet',
                component: 'Smolyan_VivaWallet/js/view/payment/method-renderer/viva-wallet-method'
            },
        );

        return Component.extend({});
    }
);
