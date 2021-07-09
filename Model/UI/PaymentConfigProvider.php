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
use Smolyan\VivaWallet\Api\Data\AuthenticationResponseInterface;
use Smolyan\VivaWallet\Api\HttpRepositoryInterface;
use Smolyan\VivaWallet\Helper\ConfigHelper;
use Smolyan\VivaWallet\Model\CcConfig;

/**
 * Class PaymentConfigProvider
 *
 * Config class for remote config payment viva-wallet method.
 * Use this class for providing data from backend to frontend
 * You can get config in your js component using window object, for example window.checkoutConfig.payment.viva_wallet
 *
 * @package Smolyan\VivaWallet\Model\UI
 */
class PaymentConfigProvider
{
    const CODE = 'viva_wallet';
    const CC_VAULT_CODE = 'viva_wallet_cc_vault';

    const PAYMENT_OBJECTS_CONFIG_KEY = 'payment';
    const IS_ACTIVE_CONFIG_KEY = 'isActive';
    const TITLE_CONFIG_KEY = 'title';
    const CODE_CONFIG_KEY = 'code';
    const AVAILABLE_TYPES_CONFIG_KEY = 'availableTypes';
    const MONTHS_CONFIG_KEY = 'months';
    const YEARS_CONFIG_KEY = 'years';
    const HAS_VERIFICATION_CONFIG_KEY = 'hasVerification';
    const ACCESS_TOKEN_CONFIG_KEY = 'accessToken';
    const CHARGE_ULR_CONFIG_KEY = 'chargeUrl';
    const CC_VAULT_CODE_KEY = 'ccVaultCode';
    const CHARGE_REDIRECT_FULL_ACTION_NAME = 'vivawallet/payment/chargeredirect';

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var CcConfig
     */
    protected $ccConfig;

    /**
     * @var HttpRepositoryInterface
     */
    protected $endpointRepository;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param ConfigHelper $configHelper
     * @param CcConfig $ccConfig
     * @param HttpRepositoryInterface $endpointRepository
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        ConfigHelper $configHelper,
        CcConfig $ccConfig,
        HttpRepositoryInterface $endpointRepository,
        UrlInterface $urlBuilder
    ) {
        $this->configHelper = $configHelper;
        $this->ccConfig = $ccConfig;
        $this->endpointRepository = $endpointRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig(): array
    {
        return[
            self::PAYMENT_OBJECTS_CONFIG_KEY => [
                self::CODE => [
                    self::IS_ACTIVE_CONFIG_KEY => $this->configHelper->isPaymentEnabled(),
                    self::TITLE_CONFIG_KEY => $this->configHelper->getTitle(),
                    self::CODE_CONFIG_KEY => self::CODE,
                    self::ACCESS_TOKEN_CONFIG_KEY => $this->getAccessToken(),
                    self::AVAILABLE_TYPES_CONFIG_KEY => [self::CODE => $this->ccConfig->getCcAllowedTypes()],
                    self::MONTHS_CONFIG_KEY => [self::CODE => $this->ccConfig->getCcMonths()],
                    self::YEARS_CONFIG_KEY => [self::CODE => $this->ccConfig->getCcYears()],
                    self::HAS_VERIFICATION_CONFIG_KEY => [self::CODE => $this->ccConfig->hasVerification()],
                    self::CHARGE_ULR_CONFIG_KEY => $this->urlBuilder->getUrl(self::CHARGE_REDIRECT_FULL_ACTION_NAME),
                    self::CC_VAULT_CODE_KEY => self::CC_VAULT_CODE,
                ]
            ]
        ];
    }

    /**
     * TODO think about this method
     * Get access token for the payment
     * @return string
     */
    public function getAccessToken(): string
    {
        $now = time();
        $token = $this->configHelper->getAccessToken();

        if (!$token || ($this->configHelper->getAccessTokenExpiredTime() <= $now)) {
            /** @var AuthenticationResponseInterface $authResponse */
            $authResponse = $this->endpointRepository->getAuth();
            $token = $authResponse->getAccessToken();
            $expTime = $authResponse->getExpiredTime();
            $this->configHelper->setAccessToken($token);
            $this->configHelper->setAccessTokenExpiredTime($expTime);
        }

        return $token;
    }
}
