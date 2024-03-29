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

use BelSmol\VivaWallet\Api\Data\ChargeResponseInterface;
use BelSmol\VivaWallet\Api\Data\ChargeResponseInterfaceFactory;
use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;
use BelSmol\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class ChargeResponseMapper
 * @package BelSmol\VivaWallet\Model\Http\Mapper
 */
class ChargeResponseMapper implements ResponseMapperInterface
{
    protected const CHARGE_TOKEN_PARAM = 'chargeToken';
    protected const REDIRECT_FORM_PARAM = 'redirectToACSForm';

    /**
     * @var ChargeResponseInterfaceFactory
     */
    protected $chargeResponseFactory;

    /**
     * @param ChargeResponseInterfaceFactory $chargeResponseFactory
     */
    public function __construct(ChargeResponseInterfaceFactory $chargeResponseFactory)
    {
        $this->chargeResponseFactory = $chargeResponseFactory;
    }

    /**
     * Convert api response to object
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface
    {
        /** @var ChargeResponseInterface $chargeResponse */
        $entity = $this->chargeResponseFactory->create();

        $entity->setStatusCode($statusCode);
        $entity->setChargeToken($response[self::CHARGE_TOKEN_PARAM] ?? '');
        $entity->setRedirectToACSForm($response[self::REDIRECT_FORM_PARAM] ?? '');

        return $entity;
    }
}
