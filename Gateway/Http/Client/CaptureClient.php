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
use Smolyan\VivaWallet\Api\HttpRepositoryInterface;
use Smolyan\VivaWallet\Gateway\Request\CaptureRequestBuilder;
use Smolyan\VivaWallet\Model\Http\Response\TransactionResponse;

/**
 * Class CaptureClient
 * @package Smolyan\VivaWallet\Gateway\Http\Client
 */
class CaptureClient implements ClientInterface
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
     * Capture order. Create real transaction
     * Places request to gateway. Returns result as ENV array
     *
     * @param \Magento\Payment\Gateway\Http\TransferInterface $transferObject
     * @return array
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $request = $transferObject->getBody();

        /** @var TransactionResponse $response */
        $response = $this->httpRepository->getTransaction(
            $request[CaptureRequestBuilder::ACCESS_TOKEN_KEY],
            $request[CaptureRequestBuilder::AMOUNT],
            $request[CaptureRequestBuilder::SOURCE_CODE],
            $request[CaptureRequestBuilder::CHARGE_TOKEN],
            $request[CaptureRequestBuilder::MERCHANT_TRNS],
            $request[CaptureRequestBuilder::CUSTOMER_TRNS],
            $request[CaptureRequestBuilder::CURRENCY_CODE],
            $request[CaptureRequestBuilder::EMAIL],
            $request[CaptureRequestBuilder::PHONE],
            $request[CaptureRequestBuilder::FULL_NAME],
            $request[CaptureRequestBuilder::REQUEST_LANG],
            $request[CaptureRequestBuilder::COUNTRY_CODE]
        );

        return [
            TransactionResponse::TRANSACTION_ID_KEY => $response->getTransactionId(),
            TransactionResponse::STATUS_CODE_KEY => $response->getStatusCode(),
        ];
    }
}
