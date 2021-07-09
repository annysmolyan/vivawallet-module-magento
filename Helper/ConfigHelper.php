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

namespace Smolyan\VivaWallet\Helper;

use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\PageCache\Model\Cache\Type;
use Magento\Store\Model\ScopeInterface;

/**
 * Class ConfigHelper
 * get settings from core_config
 *
 * @package Smolyan\VivaWallet\Helper
 */
class ConfigHelper extends AbstractHelper
{
    const IS_ENABLED_CONFIG = 'payment/viva_wallet/active';
    const TITLE_CONFIG = 'payment/viva_wallet/title';
    const MIN_ORDER_TOTAL_CONFIG = 'payment/viva_wallet/min_order_total';
    const IS_DEBUG_ENABLED_CONFIG = 'payment/viva_wallet/debug';
    const TEST_PAYMENT_URL_CONFIG = 'payment/viva_wallet/test_payment_url';
    const PRODUCTION_PAYMENT_URL_CONFIG = 'payment/viva_wallet/production_payment_url';
    const CCTYPES_CONFIG = 'payment/viva_wallet/cctypes';
    const CLIENT_ID_CONFIG = 'payment/viva_wallet/client_id';
    const SECRET_KEY_CONFIG = 'payment/viva_wallet/secret_key';
    const AUTH_PROD_ENDPOINT_CONFIG = 'payment/viva_wallet/auth_prod_endpoint';
    const AUTH_DEBUG_ENDPOINT_CONFIG = 'payment/viva_wallet/auth_debug_endpoint';
    const MERCHANT_TRN_REFERENCE_CONFIG = 'payment/viva_wallet/merchant_trn_reference';
    const CURRENCY_ISO_NUMBER_CONFIG = 'payment/viva_wallet/currency_iso_number';

    const CHARGE_PROD_ENDPOINT_CONFIG = 'payment/viva_wallet/charge_prod_endpoint';
    const CHARGE_DEBUG_ENDPOINT_CONFIG = 'payment/viva_wallet/charge_debug_endpoint';
    const TRANSACTION_PROD_ENDPOINT_CONFIG = 'payment/viva_wallet/transaction_prod_endpoint';
    const TRANSACTION_DEBUG_ENDPOINT_CONFIG = 'payment/viva_wallet/transaction_debug_endpoint';
    const PROD_SOURCE_CODE_CONFIG = 'payment/viva_wallet/prod_source_code';
    const DEBUG_SOURCE_CODE_CONFIG = 'payment/viva_wallet/debug_source_code';

    const IS_VAULT_ENABLED_CONFIG = 'payment/viva_wallet_cc_vault/active';
    const VAULT_TITLE_CONFIG = 'payment/viva_wallet_cc_vault/title';

    /**
     * Service constants. Non visible in admin. Used for global settings
     */
    const ACCESS_TOKEN_EXP_TIME_CONFIG = 'payment/viva_wallet/access_token_exp_time';
    const ACCESS_TOKEN_CONFIG = 'payment/viva_wallet/access_token';

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var TypeListInterface
     */
    protected $cacheTypeList;

    /**
     * @param Context $context
     * @param WriterInterface $configWriter
     * @param TypeListInterface $cacheTypeList
     */
    public function __construct(
        Context $context,
        WriterInterface $configWriter,
        TypeListInterface $cacheTypeList
    ) {
        parent::__construct($context);
        $this->configWriter = $configWriter;
        $this->cacheTypeList = $cacheTypeList;
    }

    /**
     * @return bool
     */
    public function isPaymentEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_ENABLED_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::TITLE_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return int
     */
    public function getMinimumOrderTotal(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::MIN_ORDER_TOTAL_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_DEBUG_ENABLED_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getTestPaymentUrl(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::TEST_PAYMENT_URL_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getProductionPaymentUrl(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::PRODUCTION_PAYMENT_URL_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::CLIENT_ID_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::SECRET_KEY_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return array
     */
    public function getAllowedCcTypes(): array
    {
        $types = (string)$this->scopeConfig->getValue(
            self::CCTYPES_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
        return explode(',', $types);
    }

    /**
     * @return string
     */
    public function getAuthProdEndpoint(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::AUTH_PROD_ENDPOINT_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getAuthDebugEndpoint(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::AUTH_DEBUG_ENDPOINT_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getAuthEndpoint(): string
    {
        return $this->isDebugModeEnabled() ? $this->getAuthDebugEndpoint() : $this->getAuthProdEndpoint();
    }

    /**
     * @return string
     */
    public function getChargeEndpoint(): string
    {
        $domain = $this->isDebugModeEnabled() ? $this->getTestPaymentUrl() : $this->getProductionPaymentUrl();
        return $domain . '/nativecheckout/v2/chargetokens';
    }

    /**
     * @return string
     */
    public function getChargeByCardEndpoint(): string
    {
        $domain = $this->isDebugModeEnabled() ? $this->getTestPaymentUrl() : $this->getProductionPaymentUrl();
        return $domain . '/acquiring/v1/cards/chargetokens?token=';
    }

    /**
     * @return string
     */
    public function getTransactionEndpoint(): string
    {
        $domain = $this->isDebugModeEnabled() ? $this->getTestPaymentUrl() : $this->getProductionPaymentUrl();
        return $domain . '/nativecheckout/v2/transactions';
    }

    /**
     * @return string
     */
    public function getCardSaveEndpoint(): string
    {
        $domain = $this->isDebugModeEnabled() ? $this->getTestPaymentUrl() : $this->getProductionPaymentUrl();
        return $domain . '/acquiring/v1/cards/tokens?chargetoken=';
    }

    /**
     * @return string
     */
    public function getProductionSourceCode(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::PROD_SOURCE_CODE_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getDebugSourceCode(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::DEBUG_SOURCE_CODE_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getSourceCode(): string
    {
        return $this->isDebugModeEnabled() ? $this->getDebugSourceCode() : $this->getProductionSourceCode();
    }

    /**
     * @return string
     */
    public function getMerchantTrnReference(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::MERCHANT_TRN_REFERENCE_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return int
     */
    public function getCurrencyIsoNumber(): int
    {
        return (int)$this->scopeConfig->getValue(
            self::CURRENCY_ISO_NUMBER_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Save global vivawallet access token expired time
     * @param int|null $time
     */
    public function setAccessTokenExpiredTime(int $time = null): void
    {
        $this->configWriter->save(self::ACCESS_TOKEN_EXP_TIME_CONFIG, $time);
        $this->cacheTypeList->cleanType(Config::TYPE_IDENTIFIER);
        $this->cacheTypeList->cleanType(Type::TYPE_IDENTIFIER);
    }

    /**
     * @return int|null
     */
    public function getAccessTokenExpiredTime(): ?int
    {
        return $this->scopeConfig->getValue(self::ACCESS_TOKEN_EXP_TIME_CONFIG);
    }

    /**
     * @return void
     */
    public function deleteAccessTokenExpiredTime(): void
    {
        $this->configWriter->delete(self::ACCESS_TOKEN_EXP_TIME_CONFIG);
    }

    /**
     * Save global vivawallet access token
     * @param string $token
     */
    public function setAccessToken(string $token): void
    {
        $this->configWriter->save(self::ACCESS_TOKEN_CONFIG, $token);
        $this->cacheTypeList->cleanType(Config::TYPE_IDENTIFIER);
        $this->cacheTypeList
            ->cleanType(Type::TYPE_IDENTIFIER);
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return (string)$this->scopeConfig->getValue(self::ACCESS_TOKEN_CONFIG);
    }

    /**
     * @return void
     */
    public function deleteAccessToken(): void
    {
        $this->configWriter->delete(self::ACCESS_TOKEN_CONFIG);
    }

    /**
     * @return bool
     */
    public function isVaultEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::IS_VAULT_ENABLED_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getVaultTitle(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::VAULT_TITLE_CONFIG,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
