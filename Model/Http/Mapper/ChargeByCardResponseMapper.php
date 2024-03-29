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

namespace BelSmol\VivaWallet\Model\Http\Mapper;

use BelSmol\VivaWallet\Api\Data\ChargeByCardResponseInterface;
use BelSmol\VivaWallet\Api\Data\ChargeByCardResponseInterfaceFactory;
use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;
use BelSmol\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class ChargeByCardResponseMapper
 * @package BelSmol\VivaWallet\Model\Http\Mapper
 */
class ChargeByCardResponseMapper implements ResponseMapperInterface
{
    protected const CHARGE_TOKEN_PARAM = 'chargeToken';

    /**
     * @var ChargeByCardResponseInterfaceFactory
     */
    protected $chargeByCardResponseFactory;

    /**
     * @param ChargeByCardResponseInterfaceFactory $chargeByCardResponseFactory
     */
    public function __construct(ChargeByCardResponseInterfaceFactory $chargeByCardResponseFactory)
    {
        $this->chargeByCardResponseFactory = $chargeByCardResponseFactory;
    }

    /**
     * Convert api response to object
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface
    {
        /** @var ChargeByCardResponseInterface $chargeResponse */
        $entity = $this->chargeByCardResponseFactory->create();

        $entity->setStatusCode($statusCode);
        $entity->setChargeToken($response[self::CHARGE_TOKEN_PARAM] ?? '');

        return $entity;
    }
}
