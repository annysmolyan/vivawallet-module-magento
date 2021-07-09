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

namespace Smolyan\VivaWallet\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Smolyan\VivaWallet\Api\Data\OrderPaymentInterface;
use Smolyan\VivaWallet\Gateway\Helper\SubjectReader;
use Smolyan\VivaWallet\Helper\ConfigHelper;

/**
 * Class AuthorizeRequest
 * Authorize payment method for the Viva Wallet system
 *
 * @package Smolyan\VivaWallet\Gateway\Request
 */
class AuthorizeRequestBuilder implements BuilderInterface
{
    const ORDER_INFO_KEY = 'order';
    const AMOUNT_KEY = 'amount';
    const CARD_INFO_KEY = 'card';
    const ACCESS_TOKEN_KEY = 'access_token';

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * @param ConfigHelper $configHelper
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        ConfigHelper $configHelper,
        SubjectReader $subjectReader
    ) {
        $this->configHelper = $configHelper;
        $this->subjectReader = $subjectReader;
    }

    /**
     * Build data for the payment, obtain and validate data from the payment form
     * This data will be sent to transferFactory and will be used as request body for card (charge) token request
     * Prepare card data here
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject): array
    {
        $paymentInfo = $this->subjectReader->readPaymentInfoObject($buildSubject);
        $payment = $paymentInfo->getPayment();
        $order = $paymentInfo->getOrder();

        return [
            self::ORDER_INFO_KEY => [
                self::AMOUNT_KEY => $order->getGrandTotalAmount(),
            ],
            self::CARD_INFO_KEY => [
                OrderPaymentInterface::CC_CID => $payment->getCcCid(),
                OrderPaymentInterface::CC_NUMBER => $payment->getCcNumber(),
                OrderPaymentInterface::CC_OWNER => $payment->getCcOwner(),
                OrderPaymentInterface::CC_EXP_YEAR => $payment->getCcExpYear(),
                OrderPaymentInterface::CC_EXP_MONTH => $payment->getCcExpMonth(),
            ],
            self::ACCESS_TOKEN_KEY => $this->configHelper->getAccessToken(),
        ];
    }
}
