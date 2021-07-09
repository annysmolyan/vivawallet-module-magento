<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3 (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @category Smolyan
 * @package Smolyan_VivaWallet
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)
 */

namespace Smolyan\VivaWallet\Block\Adminhtml\System\Config\Payment;

use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class VivaWalletFieldset
 * Display viva wallet fieldset in magento admin panel
 * in Configuration -> Sales -> Payment methods
 * @package Smolyan\VivaWallet\Block\Adminhtml\System\Config\Payment
 */
class VivaWalletFieldset extends Fieldset
{
    /**
     * @OVERRIDE
     * Return header title part of html for payment solution
     * changed basic header template
     * WARNING: can't use type hint here, because of magento errors
     *
     * @param AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHeaderTitleHtml($element): string
    {
        $htmlId = $element->getHtmlId();

        $html = '<div class="config-heading" >';
        $html .= '<div class="button-container">' .
            '<button type="button" class="button action-configure" id="' . $htmlId . '-head" onclick="VivaWalletToggleSolution.call(this, \'' . $htmlId . '\'); return false;">' .
            '<span class="state-closed">' . __('Configure') . '</span>' .
            '<span class="state-opened">' . __('Close') . '</span>' .
            '</button>' .
            '</div>';
        $html .= '<div class="heading">';
        $html .= '<span class="heading-intro">' .
            '<div class="viva-wallet-payment-logo"></div>' .
            '<div class="viva-wallet-payment-text">' .
            '<strong>' . $element->getLegend() . '</strong>' .
            __('Viva Wallet payment method for your website') .
            '</div>' .
            '</span>';

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * @OVERRIDE
     * Get collapsed state on-load
     * WARNING: can't use type hint here, because of magento errors
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _isCollapseState($element): bool
    {
        return false;
    }

    /**
     * @OVERRIDE
     * added custom js for the tab collapse
     * WARNING: can't use type hint here, because of magento errors
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getExtraJs($element): string
    {
        $script = "require(['jquery', 'prototype'], function(jQuery){
            window.VivaWalletToggleSolution = function (id, url) {
                Fieldset.toggleCollapse(id, url);
            }
        });";

        return $this->_jsHelper->getScript($script);
    }
}
