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

use Magento\Payment\Gateway\Command\CommandException;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Smolyan\VivaWallet\Gateway\Helper\SubjectReader;
use Smolyan\VivaWallet\Helper\ConfigHelper;

/**
 * Class VaultAuthorizeRequestBuilder
 * @package Smolyan\VivaWallet\Gateway\Request
 */
class VaultAuthorizeRequestBuilder implements BuilderInterface
{
    const CARD_TOKEN_KEY = 'card_token';
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
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     * @throws CommandException
     */
    public function build(array $buildSubject): array
    {
        $paymentInfo = $this->subjectReader->readPaymentInfoObject($buildSubject);
        $payment = $paymentInfo->getPayment();
        $extensionAttributes = $payment->getExtensionAttributes();
        $paymentToken = $extensionAttributes->getVaultPaymentToken();

        if ($paymentToken === null) {
            throw new CommandException(__('The Payment Token is not available to perform the request.'));
        }

        return [
            self::ACCESS_TOKEN_KEY => $this->configHelper->getAccessToken(),
            self::CARD_TOKEN_KEY => $paymentToken->getGatewayToken(),
        ];
    }
}
