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

namespace BelSmol\VivaWallet\Model\Http;

use BelSmol\VivaWallet\Api\Data\HttpRequestInterface;
use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;
use BelSmol\VivaWallet\Api\HttpRepositoryInterface;
use BelSmol\VivaWallet\Api\HttpRequestBuilderInterface;
use BelSmol\VivaWallet\Api\HttpClientInterface;

/**
 * Class HttpRepository
 * Get data from api
 * @package BelSmol\VivaWallet\Model\Http
 */
class HttpRepository implements HttpRepositoryInterface
{
    /**
     * @var HttpRequestBuilderInterface
     */
    protected $httpRequestBuilder;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     * @param HttpRequestBuilderInterface $httpRequestBuilder
     */
    public function __construct(
        HttpClientInterface $httpClient,
        HttpRequestBuilderInterface $httpRequestBuilder
    ) {
        $this->httpRequestBuilder = $httpRequestBuilder;
        $this->httpClient = $httpClient;
    }

    /**
     * Execute api endpoint end return response
     * @return HttpResponseInterface
     * @throws \Exception
     */
    public function getAuth(): HttpResponseInterface
    {
        /** @var HttpRequestInterface $request */
        $request = $this->httpRequestBuilder->buildAuthRequest();
        /** @var HttpResponseInterface $response */
        $response = $this->httpClient->call($request);
        return $response;
    }

    /**
     * Execute api endpoint end return response
     * @param string $accessToken
     * @param int $amount
     * @param string $cvc
     * @param string $number
     * @param string $holderName
     * @param string $expYear
     * @param string $expMonth
     * @return HttpResponseInterface
     * @throws \Exception
     */
    public function getCharge(
        string $accessToken,
        int $amount,
        string $cvc,
        string $number,
        string $holderName,
        string $expYear,
        string $expMonth
    ): HttpResponseInterface {

        /** @var HttpRequestInterface $request */
        $request = $this->httpRequestBuilder->buildChargeRequest(
            $accessToken,
            $amount,
            $cvc,
            $number,
            $holderName,
            $expYear,
            $expMonth
        );
        /** @var HttpResponseInterface $response */
        $response = $this->httpClient->call($request);
        return $response;
    }

    /**
     * Execute api endpoint end return response
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
     * @return HttpResponseInterface
     */
    public function getTransaction(
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
    ): HttpResponseInterface {

        /** @var HttpRequestInterface $request */
        $request = $this->httpRequestBuilder->buildTransactionRequest(
            $accessToken,
            $amount,
            $sourceCode,
            $chargeToken,
            $merchantTrns,
            $customerTrns,
            $currencyCode,
            $email,
            $phone,
            $fullName,
            $requestLang,
            $countryCode
        );
        return $this->httpClient->call($request);
    }

    /**
     * Execute api endpoint end return response
     * @param string $accessToken
     * @param string $cardToken
     * @return HttpResponseInterface
     */
    public function getChargeByCard(string $accessToken, string $cardToken): HttpResponseInterface
    {
        $request = $this->httpRequestBuilder->buildChargeRequestByCard($accessToken, $cardToken);
        return $this->httpClient->call($request);
    }
}
