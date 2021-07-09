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

namespace Smolyan\VivaWallet\Gateway\Helper;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Helper\SubjectReader as MagentoSubjectReader;
use Smolyan\VivaWallet\Api\Data\ChargeByCardResponseInterface;
use Smolyan\VivaWallet\Api\Data\ChargeResponseInterface;
use Smolyan\VivaWallet\Api\Data\TransactionResponseInterface;

/**
 * @OVERRIDE
 * Class SubjectReader
 * Used in response handler
 * @package Smolyan\VivaWallet\Gateway\Helper
 */
class SubjectReader extends MagentoSubjectReader
{
    protected const PAYMENT_OBJECT_KEY = 'payment';

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(CheckoutSession $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param array $buildSubject
     * @return PaymentDataObjectInterface
     */
    public function readPaymentInfoObject(array $buildSubject): PaymentDataObjectInterface
    {
        if (!isset($buildSubject[self::PAYMENT_OBJECT_KEY])
            || !$buildSubject[self::PAYMENT_OBJECT_KEY] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }
        return $buildSubject[self::PAYMENT_OBJECT_KEY];
    }

    /**
     * Provide chargeToken
     * @param array $response
     * @return string
     */
    public function readCcTransaction(array $response): string
    {
        if (!isset($response[ChargeResponseInterface::CHARGE_TOKEN_KEY])) {
            throw new \InvalidArgumentException('Wrong CC transaction.');
        }

        return $response[ChargeResponseInterface::CHARGE_TOKEN_KEY];
    }

    /**
     * Provide card chargeToken
     * @param array $response
     * @return string
     */
    public function readCcTransactionForDirectCard(array $response): string
    {
        if (!isset($response[ChargeByCardResponseInterface::CHARGE_TOKEN_KEY])) {
            throw new \InvalidArgumentException('Wrong CC transaction.');
        }

        return $response[ChargeByCardResponseInterface::CHARGE_TOKEN_KEY];
    }

    /**
     * @param array $response
     * @return string
     */
    public function readChargeForm(array $response): string
    {
        if (!isset($response[ChargeResponseInterface::REDIRECT_TO_ASC_FORM_KEY])) {
            throw new \InvalidArgumentException('Empty charge form.');
        }

        return $response[ChargeResponseInterface::REDIRECT_TO_ASC_FORM_KEY];
    }

    /**
     * Provide transaction id
     * @param array $response
     * @return string
     */
    public function readPaymentTransactionId(array $response): string
    {
        if (!isset($response[TransactionResponseInterface::TRANSACTION_ID_KEY])) {
            throw new \InvalidArgumentException('Wrong payment transaction.');
        }

        return $response[TransactionResponseInterface::TRANSACTION_ID_KEY];
    }
}
