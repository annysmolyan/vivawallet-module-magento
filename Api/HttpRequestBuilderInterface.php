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

namespace Smolyan\VivaWallet\Api;

use Smolyan\VivaWallet\Api\Data\HttpRequestInterface;

/**
 * Build object for API call
 * Interface HttpRequestBuilderInterface
 * @package Smolyan\VivaWallet\Api
 */
interface HttpRequestBuilderInterface
{
    /**
     * See documentation here https://developer.vivawallet.com/authentication/#oauth-2-token-generation
     * @return HttpRequestInterface
     */
    public function buildAuthRequest(): HttpRequestInterface;

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
    ): HttpRequestInterface;

    /**
     * See documentation here https://developer.vivawallet.com/api-reference-guide/card-tokenization-api/#step-3-generate-one-time-charge-token-using-card-token-optional
     * @param string $accessToken
     * @param string $cardToken
     * @return HttpRequestInterface
     */
    public function buildChargeRequestByCard(
        string $accessToken,
        string $cardToken
    ): HttpRequestInterface;

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
    ): HttpRequestInterface;

    /**
     * Request for saving card on VivaWallet side
     * See documentation here https://developer.vivawallet.com/api-reference-guide/card-tokenization-api/#step-2-generate-card-token-using-the-charge-token-optional
     * @param string $accessToken
     * @param string $chargeToken
     * @return HttpRequestInterface
     */
    public function buildCardTokenRequest(string $accessToken, string $chargeToken): HttpRequestInterface;
}
