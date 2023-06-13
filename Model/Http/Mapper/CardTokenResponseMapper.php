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

use BelSmol\VivaWallet\Api\Data\CardTokenResponseInterface;
use BelSmol\VivaWallet\Api\Data\CardTokenResponseInterfaceFactory;
use BelSmol\VivaWallet\Api\Data\HttpResponseInterface;
use BelSmol\VivaWallet\Api\ResponseMapperInterface;

/**
 * Class CardTokenResponseMapper
 * @package BelSmol\VivaWallet\Model\Http\Mapper
 */
class CardTokenResponseMapper implements ResponseMapperInterface
{
    protected const TOKEN_PARAM = 'token';

    /**
     * @var CardTokenResponseInterfaceFactory
     */
    protected $cardTokenResponseFactory;

    /**
     * @param CardTokenResponseInterfaceFactory $cardTokenResponseFactory
     */
    public function __construct(CardTokenResponseInterfaceFactory $cardTokenResponseFactory)
    {
        $this->cardTokenResponseFactory = $cardTokenResponseFactory;
    }

    /**
     * Convert api response to object
     * @param int $statusCode
     * @param array $response
     * @return HttpResponseInterface
     */
    public function toEntity(int $statusCode, array $response): HttpResponseInterface
    {
        /** @var CardTokenResponseInterface $chargeResponse */
        $entity = $this->cardTokenResponseFactory->create();

        $entity->setStatusCode($statusCode);
        $entity->setToken($response[self::TOKEN_PARAM] ?? '');

        return $entity;
    }
}
