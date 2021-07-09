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

namespace Smolyan\VivaWallet\Model\UI;

use Magento\Framework\UrlInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterface;
use Magento\Vault\Model\Ui\TokenUiComponentInterfaceFactory;
use Magento\Vault\Model\Ui\TokenUiComponentProviderInterface;
use Smolyan\VivaWallet\Model\UI\PaymentConfigProvider;

/**
 * Class TokenUiComponentProvider
 * @package Smolyan\VivaWallet\Model\UI
 */
class TokenUiComponentProvider implements TokenUiComponentProviderInterface
{
    const COMPLETE_CHECKOUT_URL = 'vivawallet/payment/completecheckout';
    /**
     * @var TokenUiComponentInterfaceFactory
     */
    protected $componentFactory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param TokenUiComponentInterfaceFactory $componentFactory
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        TokenUiComponentInterfaceFactory $componentFactory,
        UrlInterface $urlBuilder
    ) {
        $this->componentFactory = $componentFactory;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Payment vault provider on front
     * @param PaymentTokenInterface $paymentToken
     * @return TokenUiComponentInterface
     * @since 100.1.0
     */
    public function getComponentForToken(PaymentTokenInterface $paymentToken)
    {
        $jsonDetails = json_decode($paymentToken->getTokenDetails() ?: '{}', true);
        $component = $this->componentFactory->create(
            [
                'config' => [
                    'code' => PaymentConfigProvider::CC_VAULT_CODE,
                    self::COMPONENT_DETAILS => $jsonDetails,
                    self::COMPONENT_PUBLIC_HASH => $paymentToken->getPublicHash(),
                    'completeCheckoutUrl' => $this->urlBuilder->getUrl(self::COMPLETE_CHECKOUT_URL),
                ],
                'name' => 'Smolyan_VivaWallet/js/view/payment/method-renderer/vault'
            ]
        );

        return $component;
    }
}
