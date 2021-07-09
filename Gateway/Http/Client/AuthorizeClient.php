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

namespace Smolyan\VivaWallet\Gateway\Http\Client;

use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Smolyan\VivaWallet\Api\Data\AuthenticationResponseInterface;
use Smolyan\VivaWallet\Api\Data\OrderPaymentInterface;
use Smolyan\VivaWallet\Api\HttpRepositoryInterface;
use Smolyan\VivaWallet\Gateway\Request\AuthorizeRequestBuilder;
use Smolyan\VivaWallet\Model\Http\Response\ChargeResponse;

/**
 * Class AuthorizeClient
 * @package Smolyan\VivaWallet\Gateway\Http\Client
 */
class AuthorizeClient implements ClientInterface
{
    /**
     * @var HttpRepositoryInterface
     */
    protected $httpRepository;

    /**
     * @param HttpRepositoryInterface $httpRepository
     */
    public function __construct(HttpRepositoryInterface $httpRepository)
    {
        $this->httpRepository = $httpRepository;
    }

    /**
     * Places request to gateway. Returns result as ENV array
     * Try to get charge token here and form for redirection user
     *
     * @param TransferInterface $transferObject
     * @return array
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $request = $transferObject->getBody();

        /** @var ChargeResponse $response */
        $response = $this->httpRepository->getCharge(
            $request[AuthenticationResponseInterface::ACCESS_TOKEN_KEY],
            $request[AuthorizeRequestBuilder::ORDER_INFO_KEY][AuthorizeRequestBuilder::AMOUNT_KEY],
            $request[AuthorizeRequestBuilder::CARD_INFO_KEY][OrderPaymentInterface::CC_CID],
            $request[AuthorizeRequestBuilder::CARD_INFO_KEY][OrderPaymentInterface::CC_NUMBER],
            $request[AuthorizeRequestBuilder::CARD_INFO_KEY][OrderPaymentInterface::CC_OWNER],
            $request[AuthorizeRequestBuilder::CARD_INFO_KEY][OrderPaymentInterface::CC_EXP_YEAR],
            $request[AuthorizeRequestBuilder::CARD_INFO_KEY][OrderPaymentInterface::CC_EXP_MONTH]
        );

        return [
            ChargeResponse::CHARGE_TOKEN_KEY => $response->getChargeToken(),
            ChargeResponse::STATUS_CODE_KEY => $response->getStatusCode(),
            ChargeResponse::REDIRECT_TO_ASC_FORM_KEY => $response->getRedirectToACSForm(),
        ];
    }
}
