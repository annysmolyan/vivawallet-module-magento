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

namespace Smolyan\VivaWallet\Plugin\Model;

use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Api\PaymentTokenManagementInterface as Subject;

/**
 * Class PaymentTokenManagementPlugin
 * @package Smolyan\VivaWallet\Plugin\Model
 */
class PaymentTokenManagementPlugin
{
    /**
     * This plugin should fix magento bug when saving vault occurs.
     * When user use the same card and don't uncheck "Save my card" option, magento throws exception: "Unique constraint violation found"
     * More details about fix and bug report here: https://github.com/Adyen/adyen-magento2/issues/678
     *
     * @param Subject $paymentTokenManagement
     * @param callable $proceed
     * @param PaymentTokenInterface $token
     * @param OrderPaymentInterface $payment
     * @return bool
     */
    public function aroundSaveTokenWithPaymentLink(
        Subject $paymentTokenManagement,
        callable $proceed,
        PaymentTokenInterface $token,
        OrderPaymentInterface $payment
    ) : bool {

        $order = $payment->getOrder();

        // Guests should already be handled by public_hash, and is not relevant to us.
        if ($order->getCustomerIsGuest()) {
            return $proceed($token, $payment);
        }

        $existingToken = $paymentTokenManagement->getByGatewayToken(
            $token->getGatewayToken(),
            $payment->getMethodInstance()->getCode(),
            $order->getCustomerId()
        );

        // If we don't have a token for specified gateway token, fallback to public_hash logic.
        if ($existingToken === null) {
            return $proceed($token, $payment);
        }

        // Merge the token that is being saved with our existing token.
        $existingToken->addData($token->getData());
        return $proceed($existingToken, $payment);
    }
}
