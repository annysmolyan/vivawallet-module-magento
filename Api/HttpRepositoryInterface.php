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

use Smolyan\VivaWallet\Api\Data\HttpResponseInterface;

/**
 * Interface HttpRepositoryInterface
 * @package Smolyan\VivaWallet\Api
 */
interface HttpRepositoryInterface
{
    /**
     * Execute api endpoint end return response
     * @return HttpResponseInterface
     */
    public function getAuth(): HttpResponseInterface;

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
    ): HttpResponseInterface;

    /**
     * Execute api endpoint end return response
     * @param string $accessToken
     * @param string $cardToken
     * @return HttpResponseInterface
     */
    public function getChargeByCard(
        string $accessToken,
        string $cardToken
    ): HttpResponseInterface;


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
    ): HttpResponseInterface;
}
