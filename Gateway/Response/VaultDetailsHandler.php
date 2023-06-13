<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License v3 (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * @category BelSmol
 * @package BelSmol_VivaWallet
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GNU General Public License v3 (GPL 3.0)
 */

namespace BelSmol\VivaWallet\Gateway\Response;

use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Api\Data\OrderPaymentExtension;
use Magento\Sales\Api\Data\OrderPaymentExtensionInterfaceFactory;
use Magento\Vault\Api\Data\PaymentTokenFactoryInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use BelSmol\VivaWallet\Api\Data\OrderPaymentInterface;
use BelSmol\VivaWallet\Gateway\Helper\SubjectReader;

/**
 * Class VaultDetailsHandler
 * @package BelSmol\VivaWallet\Gateway\Response
 */
class VaultDetailsHandler implements HandlerInterface
{
    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * @var PaymentTokenFactoryInterface
     */
    protected $paymentTokenFactory;

    /**
     * @var OrderPaymentExtensionInterfaceFactory
     */
    protected $paymentExtensionFactory;

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * @param SubjectReader $subjectReader
     * @param PaymentTokenFactoryInterface $paymentTokenFactory
     * @param JsonSerializer $jsonSerializer
     * @param OrderPaymentExtensionInterfaceFactory $paymentExtensionFactory
     */
    public function __construct(
        SubjectReader $subjectReader,
        PaymentTokenFactoryInterface $paymentTokenFactory,
        JsonSerializer $jsonSerializer,
        OrderPaymentExtensionInterfaceFactory $paymentExtensionFactory
    ) {
        $this->subjectReader = $subjectReader;
        $this->paymentTokenFactory = $paymentTokenFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->paymentExtensionFactory = $paymentExtensionFactory;
    }

    /**
     * Manage here card vault. Trying to prepare card info for vault storage
     * @param array $handlingSubject
     * @param array $response
     * @return void
     * @throws \Exception
     */
    public function handle(array $handlingSubject, array $response): void
    {
        $paymentDO = $this->subjectReader->readPayment($handlingSubject);
        $payment = $paymentDO->getPayment();
        $cardToken = $payment->getData(OrderPaymentInterface::CARD_TOKEN);

        if (!$cardToken) {
            return;
        }

        $paymentToken = $this->getVaultPaymentToken($payment, $cardToken);
        $extensionAttributes = $this->getExtensionAttributes($payment);
        $extensionAttributes->setVaultPaymentToken($paymentToken);
    }

    /**
     * Create vault payment token entity to extension attributes
     * @param InfoInterface $payment
     * @param string $cardToken
     * @return PaymentTokenInterface
     * @throws \Exception
     */
    protected function getVaultPaymentToken(InfoInterface $payment, string $cardToken): PaymentTokenInterface
    {
        /** @var PaymentTokenInterface $paymentToken */
        $paymentToken   = $this->paymentTokenFactory->create(PaymentTokenFactoryInterface::TOKEN_TYPE_CREDIT_CARD);
        $expirationDate = $this->getExpirationDate(
            $payment->getCcExpMonth(),
            $payment->getCcExpYear()
        );

        $tokenDetails = [
            OrderPaymentInterface::TYPE => $payment->getCcType(),
            OrderPaymentInterface::MASKED_CC => $payment->getCcLast4(),
            OrderPaymentInterface::EXPIRATION_DATE => $expirationDate,
        ];

        $paymentToken
            ->setPaymentMethodCode($payment->getCode())
            ->setType('card')
            ->setExpiresAt($expirationDate)
            ->setGatewayToken($cardToken)
            ->setTokenDetails($this->jsonSerializer->serialize($tokenDetails));

        return $paymentToken;
    }

    /**
     * @param string $expirationMonth
     * @param string $expirationYear
     * @return string
     * @throws \Exception
     */
    protected function getExpirationDate(string $expirationMonth, string $expirationYear): string
    {
        $expDate = new \DateTime(
            $expirationYear . '-' . $expirationMonth . '-' . '01' . ' ' . '00:00:00',
            new \DateTimeZone('UTC')
        );
        $expDate->add(new \DateInterval('P1M'));
        return $expDate->format('Y-m-d');
    }

    /**
     * Get payment extension attributes
     * @param $payment
     * @return OrderPaymentExtension
     */
    protected function getExtensionAttributes(InfoInterface $payment): OrderPaymentExtension
    {
        $extensionAttributes = $payment->getExtensionAttributes();
        if (null === $extensionAttributes) {
            $extensionAttributes = $this->paymentExtensionFactory->create();
            $payment->setExtensionAttributes($extensionAttributes);
        }
        return $extensionAttributes;
    }
}
