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

namespace BelSmol\VivaWallet\Model\Http\Request;

use Magento\Framework\UrlInterface;
use BelSmol\VivaWallet\Api\Data\HttpRequestInterface;
use BelSmol\VivaWallet\Api\Data\HttpRequestInterfaceFactory;
use BelSmol\VivaWallet\Api\HttpRequestBuilderInterface;
use BelSmol\VivaWallet\Helper\ConfigHelper;

/**
 * Build object for API call
 * Class HttpRequestBuilder
 * @package BelSmol\VivaWallet\Model\Http\Request
 */
class HttpRequestBuilder implements HttpRequestBuilderInterface
{
    const PAYMENT_COMPLETE_CHECKOUT_FULL_ACTION_NAME = 'vivawallet/payment/completecheckout';

    /**
     * @var HttpRequestInterfaceFactory
     */
    protected $httpRequestFactory;

    /**
     * @var ConfigHelper
     */
    protected $configHelper;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @param HttpRequestInterfaceFactory $httpRequestFactory
     * @param ConfigHelper $configHelper
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        HttpRequestInterfaceFactory $httpRequestFactory,
        ConfigHelper $configHelper,
        UrlInterface $urlBuilder
    ) {
        $this->httpRequestFactory = $httpRequestFactory;
        $this->configHelper = $configHelper;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * See documentation here https://developer.vivawallet.com/authentication/#oauth-2-token-generation
     * @return HttpRequestInterface
     */
    public function buildAuthRequest(): HttpRequestInterface
    {
        $authRequest = $this->httpRequestFactory->create();
        $authRequest
            ->setHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json'
            ])
            ->setEndpoint($this->configHelper->getAuthEndpoint())
            ->setParams([
                'grant_type'=>'client_credentials',
                'client_id'=> $this->configHelper->getClientId(),
                'client_secret'=> $this->configHelper->getSecretKey(),
            ])
            ->setType(HttpRequestInterface::AUTH_RESPONSE_TYPE);

        return $authRequest;
    }

    /**
     * See documentation here https://developer.vivawallet.com/api-reference-guide/native-checkout-v2-api/#create-a-one-time-charge-token
     * @param string $accessToken
     * @param int $amount
     * @param string $cvc
     * @param string $number
     * @param string $holderName
     * @param string $expYear
     * @param string $expMonth
     * @return HttpRequestInterface
     */
    public function buildChargeRequest(
        string $accessToken,
        int $amount,
        string $cvc,
        string $number,
        string $holderName,
        string $expYear,
        string $expMonth
    ): HttpRequestInterface {
        $chargeRequest = $this->httpRequestFactory->create();
        $chargeRequest
            ->setHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->setEndpoint($this->configHelper->getChargeEndpoint())
            ->setParams([
                'amount' => $amount,
                'cvc' => $cvc,
                'number' => $number,
                'holderName' => $holderName,
                'expirationYear' => $expYear,
                'expirationMonth' => $expMonth,
                'sessionRedirectUrl' => $this->urlBuilder->getUrl(self::PAYMENT_COMPLETE_CHECKOUT_FULL_ACTION_NAME),
            ])
            ->setUseParamsAsJson(true)
            ->setType(HttpRequestInterface::CHARGE_RESPONSE_TYPE);

        return $chargeRequest;
    }

    /**
     * See documentation here https://developer.vivawallet.com/api-reference-guide/native-checkout-v2-api/#create-transaction
     * @param string $accessToken
     * @param int $amount
     * @param string $sourceCode
     * @param string $chargeToken
     * @param string $merchantTrns
     * @param string $customerTrns
     * @param string $currencyCode
     * @param string $email
     * @param string $phone
     * @param string $fullName
     * @param string $requestLang
     * @param string $countryCode
     * @param bool $preAuth
     * @param int $installments
     * @return HttpRequestInterface
     */
    public function buildTransactionRequest(
        string $accessToken,
        int $amount,
        string $sourceCode,
        string $chargeToken,
        string $merchantTrns,
        string $customerTrns,
        string $currencyCode,
        string $email,
        string $phone,
        string $fullName,
        string $requestLang,
        string $countryCode,
        bool $preAuth = false,
        int $installments = 0
    ): HttpRequestInterface {
        $transactionRequest = $this->httpRequestFactory->create();
        $transactionRequest
            ->setHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->setEndpoint($this->configHelper->getTransactionEndpoint())
            ->setParams([
                'amount' => $amount,
                'preauth' => $preAuth,
                'sourceCode' => $sourceCode,
                'chargeToken' => $chargeToken,
                'installments' => $installments,
                'merchantTrns' => $merchantTrns,
                'customerTrns' => $customerTrns,
                'currencyCode' => $currencyCode,
                'customer' => [
                    'email' => $email,
                    'phone' => $phone,
                    'fullname' => $fullName,
                    'requestLang' => $requestLang,
                    'countryCode' => $countryCode,
                ],
            ])
            ->setUseParamsAsJson(true)
            ->setType(HttpRequestInterface::TRANSACTION_RESPONSE_TYPE);

        return $transactionRequest;
    }

    /**
     * Request for saving card on VivaWallet side
     * See documentation here https://developer.vivawallet.com/api-reference-guide/card-tokenization-api/#step-2-generate-card-token-using-the-charge-token-optional
     * @param string $accessToken
     * @param string $chargeToken
     * @return HttpRequestInterface
     */
    public function buildCardTokenRequest(string $accessToken, string $chargeToken): HttpRequestInterface
    {
        $tokenRequest = $this->httpRequestFactory->create();
        $tokenRequest
            ->setHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->setEndpoint($this->configHelper->getCardSaveEndpoint() . $chargeToken)
            ->setType(HttpRequestInterface::CARD_SAVE_RESPONSE_TYPE)
            ->setIsPost(false);

        return $tokenRequest;
    }

    /**
     * See documentation here https://developer.vivawallet.com/api-reference-guide/card-tokenization-api/#step-3-generate-one-time-charge-token-using-card-token-optional
     * @param string $accessToken
     * @param string $cardToken
     * @return HttpRequestInterface
     */
    public function buildChargeRequestByCard(string $accessToken, string $cardToken): HttpRequestInterface
    {
        $chargeRequest = $this->httpRequestFactory->create();
        $chargeRequest
            ->setHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ])
            ->setEndpoint($this->configHelper->getChargeByCardEndpoint() . $cardToken)
            ->setType(HttpRequestInterface::CHARGE_BY_CARD_RESPONSE_TYPE)
            ->setIsPost(false);

        return $chargeRequest;
    }
}
