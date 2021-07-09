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

namespace Smolyan\VivaWallet\Observer\Gateway;

use Magento\Framework\DataObject;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;
use Smolyan\VivaWallet\Api\Data\OrderPaymentInterface;

/**
 * Class DataAssignObserver
 * Assign data from payment form, get data from payment js component.
 * see geData() method in method-renderer/viva-wallet-method.js
 * @package Smolyan\VivaWallet\Observer\Gateway
 */
class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param EncryptorInterface $encryptor
     */
    public function __construct(EncryptorInterface $encryptor)
    {
        $this->encryptor = $encryptor;
    }

    /**
     * Assign data from payment form, get data from payment js component.
     * see geData() method in method-renderer/viva-wallet-method.js
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $paymentModel = $this->readPaymentModelArgument($observer);
        $dataObject = $this->readDataArgument($observer);
        $additionalData = $dataObject->getData(PaymentInterface::KEY_ADDITIONAL_DATA);

        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        $paymentModel->addData([
            OrderPaymentInterface::CC_TYPE => $additionalData->getCcType(),
            OrderPaymentInterface::CC_OWNER => $additionalData->getCcOwner(),
            OrderPaymentInterface::CC_LAST_4 => substr($additionalData->getCcNumber(), -4),
            OrderPaymentInterface::CC_NUMBER_ENC => $this->encryptor->encrypt((string)$additionalData->getCcNumber()),
            OrderPaymentInterface::CC_CID_ENC => $this->encryptor->encrypt((string)$additionalData->getCcCid()),
            OrderPaymentInterface::CC_EXP_MONTH => $additionalData->getCcExpMonth(),
            OrderPaymentInterface::CC_EXP_YEAR => $additionalData->getCcExpYear(),
        ]);
    }
}
