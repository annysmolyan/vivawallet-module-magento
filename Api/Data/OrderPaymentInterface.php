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

namespace Smolyan\VivaWallet\Api\Data;

use Magento\Sales\Api\Data\OrderPaymentInterface as MagentoOrderPaymentInterface;

/**
 * @OVERRIDE
 * Add additional constants
 * Interface OrderPaymentInterface
 * @package Smolyan\VivaWallet\Api\Data
 */
interface OrderPaymentInterface extends MagentoOrderPaymentInterface
{
    const CC_CID_ENC = 'cc_cid_enc';
    const CC_CID = 'cc_cid';
    const CC_NUMBER = 'cc_number';
    const MASKED_CC = 'maskedCC';
    const CARD_TOKEN = 'card_token';
    const EXPIRATION_DATE = 'expirationDate';
    const TYPE = 'type';
}
