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

namespace BelSmol\VivaWallet\Gateway\Request;

use Magento\Framework\Locale\Resolver as LocaleResolver;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Vault\Model\Ui\VaultConfigProvider;
use BelSmol\VivaWallet\Api\Data\OrderPaymentInterface;
use BelSmol\VivaWallet\Api\HttpClientInterface;
use BelSmol\VivaWallet\Api\HttpRequestBuilderInterface;
use BelSmol\VivaWallet\Gateway\Helper\SubjectReader;
use BelSmol\VivaWallet\Helper\ConfigHelper;

/**
 * Class CaptureRequestBuilder
 * @package BelSmol\VivaWallet\Gateway\Request
 */
class CaptureRequestBuilder implements BuilderInterface
{
    const ACCESS_TOKEN_KEY = 'access_token';
    const AMOUNT = 'amount';
    const SOURCE_CODE = 'source_code';
    const CHARGE_TOKEN = 'charge_token';
    const MERCHANT_TRNS = 'merchant_trns';
    const CUSTOMER_TRNS = 'customer_trns';
    const CURRENCY_CODE = 'currency_code';
    const EMAIL = 'email';
    const PHONE = 'phone';
    const FULL_NAME = 'full_name';
    const COUNTRY_CODE = 'country_code';
    const REQUEST_LANG = 'request_lang';

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var ConfigHelper
     */
    protected $localeResolver;

    /**
     * @var HttpRequestBuilderInterface
     */
    protected $httpRequestBuilder;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var SubjectReader
     */
    protected $subjectReader;

    /**
     * @param ConfigHelper $configHelper
     * @param LocaleResolver $localeResolver
     * @param HttpRequestBuilderInterface $httpRequestBuilder
     * @param HttpClientInterface $httpClient
     * @param SubjectReader $subjectReader
     */
    public function __construct(
        ConfigHelper $configHelper,
        LocaleResolver $localeResolver,
        HttpRequestBuilderInterface $httpRequestBuilder,
        HttpClientInterface $httpClient,
        SubjectReader $subjectReader
    ) {
        $this->configHelper = $configHelper;
        $this->localeResolver = $localeResolver;
        $this->httpRequestBuilder = $httpRequestBuilder;
        $this->httpClient = $httpClient;
        $this->subjectReader = $subjectReader;
    }

    /**
     * Build capture request here
     * @param array $buildSubject
     * @return array
     * @throws \Exception
     */
    public function build(array $buildSubject): array
    {
        $paymentInfo = $this->subjectReader->readPaymentInfoObject($buildSubject);
        $payment = $paymentInfo->getPayment();
        $order = $payment->getOrder();
        $ccTransactionId = $payment->getCcTransId();
        $customerTrns = $this->configHelper->getMerchantTrnReference()
            . ' Order Number: ' . $order->getIncrementId()
            . '. Order payment amount: '
            . $payment->getAmountAuthorized();

        if ($payment->getAdditionalInformation(VaultConfigProvider::IS_ACTIVE_CODE)) {
            $cardToken = $this->getSavedCardToken($this->configHelper->getAccessToken(), $payment->getCcTransId());
            $payment->addData([OrderPaymentInterface::CARD_TOKEN => $cardToken]);
        }

        return [
            self::ACCESS_TOKEN_KEY => $this->configHelper->getAccessToken(),
            self::AMOUNT => $payment->getAmountAuthorized(),
            self::SOURCE_CODE => $this->configHelper->getSourceCode(),
            self::CHARGE_TOKEN => $ccTransactionId,
            self::MERCHANT_TRNS => $this->configHelper->getMerchantTrnReference(),
            self::CUSTOMER_TRNS => $customerTrns,
            self::CURRENCY_CODE => $this->configHelper->getCurrencyIsoNumber(),
            self::EMAIL => $order->getBillingAddress()->getEmail(),
            self::PHONE => $order->getBillingAddress()->getTelephone(),
            self::FULL_NAME => $order->getBillingAddress()->getFirstname() . ' ' . $order->getBillingAddress()->getLastname(),
            self::REQUEST_LANG => strstr($this->localeResolver->getLocale(), '_', true),
            self::COUNTRY_CODE => $order->getBillingAddress()->getCountryId(),
        ];
    }

    /**
     * if vault enabled and user want to save card
     * then send request to VivaWallet and get card token
     * @param string $accessToken
     * @param string $ccTransId
     * @return string
     */
    private function getSavedCardToken(string $accessToken, string $ccTransId): string
    {
        $cardTokenRequest = $this->httpRequestBuilder->buildCardTokenRequest($accessToken, $ccTransId);
        $cardTokenResponse = $this->httpClient->call($cardTokenRequest);
        return $cardTokenResponse->getToken();
    }
}
