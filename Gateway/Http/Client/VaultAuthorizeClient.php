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
use Smolyan\VivaWallet\Gateway\Request\VaultAuthorizeRequestBuilder;
use Smolyan\VivaWallet\Model\Http\Response\ChargeByCardResponse;

/**
 * Class VaultAuthorizeClient
 * @package Smolyan\VivaWallet\Gateway\Http\Client
 */
class VaultAuthorizeClient implements ClientInterface
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
     * Try to get card charge token here
     *
     * @param TransferInterface $transferObject
     * @return array
     * @throws \Exception
     */
    public function placeRequest(TransferInterface $transferObject): array
    {
        $request = $transferObject->getBody();

        /** @var ChargeByCardResponse $response */
        $response = $this->httpRepository->getChargeByCard(
            $request[VaultAuthorizeRequestBuilder::ACCESS_TOKEN_KEY],
            $request[VaultAuthorizeRequestBuilder::CARD_TOKEN_KEY]
        );

        return [
            ChargeByCardResponse::CHARGE_TOKEN_KEY => $response->getChargeToken(),
            ChargeByCardResponse::STATUS_CODE_KEY => $response->getStatusCode(),
        ];
    }
}
